<?php
session_start();

// Xử lý logic thanh toán tại đây (ví dụ: lưu vào cơ sở dữ liệu)

// Sau khi thanh toán, xóa giỏ hàng
unset($_SESSION['cart']);

// Điều hướng đến trang hoàn tất đơn hàng
header('Location: /webbanhang/order_success.php');
exit();
