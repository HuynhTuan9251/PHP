<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController {
    private $db;
    private $categoryModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // Hiển thị danh sách danh mục
    public function index() {
        $categories = $this->categoryModel->getCategories();
        include __DIR__ . '/../views/category/list.php';
    }

    // Thêm danh mục
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            // Kiểm tra dữ liệu đầu vào
            if (empty($name)) {
                die('Lỗi: Tên danh mục không được để trống.');
            }

            // Xử lý để tránh lỗi bảo mật
            $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

            // Gọi Model để thêm danh mục
            if ($this->categoryModel->addCategory($name, $description)) {
                header('Location: /webbanhang/category');
                exit();
            } else {
                die('Lỗi: Không thể thêm danh mục.');
            }
        } else {
            include __DIR__ . '/../views/category/add.php';
        }
    }

    // Sửa danh mục
    public function edit($id = null) {
        if ($id === null || !is_numeric($id)) {
            die('Lỗi: ID danh mục không hợp lệ.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            if (empty($name)) {
                die('Lỗi: Tên danh mục không được để trống.');
            }

            $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

            if ($this->categoryModel->updateCategory($id, $name, $description)) {
                header('Location: /webbanhang/category');
                exit();
            } else {
                die('Lỗi: Không thể cập nhật danh mục.');
            }
        } else {
            $category = $this->categoryModel->getCategoryById($id);
            if (!$category) {
                die('Lỗi: Không tìm thấy danh mục.');
            }
            include __DIR__ . '/../views/category/edit.php';
        }
    }

    // Xóa danh mục
    public function delete($id = null) {
        if ($id === null || !is_numeric($id)) {
            die('Lỗi: ID danh mục không hợp lệ.');
        }

        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: /webbanhang/category');
            exit();
        } else {
            die('Lỗi: Không thể xóa danh mục.');
        }
    }
}
