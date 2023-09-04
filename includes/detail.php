<?php

require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/product.php');
$productClass = new Product($_GET['product']);
$product = $productClass->getInfo();

$product['annotation']=str_replace(array("\r\n", "\r", "\n", '\\n'), '<br>', $product['annotation']);


require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/review.php');
$reviewClass = new Review();
$reviews = $reviewClass->getListForProduct($product['id']);


