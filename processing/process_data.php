<?php

//Проверяем, что данные переданы
if(empty($_POST))
    die('{"success":0}');

//Декодируем данные
$inputData = file_get_contents('php://input');
$inputDataDecoded = !is_null(json_decode($inputData,true)) ? json_decode($inputData,true) : array();
if(empty($inputDataDecoded) or !array_key_exists('action',$inputDataDecoded)){
    die('{"success":0,"error":"Неправильные данные"}');
}

//Включаем файл с функциями
require_once('../config.php');
require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/dbconfig.php');

if(!stripos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']))
    die('{"success":0,"error":"Вы пришли откуда-то не оттуда"}');

if (session_status() == PHP_SESSION_NONE) {
        session_start();
}

require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/order.php');
require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/product.php');
require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/review.php');

//Проверяем, какое действие мы собираемся совершить
if($inputDataDecoded['action']=='add_review'){

//    $review = new Review($inputDataDecoded['rate'],$_SESSION['login'],$inputDataDecoded['comment'],$inputDataDecoded['product']);

    $review = new Review();
    
    echo $review->AddToDatabase($inputDataDecoded['rate'],$inputDataDecoded['comment'],$inputDataDecoded['product']);

}elseif($inputDataDecoded['action']=='edit_review'){

    $review = new Review(intval($inputDataDecoded['id']));
    
    echo $review->Edit($inputDataDecoded['rate'],$inputDataDecoded['comment'],$inputDataDecoded['product']);

}elseif($inputDataDecoded['action']=='delete_review'){

    $review = new Review();
    //echo intval($inputDataDecoded['id']);
    echo $review->ChangeActive(intval($inputDataDecoded['status']),intval($inputDataDecoded['id']));

}elseif($inputDataDecoded['action']=='delete_forever_review'){

    $review = new Review(intval($inputDataDecoded['id']));
    //echo intval($inputDataDecoded['id']);
    echo $review->DeleteForever();

}elseif($inputDataDecoded['action']=='add_order'){
    
    //Создаём объект заказа
    $order = new Order();

    echo $order->AddToDatabase($inputDataDecoded['product'],$inputDataDecoded['comment']);

}elseif($inputDataDecoded['action']=='add_product'){
    
    if($_SESSION['rights']!=1)
        echo '{"success":0,"error": "Недостаточно прав"}';
    else{
    
        //Создаём объект продукта
        $product = new Product();  
        echo $product->AddToDatabase($inputDataDecoded['product'],$inputDataDecoded['color'],$inputDataDecoded['size'],$inputDataDecoded['price'],$inputDataDecoded['annotation'],$inputDataDecoded['category'],$inputDataDecoded['product_before'],$inputDataDecoded['product_after']);

    }

}elseif($inputDataDecoded['action']=='edit_product'){
    
    if($_SESSION['rights']!=1)
        echo '{"success":0,"error": "Недостаточно прав"}';
    else{

        //Создаём объект продукта
        $product = new Product($inputDataDecoded['id']);
        //Вывод результата -- успех
        echo $product->EditInDatabase($inputDataDecoded['product'],$inputDataDecoded['color'],$inputDataDecoded['size'],$inputDataDecoded['price'],$inputDataDecoded['annotation'],$inputDataDecoded['category'],$inputDataDecoded['product_before'],$inputDataDecoded['product_after']);

    }

}elseif($inputDataDecoded['action']=='activation_change_product'){
 
    if($_SESSION['rights']!=1)
       echo '{"success":0,"error": "Недостаточно прав"}';
    else{
        $product = new Product($inputDataDecoded['id']);
        //Вывод результата -- успех
        echo $product->ActivationChange($inputDataDecoded['status']);
    }

}elseif($inputDataDecoded['action']=='delete_forever'){
 
    if($_SESSION['rights']!=1)
       echo '{"success":0,"error": "Недостаточно прав"}';
    else{
        $product = new Product($inputDataDecoded['id']);
        //Вывод результата -- успех
        echo $product->DeleteForever();
    }

}
