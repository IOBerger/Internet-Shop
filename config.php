<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$siteFolder = '/Task1';
$template = 'test';
$titleJoin = ' - ';
$siteTitle = 'Замечательные товары';

$menu=[
    'main' => [
        'title' => 'Главная',
        'rights' => [-1,0,1],
        'align' => 'left'
        //-1 -- неавторизованные
        //0 -- обычные пользователи
        //1 -- администраторы
    ],
    'list' => [
        'title' => 'Список товаров',
        'rights' => [-1,0,1],
        'align' => 'left'
    ],
    'product_card' => [
        'title' => 'Добавить товар',
        'rights' => [1],
        'align' => 'left',
    ],
    'order' => [
        'title' => 'Заказать товар',
        'rights' => [0,1],
        'align' => ''
    ],
    'review' => [
        'title' => 'Отзывы',
        'rights' => [0,1],
        'align' => 'left'
    ],
    'auth' => [
        'title' => 'Вход',
        'rights' => [-1],
        'align' => 'right'
    ],
    'registration' => [
        'title' => 'Регистрация',
        'rights' => [-1],
        'align' => 'right'
    ],
    '404' => [
        'title' => 'Ошибка 404',
        'rights' => [-1,0,1],
        'align' => ''
    ],
    'detail' => [
        'title' => 'Информация детально',
        'rights' => [-1,0,1],
        'align' => ''
    ],
    'category_manage' => [
        'title' => 'Добавить категорию',
        'rights' => [1],
        'align' => ''
    ],
    'search' => [
        'title' => 'Поиск',
        'rights' => [-1,0,1],
        'align' => ''
    ],    
    
];



/*
function ReviewExists($host, $username, $password, $database){

    $conn = new mysqli($host, $username, $password, $database);
    if($conn->connect_error){
        die('Ошибка базы данных');
    }

    $result = $conn->query('SELECT COUNT(*) FROM reviews WHERE userid = '.$_SESSION['id']);
    //$numberItems = $result->fetch_assoc();
    $row = $result->fetch_row();
    $count = $row[0];

    return $count;

}
*/


function isAuthed(){

    require($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/dbconfig.php');

    if(session_status() != PHP_SESSION_NONE and $_SESSION['id']){
        return true;
    }else{
        return false;
    }

}




//Функция, подсчитывающая рейтинг
/*
function calcRate($id){

    require($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/dbconfig.php');

    $rateSum = 0;
    $rateNumber = 0;

    $conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbdatabase);
    if($conn->connect_error){
        return;
    }
    
    if(empty($id))
        return;

    $result = $conn->query("
        SELECT 
            AVG(a.rate) 
        FROM ( 
            SELECT 
                reviews.rate
               
            FROM 
                reviews 
            WHERE 
                id IN ( 
                    SELECT 
                        MAX(id) 
                    FROM 
                        reviews 
                    WHERE 
                        productID = $id AND is_active = 1
                    GROUP BY 
                        userid 
                ) 
            ) as a
        ;
        ");

    if(!$result)
        return;

    $row = $result->fetch_row();
    $rate = $row[0];

    return $rate;
}
*/

//Функция со список всех товаров
function productsArray($path){
    $result = [];
    if (($fp = fopen($path, "r")) !== false) {
        while (($data = fgetcsv($fp, 0, ";")) !== false) {
            $result[] = $data;
        }
        fclose($fp);
    }
    return $result;
}

function getProduct($id){
 
    require($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/dbconfig.php');

    $conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbdatabase);
    if($conn->connect_error){
        return [];
    }
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("d", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $queryAnswer = $result->fetch_assoc();

    //print_r($queryAnswer);
    
    return $queryAnswer;

    /*

    if (($fp = fopen($path, "r")) !== false) {
        while (($data = fgetcsv($fp, 0, ";")) !== false){
                if($data[0]==$id)
                    break;
        }
        fclose($fp);
    }
    return $data;

    */

}

function getIP(){
    return $_SERVER['REMOTE_ADDR'];
}