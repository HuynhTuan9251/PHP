<?php
// app/controllers/AccountController.php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/helpers/SessionHelper.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        // Xóa session_start() vì đã được gọi ở nơi khác
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    private function redirectWithMessage($message, $url) {
        $_SESSION['flash_message'] = $message;
        header("Location: $url");
        exit();
    }

    public function register() {
        include_once 'app/views/account/register.php';
    }

    public function login() {
        include_once 'app/views/account/login.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $fullName = trim($_POST['fullname'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $errors = [];

            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập username!";
            }
            if (empty($fullName)) {
                $errors['fullname'] = "Vui lòng nhập họ tên!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập mật khẩu!";
            }
            if ($password !== $confirmPassword) {
                $errors['confirmPass'] = "Mật khẩu và xác nhận không khớp!";
            }

            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $errors['account'] = "Tài khoản này đã có người đăng ký!";
            }

            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $role = 'user';
                $result = $this->accountModel->save($username, $fullName, $password, $role);
                if ($result) {
                    $this->redirectWithMessage("Đăng ký thành công! Vui lòng đăng nhập.", '/webbanhang/account/login');
                } else {
                    $this->redirectWithMessage("Đăng ký thất bại.", '/webbanhang/account/register');
                }
            }
        }
    }

    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $account = $this->accountModel->getAccountByUsername($username);

            if ($account && password_verify($password, $account->password)) {
                $_SESSION['username'] = $account->username;
                $_SESSION['user_role'] = $account->role;
                $this->redirectWithMessage("Đăng nhập thành công!", '/webbanhang/product');
            } else {
                $this->redirectWithMessage("Tài khoản hoặc mật khẩu không đúng.", '/webbanhang/account/login');
            }
        }
    }

    public function logout() {
        session_unset(); // Xóa tất cả biến session
        session_destroy(); // Hủy session
        $this->redirectWithMessage("Bạn đã đăng xuất thành công.", '/webbanhang/account/login');
    }
}