<?php if (!empty($product)) : ?>
    <div class="container mt-4">
        <div class="row">
            <!-- Cột hình ảnh -->
            <div class="col-md-5">
                <?php if (!empty($product->image)) : ?>
                    <img src="/webbanhang/<?= htmlspecialchars($product->image) ?>" alt="Ảnh sản phẩm" class="img-fluid rounded shadow">
                <?php else : ?>
                    <p class="text-muted">📷 Không có ảnh sản phẩm.</p>
                <?php endif; ?>
            </div>

            <!-- Cột thông tin sản phẩm -->
            <div class="col-md-7">
                <h1 class="text-primary"><?= htmlspecialchars($product->name) ?></h1>
                <p><strong>Danh mục:</strong> <?= htmlspecialchars($product->category_name ?? 'Không xác định') ?></p>
                <p><strong>Thương hiệu:</strong> <?= htmlspecialchars($product->brand ?? 'Chưa có') ?></p>
                <p><strong>Tình trạng:</strong> 
                    <?= (!isset($product->stock) || $product->stock > 0) 
                        ? '<span class="text-success">✔ Còn hàng</span>' 
                        : '<span class="text-danger">❌ Hết hàng</span>'; ?>
                </p>
                <p><strong>Mô tả:</strong> <?= !empty($product->description) ? nl2br(htmlspecialchars($product->description)) : '<em>Chưa có mô tả.</em>' ?></p>
                <p><strong>Giá:</strong> <span class="text-danger"><?= number_format($product->price ?? 0, 0, ',', '.') ?> VNĐ</span></p>

                <!-- Đánh giá sao -->
                <p><strong>Đánh giá:</strong> 
                    <?php 
                        $rating = $product->rating ?? 0;
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<span style="color: '.($i <= $rating ? '#ffc107' : '#ccc').'">★</span>';
                        }
                    ?>
                    (<?= $product->rating_count ?? 0 ?> đánh giá)
                </p>

                <!-- Nút Thêm vào giỏ hàng -->
                <form action="/webbanhang/Product/addToCart/<?= $product->id ?>" method="POST">
                    <?php if (!isset($product->stock) || $product->stock > 0) : ?>
                        <button type="submit" class="btn btn-success">🛒 Thêm vào giỏ hàng</button>
                    <?php else : ?>
                        <button type="button" class="btn btn-secondary" disabled>🚫 Hết hàng</button>
                    <?php endif; ?>
                    <a href="/webbanhang/Product" class="btn btn-secondary">⬅️ Quay lại</a>
                </form>
            </div>
        </div>
    </div>
<?php else : ?>
    <p class="alert alert-warning text-center">⚠️ Không tìm thấy sản phẩm.</p>
<?php endif; ?>
