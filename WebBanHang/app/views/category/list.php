<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách danh mục</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-3">Danh sách danh mục</h2>
        <a href="?action=add" class="btn btn-primary mb-3">➕ Thêm danh mục</a>

        <?php if (!empty($categories)): ?>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= htmlspecialchars($category->id, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <a href="?action=edit&id=<?= $category->id ?>" class="btn btn-warning btn-sm">✏ Sửa</a>
                                <a href="?action=delete&id=<?= $category->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">🗑 Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-info">Không có danh mục nào!</p>
        <?php endif; ?>
    </div>
</body>
</html>
