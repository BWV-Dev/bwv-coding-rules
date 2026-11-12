<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\{CallLike, FuncCall, MethodCall, StaticCall};
use PhpParser\Node\Scalar\InterpolatedString;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * WebSecurityRules.md 1 / SEC-DB-001 (CRITICAL)
 * "Use parameterized queries or ORM query builders that bind parameters
 * safely. Never concatenate user input directly into SQL."
 *   Bad : $this->query()->whereRaw("user.name LIKE {$nameInput}")
 *   Good: $this->query()->whereRaw('user.name LIKE ?', [$nameInput])
 *
 * WHY A CUSTOM RULE
 * -----------------
 * Nothing in the stack looks at SQL. PHPStan's own rules stop at types, and a
 * CRITICAL rule with zero tool coverage is exactly what a custom rule is for.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Expression-local syntax check, NOT taint analysis. It fires when a raw-SQL
 * sink receives a string built by interpolation ("... {$x}"), concatenation
 * ('...' . $x) or sprintf() — regardless of where that value came from. So:
 *   - a concatenated constant (`'... ' . self::TABLE`) is a false positive;
 *   - `$sql = "... {$x}"; DB::select($sql);` (built one line earlier) is a
 *     false negative — there is no interprocedural/flow taint tracking here.
 * The trade is deliberate: the shape it does catch is the shape the standard
 * shows, and both failure modes are visible at the call site during review.
 *
 * CakePHP sinks: `->execute($sql)` (Connection) and `->where($sql)` are
 * checked on the FIRST argument only, because `->where('col', 'a' . $x)` passes
 * a bound value, not SQL. `->where("name = '$x'")` and
 * `->execute('... ' . $x)` are reported. Laravel's `->where("a $x")` is
 * reported too (same shape, same risk). `->where(['a' => $x])` is not.
 * The global function exec() runs a SHELL command and is checked by
 * UserInputInPathRule (SEC-FILE-001); only PDO/mysqli methods named exec()
 * are SQL sinks here.
 *
 * Identifier: security.rawSqlInterpolation
 *
 * @implements Rule<CallLike>
 */
class RawSqlInterpolationRule implements Rule
{
    /**
     * Method sinks where only argument 0 is SQL (Cake execute(), builder where()).
     */
    private const FIRST_ARG_SINKS = ['execute', 'where'];

    /**
     * @var list<string>
     */
    private array $sqlSinks = [
        'whereraw', 'orwhereraw', 'havingraw', 'orhavingraw', 'selectraw',
        'orderbyraw', 'groupbyraw', 'joinraw', 'raw', 'statement',
        'unprepared', 'query', 'exec', 'mysqli_query', 'pg_query',
    ];

    /**
     * @var list<string>
     */
    private array $facadeSinks = ['select', 'insert', 'update', 'delete'];

    public function getNodeType(): string {
        return CallLike::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $name = $this->calleeName($node);
        if ($name === null) {
            return [];
        }

        $isSink = in_array($name, $this->sqlSinks, true)
            || ($node instanceof MethodCall && in_array($name, self::FIRST_ARG_SINKS, true))
            // DB::select()/insert()/update()/delete() take raw SQL; a query
            // builder's ->select() takes column names, so require a StaticCall.
            || ($node instanceof StaticCall && in_array($name, $this->facadeSinks, true));
        // The global function exec() runs a SHELL command (SEC-FILE-001), not
        // SQL; only PDO::exec()/mysqli methods named exec are SQL sinks.
        if ($node instanceof FuncCall && $name === 'exec') {
            return [];
        }

        if (! $isSink) {
            return [];
        }

        $args = $node->getArgs();
        if ($node instanceof MethodCall && in_array($name, self::FIRST_ARG_SINKS, true)) {
            $args = array_slice($args, 0, 1);
        }

        foreach ($args as $arg) {
            if (! $this->isBuiltString($arg->value)) {
                continue;
            }

            return [
                RuleErrorBuilder::message(sprintf(
                    'Raw SQL passed to %s() is built by string interpolation/concatenation; bind parameters instead (SEC-DB-001).',
                    $name,
                ))
                    ->identifier('security.rawSqlInterpolation')
                    ->line($node->getStartLine())
                    ->build(),
            ];
        }

        return [];
    }

    private function calleeName(CallLike $node): ?string {
        $nameNode = match (true) {
            $node instanceof MethodCall, $node instanceof StaticCall => $node->name,
            $node instanceof FuncCall => $node->name,
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

    private function isBuiltString(Node $expr): bool {
        // InterpolatedString is php-parser 5's node for "text {$var}";
        // Encapsed is the php-parser 4 name, kept for older installs.
        if ($expr instanceof InterpolatedString || $expr instanceof Node\Scalar\Encapsed) {
            return true;
        }

        if ($expr instanceof Concat) {
            return true;
        }

        return $expr instanceof FuncCall
            && $expr->name instanceof Node\Name
            && strtolower($expr->name->getLast()) === 'sprintf';
    }
}
