<?php 

//Класс с данными для заказа
class Order
{

    private $conn;

    function __construct()
    {
        $this->conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbusername'], $GLOBALS['dbpassword'], $GLOBALS['dbdatabase']);
        if($this->conn->connect_error){
            die('{"success":0,"error":"Ошибка базы данных"}');
        }
    }

    function AddToDatabase($productID,$comment){

        $dateAdded = date("Y-m-d H:i:s");
        $productIDSecure = intval($productID);
        $commentSecure =  $this->conn->real_escape_string($comment);
        $sql = "INSERT INTO orders(productid, userid, comment, date_added) VALUES ($productIDSecure,{$_SESSION['id']},'$commentSecure','".$dateAdded."')";
        $result = $this->conn->query($sql);
        if(!$result){
            $answer = '{"success":0,"error":"Ошибка базы"}';
        }else{
            $answer = '{"success":1}';
        }
        $log = $dateAdded . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $productIDSecured";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/add_order.log', $log . PHP_EOL, FILE_APPEND);

        //Вывод результата -- успех
        //echo '{ "success": 1}';            
        
        return $answer;
        
    }

}



