<?php

namespace App\Controllers;
use App\Models\ProductModel;

class ProductController extends Controller
{    
    private $ProductModel;

    public function __construct(){
        parent::__construct();
        $this->ProductModel = new ProductModel();
    }
    
    public function index(){
        $products = $this->ProductModel->getAllProduct();
        echo $this->blade->run("show", ["products" => $products]);
    }

    public function create(){
        $categories = $this->ProductModel->getAllCategory();
        echo $this->blade->run("add", ["categories" => $categories]);
    }

    public function store(){
        // Validate dữ liệu
        $errors = $this->ProductModel->validateProduct($_POST);
        
        if(!empty($errors)){
            $_SESSION['errors'] = $errors;
            $categories = $this->ProductModel->getAllCategory();
            echo $this->blade->run("add", [
                "categories" => $categories,
                "errors" => $errors
            ]);
            return;
        }
        
        $this->ProductModel->addProduct();
        header('Location:'. $_ENV['BASE_URL'] . '/products');
    }

    public function edit($id){
        $product = $this->ProductModel->getProductById($id);
        $categories = $this->ProductModel->getAllCategory();
        echo $this->blade->run("edit", [
            "product" => $product,
            "categories" => $categories
        ]);
    }
    
    public function update($id){
        $errors = $this->ProductModel->validateProduct($_POST);
        
        if(!empty($errors)){
            $_SESSION['errors'] = $errors;
            $product = $this->ProductModel->getProductById($id);
            $categories = $this->ProductModel->getAllCategory();
            echo $this->blade->run("edit", [
                "product" => $product,
                "categories" => $categories,
                "errors" => $errors
            ]);
            return;
        }
        
        $this->ProductModel->updateProduct($id);
        header('Location:'. $_ENV['BASE_URL'] . '/products');
    }

    public function delete($id){
        $this->ProductModel->deleteProduct($id);
        header('Location:'. $_ENV['BASE_URL'] . '/products');
    }
}
