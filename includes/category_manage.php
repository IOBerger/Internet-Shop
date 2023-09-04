<?php

    require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/category.php');
    $categoryClass = new Category($_GET['category']);
    $categoryInfo = $categoryClass->getInfo();

    $categoryList = $categoryClass->getList($_GET['category']);


