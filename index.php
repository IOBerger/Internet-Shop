<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('config.php');
require($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/dbconfig.php');

//title
//$page= preg_replace("[^A-Za-z]", "", $_GET['page'] );
$page=$_GET['page'];
if(empty($page))
    $page = 'main';
if(!array_key_exists($page,$menu)){
    $page = '404';
    header("HTTP/1.1 404 Not Found");
}
if($page=='detail'){
    require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/product.php');
    //$productInfo = new Product(intval($_GET['product']));
    $productClass = new Product(intval($_GET['product']));
    $product = $productClass->getInfo();
    if(empty($product['name'])){
        $page = '404';
        header("HTTP/1.1 404 Not Found");
    }else{
        $productName = $product['name'].$titleJoin;
    }
}    
$title = $productName.$menu[$page]['title'].$titleJoin.$siteTitle;
if(intval($_GET['q'])>0){
    header('Location: ?page=detail&product='.intval($_POST['q']));
}

require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/category.php');
$categoryClass = new Category();
$categories = $categoryClass->getMenuList();
//$categories = $categoryClass->getSortedList();
$categoriesMain = $categories[0];
$categoriesChild = $categories[1];

$rights = $_SESSION['rights'];
if($rights === null){
    $rights = -1;
}

require_once($_SERVER["DOCUMENT_ROOT"] . $siteFolder . '/templates/' . $template . '/template.php');

?>