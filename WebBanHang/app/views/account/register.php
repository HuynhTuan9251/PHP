<!-- app/views/account/register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            color: #495057;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-register {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
        }
        .btn-register:hover {
            background-color: #218838;
        }
        .error-text {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Đăng ký tài khoản</h2>

        <!-- Hiển thị thông báo flash -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-info">
                <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/webbanhang/account/save">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                <?php if (isset($errors['username'])): ?>
                    <p class="error-text"><?php echo $errors['username']; ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Họ tên:</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo isset($fullName) ? htmlspecialchars($fullName) : ''; ?>" required>
                <?php if (isset($errors['fullname'])): ?>
                    <p class="error-text"><?php echo $errors['fullname']; ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Mật khẩu:</label>
                <input type="password" name="password" class="form-control" required>
                <?php if (isset($errors['password'])): ?>
                    <p class="error-text"><?php echo $errors['password']; ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Xác nhận mật khẩu:</label>
                <input type="password" name="confirmpassword" class="form-control" required>
                <?php if (isset($errors['confirmPass'])): ?>
                    <p class="error-text"><?php echo $errors['confirmPass']; ?></p>
                <?php endif; ?>
            </div>

            <?php if (isset($errors['account'])): ?>
                <p class="error-text"><?php echo $errors['account']; ?></p>
            <?php endif; ?>

            <button type="submit" class="btn btn-success btn-register">Đăng ký</button>
        </form>

        <div class="login-link">
            <p>Đã có tài khoản? <a href="/webbanhang/account/login">Đăng nhập</a></p>
        </div>
    </div>

    <!-- Thêm Bootstrap JS (tùy chọn, nếu cần) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>