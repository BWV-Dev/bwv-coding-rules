# Testing Rules

## Table of Contents

- [**1. Các loại test**](#1)
- [**2. Quy tắc UT**](#2)
- [**3. Quy tắc IT**](#3)
- [**4. Quy tắc E2E**](#4)
- [**5. Coverage**](#5)
- [**6. Ngoại lệ không cần test**](#6)
- [**7. Sửa test khi có thay đổi**](#7)

## 1. Các loại test <a id="1"></a>

| Loại | Định nghĩa | Bắt buộc cho |
| --- | --- | --- |
| **UT** (Unit Test) | Test riêng một function hoặc class. | Logic nghiệp vụ, tính toán, validate, xử lý điều kiện |
| **IT** (Integration Test) | Test nhiều phần của hệ thống chạy cùng nhau, có DB thật. | Mọi API endpoint, và các query DB có điều kiện lọc, join, tổng hợp |
| **E2E** (End-to-End) | Test cả hệ thống qua giao diện (UI). | Các luồng nghiệp vụ chính |

## 2. Quy tắc UT <a id="2"></a>

- Không gọi DB, API bên ngoài, không đọc/ghi file.
- Chỉ mock các hàm/class mà hàm đang test gọi trực tiếp. Không mock chính hàm đang test.
- Nếu hàm dùng thời gian hiện tại (ví dụ tính hạn, tính tuổi, so sánh với ngày hôm nay) hoặc giá trị random (ví dụ sinh mã OTP), phải cố định thời gian và random trong test, để kết quả lần chạy nào cũng giống nhau.

  ```ts
  // Bad: hôm nay pass, vài ngày sau fail dù code không đổi
  it('does not expire an order created 5 days ago', () => {
    const order = { createdAt: new Date('2026-10-25') };
    expect(isExpired(order)).toBe(false);
  });

  // Good 👍 cố định "bây giờ"
  afterEach(() => jest.useRealTimers());

  it('does not expire an order created 5 days ago', () => {
    jest.useFakeTimers().setSystemTime(new Date('2026-10-30'));
    const order = { createdAt: new Date('2026-10-25') };
    expect(isExpired(order)).toBe(false);
  });
  ```

- Test cả trường hợp đúng lẫn từng trường hợp lỗi (để trống, sai định dạng, vượt giới hạn...), mỗi trường hợp một test.

  ```ts
  // Bad: chỉ test happy path
  it('accepts a valid email', () => {
    expect(validateEmail('a@b.com').ok).toBe(true);
  });

  // Good 👍 mỗi trường hợp một test
  it.each([
    ['a@b.com', true],
    ['', false],                         // để trống
    ['abc', false],                      // sai định dạng
    [`${'a'.repeat(250)}@b.com`, false], // quá dài
  ])('validateEmail(%j) returns ok = %s', (input, ok) => {
    expect(validateEmail(input).ok).toBe(ok);
  });
  ```

## 3. Quy tắc IT <a id="3"></a>

- IT dùng DB riêng cho test (ví dụ chạy bằng container), cùng loại và phiên bản với production. Không dùng chung DB dev.
- Mỗi test tự tạo dữ liệu nó cần, không dựa vào dữ liệu có sẵn trong DB.

  ```ts
  // Bad: giả định DB đã có user id = 1 đang bị khóa
  it('rejects login of a locked user', async () => {
    const res = await login({ userId: 1 });
    expect(res.status).toBe(403);
  });

  // Good 👍 test tự tạo dữ liệu nó cần
  it('rejects login of a locked user', async () => {
    const user = await db.user.create({ data: { email: 'locked@test.com', status: 'locked' } });
    const res = await login({ userId: user.id });
    expect(res.status).toBe(403);
  });
  ```

- Sau mỗi test phải dọn dữ liệu test đã tạo, để test sau bắt đầu từ DB sạch.
- Mỗi API endpoint test cả trường hợp thành công lẫn trường hợp lỗi: dữ liệu không hợp lệ, không tìm thấy, chưa đăng nhập, không có quyền.
- Mock:
  - Chỉ mock service nằm ngoài project (third-party hoặc service nội bộ của công ty), email/SMS, thời gian, random.
  - Không mock DB và các layer nội bộ của project.

## 4. Quy tắc E2E <a id="4"></a>

- Test trường hợp thành công của các luồng nghiệp vụ chính (login, luồng chính của sản phẩm, thanh toán nếu có) và các trường hợp lỗi quan trọng.
- E2E tìm element theo role, text hiển thị hoặc `data-testid`, không theo class CSS hay cấu trúc HTML, để refactor giao diện không làm vỡ E2E.

  ```ts
  // Bad: phụ thuộc class CSS và cấu trúc HTML
  await page.locator('div.form > button.btn-primary').click();

  // Good 👍
  await page.getByRole('button', { name: 'Đăng ký' }).click();
  await page.getByTestId('submit-order').click();
  ```

- Dùng tài khoản test riêng cho E2E.
- Dữ liệu E2E:
  - E2E tự tạo dữ liệu cần dùng, không phụ thuộc dữ liệu có sẵn trên môi trường.
  - Dữ liệu do E2E tạo phải nhận biết được (ví dụ email hoặc tên bắt đầu bằng `e2e-`).
  - Test xong phải xóa dữ liệu vừa tạo, kể cả khi test fail. Chỉ xóa đúng dữ liệu đó, không xóa dữ liệu khác trên môi trường.
- Người viết E2E tự xác định các luồng cần test dựa trên tài liệu thiết kế.
- Mỗi project nên giữ danh sách các luồng E2E trong một file để review.

## 5. Coverage <a id="5"></a>

- **Code mới hoặc thay đổi** trong PR phải đạt mức coverage tối thiểu theo bảng dưới, chỉ tính UT và IT (E2E không tính vào coverage). Không đạt thì PR không được merge.

| Loại | Đo gì | Mức tối thiểu cho code mới | Lý do |
| --- | --- | --- | --- |
| **Line** | Phần code được test chạy qua | **80%** | Để phần lớn code mới đều được test chạy qua. |
| **Branch** | Các trường hợp đúng/sai của mỗi điều kiện | **70%** | Để cả nhánh đúng lẫn nhánh sai của mỗi điều kiện đều được test. Thấp hơn line vì khó đạt hơn. |
| **Statement** | Các câu lệnh được test chạy qua | Không bắt buộc, chỉ theo dõi | Gần giống line, nên không cần quy định riêng. |
| **Function** | Các hàm được test gọi ít nhất một lần | Không bắt buộc, chỉ theo dõi | Gọi hàm một lần là đạt, không phản ánh test đủ hay chưa, nên không cần quy định riêng. |

## 6. Ngoại lệ không cần test <a id="6"></a>

- Không yêu cầu test và không tính coverage cho: code sinh tự động, file cấu hình, migration, class chỉ chứa dữ liệu.
- Muốn loại một đoạn code khỏi coverage: phải confirm với PL trước.

## 7. Sửa test khi có thay đổi <a id="7"></a>

- **Đổi hành vi** (requirement mới):
  - UT/IT: sửa test theo yêu cầu mới. Không sửa test cho dễ pass hơn (ví dụ bỏ bớt điều kiện kiểm tra).

    ```ts
    // Requirement mới: phí ship tăng từ 100 lên 150
    // Trước: expect(calcTotal(order)).toBe(1500);

    // Bad: nới assertion cho test pass
    expect(calcTotal(order)).toBeDefined();

    // Good 👍 sửa giá trị mong đợi theo requirement mới
    expect(calcTotal(order)).toBe(1550);
    ```

  - E2E: sửa test theo yêu cầu mới, đồng thời cập nhật danh sách luồng E2E.
- **Refactor** (không đổi hành vi):
  - Không phải sửa test (UT, IT, E2E).
  - Refactor mà test bị fail nghĩa là test đang kiểm tra cách viết code thay vì kết quả. Phải sửa test để kiểm tra kết quả.
- **Xóa feature hoặc code**:
  - UT/IT: xóa test tương ứng.
  - E2E: xóa test E2E và luồng trong danh sách E2E.
  - Không giữ lại test bằng cách comment hoặc skip.
- **Sửa bug**: viết kèm một test tái hiện đúng bug đó (regression test):
  - Chạy trên code chưa sửa: test fail (chứng minh test bắt được bug).
  - Chạy sau khi sửa: test pass.

    ```ts
    // Good 👍 Bug #1234: đơn hàng 0đ vẫn bị tính phí ship
    it('does not charge shipping when order total is 0', () => {
      expect(calcShippingFee({ total: 0 })).toBe(0);
    });
    ```

- Ngoài các trường hợp trên, chỉ xóa test khi hành vi nó kiểm tra đã bị bỏ, hoặc đã có test khác kiểm tra cùng hành vi đó.
- Không commit test bị tắt (`.skip`) hoặc chỉ chạy riêng (`.only`). Trường hợp bắt buộc phải tắt tạm, phải confirm với PL.
