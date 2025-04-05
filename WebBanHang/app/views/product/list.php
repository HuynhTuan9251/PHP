<?php 
include 'app/views/shares/header.php'; 
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Thế Giới Di Động</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Trang chủ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Sản phẩm</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Khuyến mãi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Giới thiệu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Liên hệ</a>
        </li>

        <!-- Luôn hiển thị Đăng nhập và Đăng ký -->
        <li class="nav-item">
          <a class="nav-link btn btn-outline-light px-3 py-1" href="/webbanhang/account/login">Đăng nhập</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-primary px-3 py-1 text-white" href="/webbanhang/account/register">Đăng ký</a>
        </li>

        <!-- Hiển thị tên người dùng và Đăng xuất nếu đã đăng nhập -->
        <?php if (isset($_SESSION['username'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="#">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-danger text-white px-3 py-1" href="/webbanhang/account/logout">Đăng xuất</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-4">
    <h1 class="text-center mb-4">Danh sách sản phẩm</h1>

    <!-- Chỉ hiển thị nút Thêm sản phẩm nếu là admin -->
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="/webbanhang/Product/add" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Thêm sản phẩm mới</a>
    <?php endif; ?>

    <!-- Hiển thị thông báo flash -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-info">
            <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-light">
                    <?php if ($product->image): ?>
                        <img src="/webbanhang/<?php echo htmlspecialchars($product->image); ?>" class="card-img-top" alt="Product Image" style="height: 250px; object-fit: cover;">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/250x250.png?text=No+Image" class="card-img-top" alt="No Image Available">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        <p class="card-text"><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Giá: <?php echo number_format($product->price, 0, ',', '.') ?> VND</strong></p>
                        <p class="text-muted">Danh mục: <?php echo htmlspecialchars($product->category_name ?? 'Không xác định', ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="d-flex justify-content-between">
                            <!-- Chỉ hiển thị nút Sửa và Xóa nếu là admin -->
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Sửa</a>
                                <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');"><i class="fas fa-trash-alt"></i> Xóa</a>
                            <?php endif; ?>
                            <!-- Nút Thêm vào giỏ hàng luôn hiển thị -->
                            <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-primary btn-sm"><i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<?php include 'app/views/shares/footer.php'; ?>

<!-- Font Awesome & Bootstrap JS -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>