<?php

include 'app/views/shares/header.php';

// Cập nhật số lượng sản phẩm trong giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $id => $new_quantity) {
        $_SESSION['cart'][$id]['quantity'] = max(1, intval($new_quantity)); // Đảm bảo số lượng tối thiểu là 1
    }
    header("Location: /webbanhang/Product/cart"); // Reload lại trang giỏ hàng
    exit();
}

// Xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove_id'])) {
    $remove_id = $_GET['remove_id'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header("Location: /webbanhang/Product/cart");
    exit();
}

// Xóa toàn bộ giỏ hàng
if (isset($_GET['clear_cart'])) {
    unset($_SESSION['cart']);
    header("Location: /webbanhang/Product/cart");
    exit();
}

$cart = $_SESSION['cart'] ?? [];
$totalPrice = 0;
?>

<h1>🛒 Giỏ hàng</h1>

<?php if (!empty($cart)): ?>
    <form method="POST" action="">
        <ul class="list-group">
            <?php foreach ($cart as $id => $item): 
                $itemTotal = $item['price'] * $item['quantity'];
                $totalPrice += $itemTotal;
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h2><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <?php if (!empty($item['image'])): ?>
                            <img src="/webbanhang/<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image" style="max-width: 100px;">
                        <?php endif; ?>
                        <p>Giá: <?php echo number_format($item['price']); ?> VND</p>

                        <!-- Input chỉnh sửa số lượng -->
                        <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control w-25">
                        <p><strong>Thành tiền: <?php echo number_format($itemTotal); ?> VND</strong></p>
                    </div>
                    <div>
                        <a href="?remove_id=<?php echo $id; ?>" class="btn btn-danger">❌ Xóa</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <h3 class="mt-3">💰 Tổng tiền: <strong><?php echo number_format($totalPrice); ?> VND</strong></h3>

        <!-- Nút cập nhật giỏ hàng duy nhất -->
        <button type="submit" name="update_cart" class="btn btn-success mt-2">🔄 Cập nhật giỏ hàng</button>
    </form>

    <a href="?clear_cart=1" class="btn btn-warning mt-2">🗑 Xóa toàn bộ giỏ hàng</a>
    <a href="/webbanhang/Product" class="btn btn-secondary mt-2">🔙 Tiếp tục mua sắm</a>
    <a href="/webbanhang/Product/checkout" class="btn btn-primary mt-2">💳 Thanh Toán</a>

<?php else: ?>
    <p>🛍 Giỏ hàng của bạn đang trống.</p>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
