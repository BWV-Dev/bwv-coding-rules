<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node\Expr\{CallLike, FuncCall, MethodCall, StaticCall, Variable};
use PhpParser\{Node, NodeFinder};
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * WebSecurityRules.md 6 / SEC-FILE-001 (CRITICAL)
 * "Avoid using direct user input for file paths, redirects, outbound URLs or
 * shell commands. Use IDs, allowlists and safe mapping functions."
 *   Bad : return redirect($request->input('hiddenInputUrl'));
 *         $detail = Storage::get($request->input('filePath'));
 * Shell sinks (global exec, shell_exec, system, passthru, popen, proc_open)
 * are covered. There is NO outbound-HTTP sink (Http::get, curl);
 * file_get_contents on a URL is only caught as a file sink. Method-style
 * `$x->exec()` is SQL (SEC-DB-001), so shell sinks match plain function calls
 * only.
 * CakePHP: `$this->request->getQuery()/getData()/getParam()/getCookie()/
 * getHeaderLine()` (and `$request->...`) count as input; they are only
 * recognised on a receiver named request / getRequest().
 *
 * WHY A CUSTOM RULE
 * -----------------
 * A CRITICAL rule with no coverage anywhere in the stack: Rector and
 * PHP-CS-Fixer rewrite shape, not data flow, and PHPStan ships no taint
 * analysis.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Expression-local, NOT taint analysis: the request access must appear INSIDE
 * the sink call's arguments. Caught:
 *   Storage::get($request->input('p'))      redirect(request()->get('u'))
 *   fopen($_GET['f'], 'r')                  $this->redirect($request->input('u'))
 * Not caught (accepted false negatives):
 *   $p = $request->input('p'); Storage::get($p);   // one line apart
 *   a path that travelled through another method or a DTO
 * `include`/`require` are language constructs, not calls, so they are out of
 * this rule's node type — keep them out of user-controlled paths by review.
 *
 * Identifier: security.userInputInPath
 *
 * @implements Rule<CallLike>
 */
class UserInputInPathRule implements Rule
{
    /**
     * Filesystem / redirect sinks, matched on the called name alone.
     */
    private const PATH_SINKS = [
        'file_get_contents', 'file_put_contents', 'fopen', 'readfile', 'unlink',
        'copy', 'rename', 'move_uploaded_file', 'mkdir', 'rmdir', 'scandir',
        'glob', 'realpath', 'file', 'redirect', 'redirectto', 'away',
    ];

    /**
     * Sinks whose name is too generic to match alone — require a known facade.
     */
    private const FACADE_SINKS = ['get', 'put', 'download', 'url', 'to', 'delete', 'exists', 'path'];

    /**
     * @var list<string>
     */
    private const FACADES = ['storage', 'file', 'redirect', 'response'];

    /**
     * Request accessors: a method of this name on any receiver counts as input.
     */
    private const REQUEST_ACCESSORS = ['input', 'query', 'post', 'cookie', 'header', 'all'];

    /**
     * Shell-execution sinks: plain function calls only.
     */
    private const SHELL_SINKS = ['exec', 'shell_exec', 'system', 'passthru', 'proc_open', 'popen'];

    /**
     * CakePHP ServerRequest accessors: only counted on a request receiver.
     */
    private const CAKE_ACCESSORS = ['getquery', 'getdata', 'getparam', 'getcookie', 'getheaderline'];

    private const SUPERGLOBALS = ['_GET', '_POST', '_REQUEST', '_COOKIE', '_FILES', '_SERVER'];

    public function getNodeType(): string {
        return CallLike::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        if (! $this->isPathSink($node)) {
            return [];
        }

        foreach ($node->getArgs() as $arg) {
            if (! $this->containsUserInput($arg->value)) {
                continue;
            }

            return [
                RuleErrorBuilder::message(
                    'User input reaches a file-path/redirect target directly; look the path or URL up by ID instead (SEC-FILE-001).',
                )
                    ->identifier('security.userInputInPath')
                    ->line($node->getStartLine())
                    ->build(),
            ];
        }

        return [];
    }

    private function isPathSink(CallLike $node): bool {
        $name = $this->calleeName($node);
        if ($name === null) {
            return false;
        }

        if (in_array($name, self::PATH_SINKS, true)
            || ($node instanceof FuncCall && in_array($name, self::SHELL_SINKS, true))
        ) {
            return true;
        }

        if (! in_array($name, self::FACADE_SINKS, true)) {
            return false;
        }

        // Storage::get(...) / Redirect::to(...) — generic names, so the
        // receiver has to be one of the known facades.
        $receiver = match (true) {
            $node instanceof StaticCall => $node->class instanceof Node\Name ? $node->class->getLast() : null,
            $node instanceof MethodCall => $node->var instanceof Variable && is_string($node->var->name)
                ? $node->var->name
                : null,
            default => null,
        };

        return $receiver !== null && in_array(strtolower($receiver), self::FACADES, true);
    }

    private function containsUserInput(Node $expr): bool {
        $finder = new NodeFinder();

        foreach ($finder->find($expr, static fn (Node $n): bool => $n instanceof Variable) as $variable) {
            /** @var Variable $variable */
            if (is_string($variable->name) && in_array($variable->name, self::SUPERGLOBALS, true)) {
                return true;
            }
        }

        foreach ($finder->find($expr, static fn (Node $n): bool => $n instanceof FuncCall) as $call) {
            /** @var FuncCall $call */
            if ($call->name instanceof Node\Name && strtolower($call->name->getLast()) === 'request') {
                return true;
            }
        }

        foreach ($finder->find($expr, static fn (Node $n): bool => $n instanceof MethodCall) as $call) {
            /** @var MethodCall $call */
            if ($call->name instanceof Node\Identifier
                && in_array($call->name->toLowerString(), self::REQUEST_ACCESSORS, true)
            ) {
                return true;
            }

            if ($call->name instanceof Node\Identifier
                && in_array($call->name->toLowerString(), self::CAKE_ACCESSORS, true)
                && $this->isRequestReceiver($call->var)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * `$request`, `$this->request`, `$this->getRequest()`.
     */
    private function isRequestReceiver(Node $var): bool {
        return match (true) {
            $var instanceof Variable => $var->name === 'request',
            $var instanceof Node\Expr\PropertyFetch => $var->name instanceof Node\Identifier
                && $var->name->toLowerString() === 'request',
            $var instanceof MethodCall => $var->name instanceof Node\Identifier
                && $var->name->toLowerString() === 'getrequest',
            default => false,
        };
    }

    private function calleeName(CallLike $node): ?string {
        $nameNode = match (true) {
            $node instanceof MethodCall, $node instanceof StaticCall, $node instanceof FuncCall => $node->name,
            default => null,
        };

        if ($nameNode instanceof Node\Identifier) {
            return $nameNode->toLowerString();
        }

        if ($nameNode instanceof Node\Name) {
            return strtolower($nameNode->getLast());
        }

        return null;
    }
}
