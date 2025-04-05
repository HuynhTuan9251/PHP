<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $action = $_POST['action'];

    // Kiểm tra sản phẩm có trong giỏ hàng không
    if (isset($_SESSION['cart'][$productId])) {
        if ($action === 'increase') {
            // Tăng số lượng
            $_SESSION['cart'][$productId]['quantity']++;
        } elseif ($action === 'decrease') {
            // Giảm số lượng
            if ($_SESSION['cart'][$productId]['quantity'] > 1) {
                $_SESSION['cart'][$productId]['quantity']--;
            }
        }
    }

    // Điều hướng trở lại trang giỏ hàng
    header('Location: /webbanhang/cart.php');
    exit();
}
