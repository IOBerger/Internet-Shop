<?php 

class Product
{
    public $name;
    public $sort;
    public $status;
    public $color;
    public $size;
    public $price;
    public $id;
    public $annotation;
    public $categoryID;
    public $categoryTitle;
    public $tablename = 'products';
    private $conn;

    function __construct($id = null)
    {
            $this->conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbusername'], $GLOBALS['dbpassword'], $GLOBALS['dbdatabase']);
            if($this->conn->connect_error){
                die('{"success":0,"error":"Ошибка базы данных"}');
            }else{
                if(!empty($id)){
                    $idInt = intval($id);
                    $this->id = $idInt;
                    $sql = 'SELECT products.name AS name, products.sort AS sort, products.is_active AS status, products.color AS color, products.size AS size, products.price AS price, products.annotation AS annotation, 
                            products.category AS category, product_categories.title AS categoryTitle FROM '.$this->tablename.' 
                        LEFT JOIN product_categories ON product_categories.id = products.category 
                        WHERE products.id = ' . $this->id;
                    ///echo $sql;
                    $result = $this->conn->query($sql);        
                    $data = $result->fetch_assoc();
                    $this->name = $data['name'];
                    $this->sort = $data['sort'];
                    $this->status = $data['status'];
                    $this->color = $data['color'];
                    $this->size = $data['size'];
                    $this->price = $data['price'];
                    $this->annotation = $data['annotation'];
                    $this->category = $data['category'];
                    $this->categoryTitle = $data['categoryTitle'];
                }
            }
    }
/*
    function getProductName(){
        return $this->name;
    }
*/

