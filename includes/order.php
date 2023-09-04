<?php

    require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/product.php');
    
    $productClass = new Product($_GET['product']);
    $product = $productClass->getInfo();

?>