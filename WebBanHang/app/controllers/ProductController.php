<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php'); // Thêm SessionHelper nếu chưa có

class ProductController {
    private $productModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    // Hàm kiểm tra quyền admin
    private function restrictToAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['flash_message'] = "Bạn không có quyền thực hiện hành động này.";
            header('Location: /webbanhang/Product');
            exit();
        }
    }

    // Upload banner (không cần kiểm tra admin vì không liên quan đến sản phẩm)
    public function uploadBanner() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['banner'])) {
            $target_dir = "public/images/";
            $target_file = $target_dir . basename($_FILES["banner"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["banner"]["tmp_name"]);
            if ($check === false) {
                echo "File không phải là hình ảnh.";
                return;
            }

            if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                echo "Chỉ hỗ trợ các định dạng JPG, JPEG, PNG & GIF.";
                return;
            }

            if (move_uploaded_file($_FILES["banner"]["tmp_name"], $target_file)) {
                echo "Banner đã được cập nhật.";
                header("Location: /webbanhang/Product");
            } else {
                echo "Có lỗi xảy ra khi tải lên hình ảnh.";
            }
        }
    }

    // Hiển thị danh sách sản phẩm (không cần kiểm tra quyền)
    public function index() {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    // Hiển thị chi tiết sản phẩm (không cần kiểm tra quyền)
    public function show($id) {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    // Thêm sản phẩm (chỉ admin)
    public function add() {
        $this->restrictToAdmin();
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save() {
        $this->restrictToAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                $_SESSION['flash_message'] = "Thêm sản phẩm thành công!";
                header('Location: /webbanhang/Product');
            }
        }
    }

    // Sửa sản phẩm (chỉ admin)
    public function edit($id) {
        $this->restrictToAdmin();
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update() {
        $this->restrictToAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }
            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if ($edit) {
                $_SESSION['flash_message'] = "Cập nhật sản phẩm thành công!";
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    // Xóa sản phẩm (chỉ admin)
    public function delete($id) {
        $this->restrictToAdmin();
        if ($this->productModel->deleteProduct($id)) {
            $_SESSION['flash_message'] = "Xóa sản phẩm thành công!";
            header('Location: /webbanhang/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    // Upload ảnh sản phẩm
    private function uploadImage($file) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    // Các phương thức khác không liên quan đến thêm/xóa/sửa (không cần kiểm tra quyền)
    public function addToCart($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }
        header('Location: /webbanhang/Product/cart');
    }

    public function cart() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    public function checkout() {
        include 'app/views/product/checkout.php';
    }
}