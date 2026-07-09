# Hướng Dẫn Triển Khai Ứng Dụng Với XAMPP

Thực hiện theo các bước dưới đây để thiết lập môi trường, cơ sở dữ liệu và khởi chạy ứng dụng.

---

## Bước 1: Thiết Lập Thư Mục Dự Án

* Tạo một thư mục mới có tên là **`WEB`** bên trong thư mục `htdocs` của XAMPP.
* **Đường dẫn mặc định:** > `C:\xampp\htdocs\WEB`

---

## Bước 2: Cấu Hình Database (Cơ Sở Dữ Liệu)

1. Mở **XAMPP Control Panel** và nhấn **Start** dịch vụ **MySQL**.
2. Truy cập vào trình quản lý dữ liệu theo đường dẫn: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
3. Tiến hành tạo database mới và **Import** file `database.sql` vào.

---

## Bước 3: Khởi Chạy Ứng Dụng

>  **Lưu ý:** Đảm bảo dịch vụ **Apache** đã được khởi động (`Start`) trên XAMPP Control Panel.

Sau khi kích hoạt Apache, bạn có thể truy cập vào hệ thống qua 2 giao diện:

### Giao diện Client (Người dùng)
* **Đường dẫn:** [http://localhost/WEB/client](http://localhost/WEB/client)

### Giao diện Admin (Quản trị)
* **Đường dẫn:** [http://localhost/WEB/admnin](http://localhost/WEB/admnin)
