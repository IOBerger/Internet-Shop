<?php

require_once('../config.php');
require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/dbconfig.php');

require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/product.php');

$inputData = file_get_contents('php://input');
$inputDataDecoded = !is_null(json_decode($inputData,true)) ? json_decode($inputData,true) : array();
if(empty($inputDataDecoded)){
    die('{"success":0,"error":"Неправильные данные"}');
}

$action = $inputDataDecoded['action'];

$productClass = new Product();

if($action == 'count_list'){
    echo '{"success":"1", "result": ' . $productClass->countProducts() . '}';
}elseif($action == 'next_list'){
    $page = intval($inputDataDecoded['page']);
    $products = $productClass->getList($_GET['category'],$page,$_SESSION['sort'],$_SESSION['order'],$_SESSION['search']);
    require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/review.php');
    foreach($products as $product){
        echo "<div class='products-item ";
        if(!$product['is_active']) 
            echo 'products-item-deleted';
        echo "'><div class='products-item-picture'></div>";
        echo "<div class='products-item-info'><p><strong><a class='products-item-info-link' href='?page=detail&product=".$product['id']."'>".$product['name']."</a></strong></p>";
        echo "<p>Цвет: ".$product['color']."</p>";
        echo "<p>Размер: ".$product['size']."</p>";
        echo "<p>Цена: <b>".$product['price']."&#36;</b></p>";
        $reviewClass = new Review($product['id']);
        $rateNumber = round($reviewClass->calcRate(),2);
        $rate = $rateNumber > 0 ? "<b>$rateNumber</b>" : 'Никто не проголосовал';
        echo "<p>Рейтинг: $rate</p>";
        if(isAuthed()){
            echo '<button class="products-manage-button" onclick="document.location=\'?page=order&product='.$product['id'].'\'">Заказать</button> ';
            echo '<button class="products-manage-button" onclick="document.location=\'?page=review&product='.$product['id'].'\'">Отзыв</button><br>';
        }
        if($_SESSION['rights']==1){
            echo '<button class="products-manage-button" onclick="document.location=\'?page=product_card&action=edit&product='.$product['id'].'\'">Изменить</button> ';
            if($product['is_active']==1){
                echo '<button class="products-manage-button product-delete-button" data-id="'.$product['id'].'">Удалить</button>';
            }else{            
                echo '<button class="products-manage-button product-ressurect-button" data-id="'.$product['id'].'">Восстановить</button>';
            }
            echo '<div>
                <button class="products-manage-button product-delete-forever-button" data-id="'.$product['id'].'">Удалить НАВЕЧНО</button>
                </div>';  
        }
        echo '</div></div>';
    }
}