<?php

namespace App\Models;

class ProductModel extends Model {
    public function getAllProduct() {
        $stmt = $this->queryBuilder
            ->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->join('p', 'categories', 'c', 'p.category_id = c.id')
            ->orderby('p.id', 'DESC');
        return $stmt->fetchAllAssociative();
    }

    public function getAllCategory() {
        $stmt = $this->queryBuilder
            ->select('*')
            ->from("categories");
        return $stmt->fetchAllAssociative();
    }

    // Validate dữ liệu
    public function validateProduct($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        
        if (empty($data['category_id'])) {
            $errors['category_id'] = 'Vui lòng chọn danh mục sản phẩm';
        }
        
        return $errors;
    }
    
    // Upload ảnh
    public function uploadImage($file) {
        $uploadDir = 'uploads/products/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $uploadDir . $fileName;
        
        move_uploaded_file($file['tmp_name'], $targetFile);
        return $targetFile;
    }
    
    // Thêm sản phẩm
    public function addProduct() {
        $imgPath = '';
        if (isset($_FILES['img_thumbnail']) && $_FILES['img_thumbnail']['error'] == 0) {
            $imgPath = $this->uploadImage($_FILES['img_thumbnail']);
        }
        
        $now = date('Y-m-d H:i:s');
        $stmt = $this->queryBuilder
            ->insert('products')
            ->values([
                'category_id' => ':category_id',
                'name' => ':name',
                'img_thumbnail' => ':img_thumbnail',
                'description' => ':description',
                'created_at' => ':created_at',
                'updated_at' => ':updated_at'
            ])
            ->setParameters([
                'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'img_thumbnail' => $imgPath,
                'description' => $_POST['description'] ?? '',
                'created_at' => $now,
                'updated_at' => $now
            ]);
            
        $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
        return true;
    }
    
    // Lấy sản phẩm theo ID
    public function getProductById($id) {
        $stmt = $this->queryBuilder
            ->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->join('p', 'categories', 'c', 'p.category_id = c.id')
            ->where('p.id = :id')
            ->setParameter('id', $id);
        return $this->connection->fetchAssociative($stmt->getSQL(), $stmt->getParameters());
    }
    
    // Cập nhật sản phẩm
    public function updateProduct($id) {
        $currentProduct = $this->getProductById($id);
        $imgPath = $currentProduct['img_thumbnail'];
        
        if (isset($_FILES['img_thumbnail']) && $_FILES['img_thumbnail']['error'] == 0) {
            // Xóa ảnh cũ
            if (!empty($currentProduct['img_thumbnail']) && file_exists($currentProduct['img_thumbnail'])) {
                unlink($currentProduct['img_thumbnail']);
            }
            
            $imgPath = $this->uploadImage($_FILES['img_thumbnail']);
        }
        
        $stmt = $this->queryBuilder
            ->update('products')
            ->set('category_id', ':category_id')
            ->set('name', ':name')
            ->set('img_thumbnail', ':img_thumbnail')
            ->set('description', ':description')
            ->set('updated_at', ':updated_at')
            ->where('id = :id')
            ->setParameters([
                'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'img_thumbnail' => $imgPath,
                'description' => $_POST['description'] ?? '',
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $id
            ]);
            
        $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
        return true;
    }
    
    // Xóa sản phẩm
    public function deleteProduct($id) {
        // Xóa ảnh
        $product = $this->getProductById($id);
        if ($product && !empty($product['img_thumbnail']) && file_exists($product['img_thumbnail'])) {
            unlink($product['img_thumbnail']);
        }
        
        $stmt = $this->queryBuilder
            ->delete('products')
            ->where('id = :id')
            ->setParameter('id', $id);
            
        $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
        return true;
    }
}
