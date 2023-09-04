<?php 
//Класс с данными для отзыва
class Review
{
    public $rate;
    public $comment;
    public $productID;
    public $id;
    public $conn;

    function __construct($id = null)
    {
        $this->id = $id;
        $this->conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbusername'], $GLOBALS['dbpassword'], $GLOBALS['dbdatabase']);
        if($this->conn->connect_error){
            die('{"success":0,"error":"Ошибка базы данных"}');
        }
        if($id != null){
                $result = $this->conn->query('SELECT rate, comment, productid FROM reviews WHERE id = ' . $id);        
                $review = $result->fetch_assoc();
                $this->rate = $review['rate'];
                $this->comment = $review['comment'];
                $this->productid = $review['productid'];
        }
    }

    function AddToDatabase($rate, $comment, $productID){

        $dateAdded = date("Y-m-d H:i:s");
        $rateSecured = intval($rate);
        $commentSecured = htmlspecialchars($this->conn->real_escape_string($comment));
        $productIDSecured = intval($productID);

            $result = $this->conn->query('SELECT COUNT(*) as n FROM reviews WHERE is_active = 1 AND productid = ' . $productIDSecured . ' and userid = ' . $_SESSION['id']);        
            $numberUserReviews = $result->fetch_assoc();
            if($numberUserReviews['n']==0){
                $result = $this->conn->query("INSERT INTO reviews(rate, productid, userid, comment, date_added, is_active) VALUES 
                    ($rateSecured, $productIDSecured, {$_SESSION['id']}, '$commentSecured', '$dateAdded',1);");        
                //$stmt = $this->conn->prepare("INSERT INTO reviews(rate, productid, userid, comment, date_added, is_active) VALUES (?,?,?,?,?,1);");
                //$stmt->bind_param("iiiss", $rateSecured, $productIDSecured, $_SESSION['id'], $commentSecured, $dateAdded);
                //$result=$stmt->execute();
                if(!$result){
                    $answer = '{"success":0,"error":"Ошибка базы"}';
                }else{
                    $answer = '{"success":1}';
                }
            }else{
                $answer = '{"success":0,"error":"Вы уже оставили отзыв к этому товару"}';
            }    
        

        $log = $dateAdded . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $rateSecured $productIDSecured";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/add_review.log', $log . PHP_EOL, FILE_APPEND);

        return $answer;

    }

    function Edit($rate, $comment, $productID){
        $dateEdited = date("Y-m-d H:i:s");
        $rateSecured = $this->conn->real_escape_string(htmlspecialchars($rate));
        $commentSecured = $this->conn->real_escape_string(htmlspecialchars($comment));
        $productIDSecured = intval($productID);
        
            $stmt = $this->conn->prepare("UPDATE reviews SET rate = ?, comment = ?, date_edited = ? WHERE is_active = 1 AND productid = ? AND userid = ?");
            $stmt->bind_param("issii", $rateSecured, $commentSecured, $dateEdited, $productIDSecured, $_SESSION['id'] );
            $result=$stmt->execute();
            if(!$result){
                $answer = '{"success":0,"error":"Ошибка базы"}';
            }else{
                $answer = '{"success":1}';
            }    
        
        $log = $dateEdited . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $rateSecured $productIDSecured";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/edit_review.log', $log . PHP_EOL, FILE_APPEND);

        return $answer;

    }

    function ChangeActive($status=0,$id=null){
        
        if($id == null)
            $id=$this->id;
        if($id == null){
            $answer = '{"success":0,"error":"Ошибка идентификатора"}';
            return $answer;
        }
        $dateEdited = date("Y-m-d H:i:s");
        $statusSequre = intval($status);

        $sql = "UPDATE reviews SET is_active = $statusSequre WHERE id = $id";
        $result = $this->conn->query($sql);
        if(!$result){
            
            $answer = '{"success":0,"error":"Ошибка базы"}'; 
        }else{
            
            $answer = '{"success":1}';
            //$answer = '{"success":0,"error":"'.$sql.'"}';
        }
        
        $log = $dateEdited . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $this->id";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/delete_review.log', $log . PHP_EOL, FILE_APPEND);

        return $answer;
    }

    function DeleteForever($id=null){
        
        if($id == null)
            $id=$this->id;
        if($id == null){
            $answer = '{"success":0,"error":"Ошибка идентификатора"}';
            return $answer;
        }
        $dateEdited = date("Y-m-d H:i:s");

        $sql = "DELETE FROM reviews WHERE id = $id";
        $result = $this->conn->query($sql);
        if(!$result){
            
            $answer = '{"success":0,"error":"Ошибка базы"}'; 
        }else{
            
            $answer = '{"success":1}';
            //$answer = '{"success":0,"error":"'.$sql.'"}';
        }
        
        $log = $dateEdited . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $this->id";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/delete_review.log', $log . PHP_EOL, FILE_APPEND);

        return $answer;
    }

        
    function getListForProduct($productID){

        $result = $this->conn->query("SELECT * FROM reviews WHERE productid = " . intval($productID) );
        $queryAnswer = [];
        while ($row = $result->fetch_assoc()) {
            $queryAnswer[] = $row;
        }
        
        return $queryAnswer;

    }

    function calcRate(){

        if(empty($this->id))
            return;
    
        $result = $this->conn->query("
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
                            productID = $this->id AND is_active = 1
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

}

