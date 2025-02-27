<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm mới</title>
</head>
<body>
    <h1>Thêm sản phẩm mới</h1>
    <a href="{{$_ENV['BASE_URL']}}/products">Quay lại danh sách</a>
    
    <form action="{{$_ENV['BASE_URL']}}/products/store" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Danh mục:</td>
                <td>
                    <select name="category_id">
                        <option value="">-- Chọn danh mục --</option>
                        <?php
                        foreach ($categories as $category) {
                            echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tên sản phẩm:</td>
                <td><input type="text" name="name" required></td>
            </tr>
            <tr>
                <td>Hình ảnh:</td>
                <td><input type="file" name="img_thumbnail" accept="image/*"></td>
            </tr>
            <tr>
                <td>Mô tả:</td>
                <td><textarea placeholder="Mo ta san pham" name="description" rows="5" cols="40"></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Lưu</button>
                    <button type="reset">Làm mới</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