    function calcSort($before, $after){
            
            $beforeSequre = intval($before);
            $afterSequre = intval($after);

           //Запрос
           $sql = "SELECT sort FROM ".$this->tablename." WHERE id = $beforeSequre OR id = $afterSequre";
           $result = $this->conn->query($sql);
           $between = [];
           while ($row = $result->fetch_assoc()) {
               $between[] = $row;
           }

           //print_r($between);
           
           if(!empty($before) and !empty($after)){
               if(count($between)!=2){
                    return null; 
               }
               $sort = ($between[0]['sort']+$between[1]['sort'])/2;
               
           }elseif(empty($before) and !empty($after)){
               if(count($between)!=1){
                    return null; 
            }
               $sort = $between[0]['sort']+100;
           }elseif(empty($after) and !empty($before)){
               if(count($between)!=1){
                    return null; 
            }
               $sort = $between[0]['sort']-100;
           }else{
               $result = $this->conn->query("
                   SELECT MIN(sort) AS min_sort, COUNT(*) AS count FROM ".$this->tablename.";
               ");
               $min=$result->fetch_row();
               //print_r($min);
               $sort = $min[1] > 0 ? $min[0]-10 : 100;
               
           }   
           
           return $sort;
           
    }

    function AddToDatabase($name, $color, $size, $price, $annotation, $categoryID, $before, $after){

            //echo "$name, $color, $size, $price";
            $nameSequre = $this->conn->real_escape_string(htmlspecialchars($name));
            $colorSequre = $this->conn->real_escape_string(htmlspecialchars($color));
            $sizeSequre = $this->conn->real_escape_string(htmlspecialchars($size));
            $annotationSequre = $this->conn->real_escape_string(htmlspecialchars($annotation));
            $priceSequre = floatval($price);
            $categoryIDSequre = intval($categoryID);
 

            $sort = $this->calcSort($before, $after);
            if($sort == null){
                return '{"success":0,"error":"Неправильный индекс сортровки"}';
            }
 
            
            $stmt = $this->conn->prepare("INSERT INTO ".$this->tablename." (name, sort, color, size, price, date_added, annotation, category) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sdssdsss", $nameSequre, $sort, $colorSequre, $sizeSequre, $priceSequre, date("Y-m-d H:i:s"), $annotationSequre, $categoryIDSequre);
            $result=$stmt->execute();
            if(!$result){
                $answer = '{"success":0,"error":"Ошибка базы"}';
            }else{
                $answer = '{"success":1}';
            }
        

        $result = $this->conn->query("
            SELECT MAX(id) AS id FROM ".$this->tablename.";
        ");

        $lastID = ($result->fetch_row())['id'];
        
        $log = date('Y-m-d H:i:s') . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $lastID '$nameSequre' $categoryIDSequre '$colorSequre' $sizeSequre $priceSequre";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/add_product.log', $log . PHP_EOL, FILE_APPEND);

        //Вывод результата -- успех
        //echo '{ "success": 1}';
        

        return $answer;

    }

    function EditInDatabase($name, $color, $size, $price, $annotation, $categoryID, $before, $after){

        //Изменение товара

        $dateEdited = date('Y-m-d H:i:s');

        $nameSequre = $this->conn->real_escape_string(htmlspecialchars($name));
        $colorSequre = $this->conn->real_escape_string(htmlspecialchars($color));
        $sizeSequre = $this->conn->real_escape_string(htmlspecialchars($size));
        $annotationSequre = $this->conn->real_escape_string(htmlspecialchars($annotation));
        $priceSequre = floatval($price);
        $categoryIDSequre = intval($categoryID);

        $sort = $this->calcSort($before, $after);
        if($sort == null){
            return '{"success":0,"error":"Неправильный индекс сортровки"}';
        }


        $stmt = $this->conn->prepare("UPDATE " . $this->tablename . " SET sort = ?, name = ?, color = ?, size = ?, price = ?, date_edited = ?, annotation = ?, category = ? WHERE id = ?");
        $stmt->bind_param("dsssdssii", $sort, $nameSequre, $colorSequre, $sizeSequre, $priceSequre, $dateEdited, $annotationSequre, $categoryIDSequre, $this->id);
        $result=$stmt->execute();
        if(!$result){
            $answer = '{"success":0,"error":"Ошибка базы"}';
        }else{
            $answer = '{"success":1}';
        }

        $log = $dateEdited . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $this->id $categoryIDSequre '$nameSequre' '$colorSequre' $sizeSequre $priceSequre";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/edit_product.log', $log . PHP_EOL, FILE_APPEND);

        return $answer;

    }

    function ActivationChange($status){

        $dateDeleted = date("Y-m-d H:i:s");
    
        $stmt = $this->conn->prepare("UPDATE ".$this->tablename." SET date_deleted = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("sii", $dateDeleted, $status, $this->id);
        $result=$stmt->execute();
        if(!$result){
            $answer = '{"success":0,"error":"Ошибка базы данных"}';
        }else{
            $answer = '{"success":1}';
        }
    
        $log = $dateDeleted . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} ".intval($status)." $this->id";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/delete_product.log', $log . PHP_EOL, FILE_APPEND);
    

        return $answer;
    
    }

    function DeleteForever(){

        $dateDeleted = date("Y-m-d H:i:s");
    
        $stmt = $this->conn->prepare("DELETE FROM ".$this->tablename." WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $result=$stmt->execute();
        if(!$result){
            $answer = '{"success":0,"error":"Ошибка базы данных"}';
        }else{
            $answer = '{"success":1}';
        }
    
        $log = $dateDeleted . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $this->id";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/delete_forever_product.log', $log . PHP_EOL, FILE_APPEND);
    

        return $answer;
    
    }

    function getList($category = null, $page = 1, $sort = 'sort', $order = 'asc', $search = null){
        
        $searchSequre = $this->conn->real_escape_string(htmlspecialchars(trim($search)));

        $listSort = ['sort', 'rate', 'name', 'price'];
        $listOrder = ['asc','desc'];
        if(!in_array($sort, $listSort)){
            $sort = 'sort';
        }
        if(!in_array($order,$listOrder)){
            $order = 'asc';
        }
        $sortSequred = $sort; 
        $orderSequred = $order; 
        
        if($page == -1){
            $limit = '';
        }elseif(!empty($page)){
            $firstIndex = empty($page) ? 1 : (intval($page)-1)*9;//0-8
            $limit = "LIMIT $firstIndex, 9";
        }
        if(!empty($category))
            $categoryWhere = "AND category = ".intval($category);
        
        if(!$_SESSION['rights']==1)
            $active = 'products.is_active = 1';
        else
            $active = "1=1";
        
        if(!empty($searchSequre))
            $searchWhere = "AND ( products.name LIKE '%$searchSequre%' OR products.annotation LIKE '%$searchSequre%')";
        

        $sql = "SELECT 
            products.id AS id, products.name AS name, products.sort AS sort, products.is_active AS is_active, 
            products.color AS color, products.size AS size, products.price AS price, 
            products.category AS category, AVG(reviews.rate*reviews.is_active) as rate
            FROM ".$this->tablename." as products
            LEFT JOIN reviews ON products.id = reviews.productid 
            WHERE $active $categoryWhere $searchWhere GROUP BY 
            products.id, products.name, products.sort, products.is_active, 
            products.color, products.size, products.price, products.category
            ORDER BY  $sortSequred $orderSequred " . $limit;
        $result = $this->conn->query($sql);
            $queryAnswer = [];
        while ($row = $result->fetch_assoc()) {
            $queryAnswer[] = $row;
        }
        
        return $queryAnswer;
    
    }
    
    function countProducts($category = 0, $search = null){

        $searchSequre = $this->conn->real_escape_string(htmlspecialchars(trim($search)));

        if($category>0)
            $categoryWhere = "AND category = ".intval($category);
        if(!empty($searchSequre))
            $searchWhere = " AND ( products.name LIKE '%$searchSequre%' OR products.annotation LIKE '%$searchSequre%')";

        $result = $this->conn->query("SELECT COUNT(*) AS n FROM ".$this->tablename.' WHERE is_active = 1 '.$categoryWhere.$searchWhere );
        if(!$result){
            return 0;
        }
        $answer = $result->fetch_assoc();
        
        return $answer['n'];
    
    }

    function getInfo(){
        
        $info = [];
        $info['name'] = $this->name;
        $info['is_active'] = $this->status;
        $info['color'] = $this->color;
        $info['size'] = $this->size;
        $info['price'] = $this->price;
        $info['id'] = $this->id;
        $info['annotation'] = $this->annotation;
        $info['category'] = $this->category;
        $info['categoryTitle'] = $this->categoryTitle;

        //rate
        require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/review.php');
        $reviewClass = new Review($info['id']);
        $info['rate'] = $reviewClass->calcRate();
        

        if($this->sort != null){
        
            $sql = "SELECT id FROM ".$this->tablename." WHERE sort > ".$this->sort." AND is_active=1 ORDER BY sort ASC LIMIT 0,1";
            $result = $this->conn->query($sql);
            $info['before'] = intval($result->fetch_assoc()['id']);
            
            $afterSequre = intval($after);
            $sql = "SELECT id FROM ".$this->tablename." WHERE sort < ".$this->sort." AND is_active=1 ORDER BY sort DESC LIMIT 0,1";
            $result = $this->conn->query($sql);
            $info['after'] = intval($result->fetch_assoc()['id']);
        
        }

        return $info;

    }


}


