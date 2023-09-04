<?php

//Проверяем, что данные переданы
if(empty($_POST))
    die('{"success":0}');

if(!stripos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']))
    die('{"success":0,"error":"Вы пришли откуда-то не оттуда"}');

//Декодируем данные
$inputData = file_get_contents('php://input');
$inputDataDecoded = !is_null(json_decode($inputData,true)) ? json_decode($inputData,true) : array();
if(empty($inputDataDecoded) or !array_key_exists('action',$inputDataDecoded))
    die('{"success":0,"error":"Неправильные данные"}');

//Включаем файл с функциями
require_once('../config.php');

if(isAuthed()){
    session_destroy();

    echo '{"success":1}';

}else{
    die('{"success":0,"error":"Вы не авторизованы"}');
}




