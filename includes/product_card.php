<?php 
    
//    $productInfo = getProduct($_GET['product']);
    require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/product.php');
    $productClass = new Product(intval($_GET['product']));
    $productInfo = $productClass->getInfo();

    $category = intval($_GET['category']);
    $categoryClass = new Category(intval($_GET['category']));
    $categories = $categoryClass->getList();

?>