# Testing Rules

## Table of Contents

- [**1. Test types**](#1)
- [**2. UT rules**](#2)
- [**3. IT rules**](#3)
- [**4. E2E rules**](#4)
- [**5. Coverage**](#5)
- [**6. Exceptions that do not need tests**](#6)
- [**7. Updating tests when something changes**](#7)

## 1. Test types <a id="1"></a>

| Type | Definition | Required for |
| --- | --- | --- |
| **UT** (Unit Test) | Tests a single function or class. | Business logic, calculations, validation, conditional handling |
| **IT** (Integration Test) | Tests several parts of the system running together, with a real DB. | Every API endpoint, and DB queries with filters, joins or aggregation |
| **E2E** (End-to-End) | Tests the whole system through the UI. | Main business flows |

## 2. UT rules <a id="2"></a>

- Do not call the DB or external APIs, and do not read or write files.
- Only mock what a UT cannot run with: the DB, external APIs, files, time, random values. Use the project's real functions/classes wherever possible. Do not mock the function under test itself.

  ```ts
  // Bad: mocks the project's own calcTax, so the test only checks that it was called
  jest.mock('./tax');
  it('calculates the order total', () => {
    calcTotal({ subtotal: 1000 });
    expect(calcTax).toHaveBeenCalledWith(1000);
  });

  // Good 👍 uses the real calcTax and checks the result
  it('calculates the order total', () => {
    expect(calcTotal({ subtotal: 1000 })).toBe(1100);
  });
  ```

- Tests must not depend on each other or on the order they run in.

  ```ts
  // Bad: 'removes an item' only passes if 'adds an item' ran before it
  let cart;
  it('adds an item', () => {
    cart = createCart();
    cart.add('book');
    expect(cart.items).toHaveLength(1);
  });
  it('removes an item', () => {
    cart.remove('book');
    expect(cart.items).toHaveLength(0);
  });

  // Good 👍 each test sets up what it needs
  it('removes an item', () => {
    const cart = createCart();
    cart.add('book');
    cart.remove('book');
    expect(cart.items).toHaveLength(0);
  });
  ```

- If a function uses the current time (e.g. calculating a deadline or an age, comparing with today) or random values (e.g. generating an OTP), fix the time and the random values in the test, so that every run gives the same result.

  ```ts
  // Bad: passes today, fails a few days later even though the code has not changed
  it('does not expire an order created 5 days ago', () => {
    const order = { createdAt: new Date('2026-10-25') };
    expect(isExpired(order)).toBe(false);
  });

  // Good 👍 fix "now"
  afterEach(() => jest.useRealTimers());

  it('does not expire an order created 5 days ago', () => {
    jest.useFakeTimers().setSystemTime(new Date('2026-10-30'));
    const order = { createdAt: new Date('2026-10-25') };
    expect(isExpired(order)).toBe(false);
  });
  ```

- Test the valid case, the boundary values (e.g. exactly at the limit, 0) and each error case (empty, wrong format, over the limit...), one test per case.

  ```ts
  // Bad: only tests the happy path
  it('accepts a valid email', () => {
    expect(validateEmail('a@b.com').ok).toBe(true);
  });

  // Good 👍 one test per case
  it.each([
    ['a@b.com', true],
    ['', false],                         // empty
    ['abc', false],                      // wrong format
    [`${'a'.repeat(248)}@b.com`, true],  // exactly at the limit (254 characters)
    [`${'a'.repeat(249)}@b.com`, false], // one character over the limit
  ])('validateEmail(%j) returns ok = %s', (input, ok) => {
    expect(validateEmail(input).ok).toBe(ok);
  });
  ```

## 3. IT rules <a id="3"></a>

- IT uses a separate DB for tests (e.g. running in a container), of the same type and version as production. Do not share the dev DB.
- Each test creates the data it needs and does not rely on data that already exists in the DB.

  ```ts
  // Bad: assumes the DB already has a locked user with id = 1
  it('rejects login of a locked user', async () => {
    const res = await login({ userId: 1 });
    expect(res.status).toBe(403);
  });

  // Good 👍 the test creates the data it needs
  it('rejects login of a locked user', async () => {
    const user = await db.user.create({ data: { email: 'locked@test.com', status: 'locked' } });
    const res = await login({ userId: user.id });
    expect(res.status).toBe(403);
  });
  ```

- After each test, clean up the test data it created, so that the next test starts from a clean DB.
- For each API endpoint, test the success case and the error cases that apply to that endpoint: invalid data, not found, not logged in, no permission.
- For endpoints that change data, check the stored data as well as the response.

  ```ts
  // Bad: only checks the response
  it('creates an order', async () => {
    const res = await api.post('/orders', { productId: 1, quantity: 2 });
    expect(res.status).toBe(201);
  });

  // Good 👍 also checks what was stored
  it('creates an order', async () => {
    const res = await api.post('/orders', { productId: 1, quantity: 2 });
    expect(res.status).toBe(201);
    const order = await db.order.findUnique({ where: { id: res.body.id } });
    expect(order.quantity).toBe(2);
  });
  ```

- Mock:
  - Only mock services outside the project (third-party or internal company services), email/SMS, time, random values.
  - Do not mock the DB or the project's internal layers.

## 4. E2E rules <a id="4"></a>

- Test the success case of the main business flows (login, the product's main flow, payment if any) and the important error cases.
- E2E finds elements by role, visible text or `data-testid`, not by CSS class or HTML structure, so that refactoring the UI does not break E2E.

  ```ts
  // Bad: depends on CSS classes and HTML structure
  await page.locator('div.form > button.btn-primary').click();

  // Good 👍
  await page.getByRole('button', { name: 'Sign up' }).click();
  await page.getByTestId('submit-order').click();
  ```

- Use dedicated test accounts for E2E.
- Tests must not depend on each other or on the order they run in.
- E2E data:
  - E2E creates the data it needs and does not depend on data that already exists in the environment.
  - Data created by E2E must be identifiable (e.g. an email or name starting with `e2e-`).
  - When a test finishes, delete the data it created, even when the test fails. Delete only that data, not any other data in the environment.
- The person writing E2E decides which flows to test, based on the design documents.
- Each project should keep the list of E2E flows in a file for review.

## 5. Coverage <a id="5"></a>

- **New or changed code** in a PR must reach the minimum coverage in the table below, counting UT and IT only (E2E does not count toward coverage). A PR that does not reach it cannot be merged.

| Type | What it measures | Minimum for new code | Reason |
| --- | --- | --- | --- |
| **Line** | Code that tests run through | **80%** | So that most new code is run by tests. |
| **Branch** | The true/false cases of each condition | **70%** (applies when the project's tooling can measure it) | So that both the true and the false branch of each condition are tested. Lower than line because it is harder to reach. |
| **Statement** | Statements that tests run through | Not required, tracked only | Almost the same as line, so no separate rule is needed. |
| **Function** | Functions that tests call at least once | Not required, tracked only | One call is enough to pass, which does not show whether the function is tested enough, so no separate rule is needed. |

## 6. Exceptions that do not need tests <a id="6"></a>

- Tests are not required and coverage is not counted for: generated code, configuration files, migrations, classes that only hold data.
- To exclude a piece of code from coverage, confirm with the Project Leader (PL) first.

## 7. Updating tests when something changes <a id="7"></a>

- **Behavior change** (new requirement):
  - UT/IT: update the tests to match the new requirement. Do not change a test to make it pass more easily (e.g. by removing checks).
  - E2E: update the tests to match the new requirement, and update the list of E2E flows (if any).
- **Refactor** (no behavior change):
  - A correct refactor does not require changing tests (UT, IT, E2E).
  - If a test fails after a refactor, check whether the returned result has changed:
    - It changed: the refactor broke the behavior, so fix the code.
    - It did not change: the test is checking how the code is written instead of the result, so fix the test to check the result.
- **Removing a feature or code**:
  - UT/IT: delete the corresponding tests.
  - E2E: delete the E2E tests and remove the flow from the list of E2E flows (if any).
  - Do not keep tests by commenting them out or skipping them.
- **Bug fix**: add a test that reproduces that exact bug (regression test):
  - Run on the unfixed code: the test fails (proving that the test catches the bug).
  - Run after the fix: the test passes.

    ```ts
    // Good 👍 Bug #1234: an order with a total of 0 is still charged shipping
    it('does not charge shipping when order total is 0', () => {
      expect(calcShippingFee({ total: 0 })).toBe(0);
    });
    ```

- Only delete a test when the behavior it checks has been removed, or when another test already checks the same behavior.
- Do not commit disabled tests (`.skip`) or tests set to run alone (`.only`). If a test must be disabled temporarily, confirm with the PL.
