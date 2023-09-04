<?php

//Проверяем, что данные переданы
if(empty($_POST))
    die('{"success":0}');

//Включаем файл с функциями
require_once('../config.php');
require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/dbconfig.php');

function Loging($login,$action, $result){

    $resultDecoded = json_decode($result,true);
    if(array_key_exists('success',$resultDecoded) and $resultDecoded['success'] == 1){
        $resultToWrite = 'success';
    }else{
        $resultToWrite = $resultDecoded['error'];
    }

    $log = '[' . date('Y-m-d H:i:s') . ']' . ' ' . getIP() . ' "' . $_SERVER['HTTP_USER_AGENT'].'" ' . $login . ' "' . $resultToWrite . '"';

    file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/'.$action.'.log', $log . PHP_EOL, FILE_APPEND);

}

function Auth($login, $password){

    $login = trim($login);
    $passwordMD5 = md5($password);

    if(empty($login) or empty($password))
        return '{"success":0,"error":"Не введён логин или пароль"}';

    //Поиск строки в таблице с этим логином и паролем. 
    //Если строка ровно одна, то добавляем в сессию абракадабру, сохраняем её в куки и передаём от страницы к странице.

    $conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbusername'], $GLOBALS['dbpassword'], $GLOBALS['dbdatabase']);
    if($conn->connect_error){
        return '{"success":0,"error":"Ошибка базы данных"}';
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE login LIKE ? and password LIKE ?");
    $stmt->bind_param('ss', $login, $passwordMD5);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $queryAnswer = [];
    while ($row = $result->fetch_assoc()) {
        $queryAnswer[] = $row;
    }
    //echo '{ "success": '.$queryAnswer['n'].'}';
    $accountsCount = count($queryAnswer);
    
    if($accountsCount===1){

        $_SESSION['login'] = $queryAnswer[0]['login'];
        $_SESSION['rights'] = $queryAnswer[0]['rights'];
        $_SESSION['id'] = $queryAnswer[0]['id'];
    
    /*
    $stmt = $conn->prepare("UPDATE users SET session=? WHERE login LIKE ?");
    $stmt->bind_param("ss", session_id(), $login);
    $result=$stmt->execute();
    if(!$result){
        $answer = '{"success":0,"error":"Ошибка сессии"}';
    }
    */

        return '{ "success": 1 }';
        
    }else{

        return '{"success":0,"error":"Неправильная пара логин-пароль"}';

    }

}

function Registration($login, $password, $password2){

    $login = trim($login);
    $passwordMD5 = md5($password);

    if(strcmp($password,$password2)!=0){
        return '{"success":0,"error":"Пароли не совпадают"}';
    }
    
    if(empty($login) or empty($password))
        return  '{"success":0,"error":"Не введён логин или пароль"}';

    $conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbusername'], $GLOBALS['dbpassword'], $GLOBALS['dbdatabase']);
    if($conn->connect_error){
        return '{"success":0,"error":"Ошибка базы данных"}';
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE login LIKE ?");
    $stmt->bind_param('s', $login);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $queryAnswer = [];
    while ($row = $result->fetch_assoc()) {
        $queryAnswer[] = $row;
    }
    $accountsCount = count($queryAnswer);
        
    if($accountsCount===0){

        $stmt2 = $conn->prepare("INSERT INTO users(login, password, date_registered) VALUES (?,?,?)");
        $stmt2->bind_param("sss", $login, $passwordMD5, date("Y-m-d H:i:s"));
        $result=$stmt2->execute();
        if(!$result){
            return '{"success":0,"error":"Ошибка сессии"}';
        }
        
        return '{ "success": 1 }';

    }else{
        return '{"success":0,"error":"Такой пользователь уже существует"}';
    }

}

//Декодируем данные
$inputData = file_get_contents('php://input');
$inputDataDecoded = !is_null(json_decode($inputData,true)) ? json_decode($inputData,true) : array();
if(empty($inputDataDecoded) or !array_key_exists('action',$inputDataDecoded))
    $answer = '{"success":0,"error":"Неправильные данные"}';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!stripos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']))
    $answer = '{"success":0,"error":"Вы пришли откуда-то не оттуда"}';

if($inputDataDecoded['action']=='auth'){
    $result = Auth($inputDataDecoded['login'],$inputDataDecoded['password']);
    echo $result;
    Loging($login,'auth',$result);
}elseif($inputDataDecoded['action']=='registration'){
    $result = Registration($inputDataDecoded['login'],$inputDataDecoded['password'],$inputDataDecoded['password2']);
    echo $result;
    Loging($login,'registration',$result);
}
