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

require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/category.php');

//Проверяем, какое действие мы собираемся совершить
if($inputDataDecoded['action']=='add'){
    
    if($_SESSION['rights']!=1)
        echo '{"success":0,"error": "Недостаточно прав"}';
    else{
        $category = new Category();
        echo $category->AddToDatabase($inputDataDecoded['title'],$inputDataDecoded['description'],$inputDataDecoded['parent'],$inputDataDecoded['sort']);
    }



}elseif($inputDataDecoded['action']=='edit'){
    
    if($_SESSION['rights']!=1)
        echo '{"success":0,"error": "Недостаточно прав"}';
    else{
        $category = new Category($inputDataDecoded['id']);
        echo $category->EditInDatabase($inputDataDecoded['title'],$inputDataDecoded['description'],$inputDataDecoded['parent'],$inputDataDecoded['sort']);
    }

}elseif($inputDataDecoded['action']=='delete'){
    
    if($_SESSION['rights']!=1)
        echo '{"success":0,"error": "Недостаточно прав"}';
    else{
        $category = new Category($inputDataDecoded['id']);
        echo $category->ChangeActive($inputDataDecoded['status']);
    }

}elseif($inputDataDecoded['action']=='delete_forever'){
    
    if($_SESSION['rights']!=1)
        echo '{"success":0,"error": "Недостаточно прав"}';
    else{
        $category = new Category($inputDataDecoded['id']);
        echo $category->DeleteForever();
    }

}


