<?php
    
    $_SESSION['sort'] = preg_replace('/[^a-zA-Z]/ui', '',$_GET['sort']);
    $_SESSION['order'] = preg_replace('/[^a-zA-Z]/ui', '',$_GET['order']);
    //if(!empty($_GET['q']))$_SESSION['search'] = htmlspecialchars($_GET['search']);
    

    $from = intval($_GET['from']);
    if($from==0){
        $from=1;
    }

    require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/product.php');
    $productClass = new Product();
    $products = $productClass->getList($_GET['category'],$from,$_GET['sort'],$_GET['order'],$_GET['q']);

    $numberPages = intval($productClass->countProducts($_GET['category'],$_GET['q'])/9)+1;
    
    $rate = [];
    require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/review.php');
    foreach($products as $product){
        $reviewClass = new Review($product['id']);
        $rateNumber = round($reviewClass->calcRate(),2);
        $rate[$product['id']] = $rateNumber > 0 ? "<b>$rateNumber</b>" : 'Никто не проголосовал';
    }
    
    $numberResults = $productClass->countProducts($_GET['category'],$_GET['q']);
    if($numberResults<=9)
        $removeNextButton = 1;

    $category = intval($_GET['category']);
    $categoryClass = new Category(intval($_GET['category']));
    $categoryTitle = !empty($categoryClass->title) ? $categoryClass->title : 'Полный список';

    $categoryClass = new Category();
    $categories = $categoryClass->getMenuList($_GET['q']);
    $categoriesMain = $categories[0];
    $categoriesChild = $categories[1];

?>
