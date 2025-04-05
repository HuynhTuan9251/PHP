<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];

    // Kiểm tra sản phẩm có trong giỏ hàng không và xóa
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }

    // Điều hướng trở lại trang giỏ hàng
    header('Location: /webbanhang/cart.php');
    exit();
}
