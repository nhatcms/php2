<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
</head>
<body>
    <div class="container">
        <h1>Sửa sản phẩm</h1>
        
        <?php if(isset($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach($errors as $field => $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo $_ENV['BASE_URL']; ?>/products/<?php echo $product['id']; ?>/update/" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Danh mục</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo $product['description']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="img_thumbnail">Ảnh sản phẩm</label>
                    <div>
                        <img src="<?php echo $_ENV['BASE_URL']; ?>/<?php echo $product['img_thumbnail']; ?>" alt="<?php echo $product['name']; ?>" style="max-width: 200px; margin-bottom: 10px;">
                    </div>
                <input type="file" class="form-control" id="img_thumbnail" name="img_thumbnail">
                <small class="form-text text-muted">Để trống nếu không muốn thay đổi ảnh</small>
            </div>
            
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo $_ENV['BASE_URL']; ?>/products" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>
