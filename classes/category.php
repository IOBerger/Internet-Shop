<?php 

//Класс категории
class Category
{
    public $id;
    public $status;
    public $title;
    public $sort;
    public $description;
    public $parent;
    public $conn;

    function __construct($id = null)
    {
        $this->id = $id;
        $this->conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbusername'], $GLOBALS['dbpassword'], $GLOBALS['dbdatabase']);
        if($this->conn->connect_error){
            die('{"success":0,"error":"Ошибка базы данных"}');
        }
        if($id != null){
                $result = $this->conn->query('SELECT title, is_active as status, sort, description, parent FROM product_categories WHERE id = ' . $id);        
                $data = $result->fetch_assoc();
                $this->title = $data['title'];
                $this->sort = $data['sort'];
                $this->status = $data['status'];
                $this->description = $data['description'];
                $this->parent = $data['parent'];
        }
    }

    function getList($exclude = null){
        if(!empty($exclude)){
            $queryWhere = "WHERE id != ".intval($exclude);
        }
        $result = $this->conn->query("SELECT * FROM product_categories ".$queryWhere."  ORDER BY id");
        $queryAnswer = [];
        while ($row = $result->fetch_assoc()) {
            $queryAnswer[] = $row;
        }
        
        return $queryAnswer;
    }

    function getInfo(){
        
        $info = [];
        $info['title'] = $this->title;
        $info['id'] = $this->id;
        $info['status'] = $this->status;
        $info['sort'] = $this->sort;
        $info['description'] = $this->description;
        $info['parent'] = $this->parent;
        
        return $info;

    }
/*
    function processKnot(
        $original,//массив, в котором перечислены все категории
        $i,//индекс обрабатываемый
        $result//дерево
    ){

        $result = $this->addKnot($i, $result);


    }

    function getTree(){
       $originalList = $this->getList();
       print_r($list);
       

    }
*/

    function getMenuList($search = ''){

        $searchSequre = $this->conn->real_escape_string(htmlspecialchars(trim($search)));

        $originalList = $this->getList();

        if(!empty($searchSequre))
            $searchWhere = "(title LIKE '%$searchSequre%')";
        else
            $searchWhere = " 1 = 1 ";
        
        $sql = "SELECT * FROM product_categories WHERE parent = 0 AND $searchWhere ORDER BY sort ASC";
        $result = $this->conn->query($sql);
        $mainCategories = [];
        
        while ($row = $result->fetch_assoc()) {
            $mainCategories[] = $row;
        }      
        /*
        $mainArray = [];
        for($i=0;$i<     $mainCategories as $main){
            $mainArray[]
        }
        */

        

        $categories = [];
        //id -- id основной категории, содержимое -- информация о подкатегории
        if(!empty($mainCategories)){
        

        foreach($mainCategories as $main){
            
            if(intval($main['id'])==0)
                $searchParentWhere = "1 = 0";
            else
                $searchParentWhere = "parent = ".$main['id'];
         
            $sql = "SELECT * FROM product_categories WHERE $searchParentWhere AND $searchWhere  ORDER BY sort ASC";
            //echo $sql;
            $result = $this->conn->query($sql);
            //echo $sql;
            
            $categories[$main['id']] = [];
            while ($row = $result->fetch_assoc()) {
                //print_r($row);
                $categories[$main['id']][] = $row;
            }      
            
        }
        $answer = [$mainCategories, $categories];
        }else{

            $sql = "SELECT * FROM product_categories WHERE $searchWhere ORDER BY sort ASC";
            $result = $this->conn->query($sql);
            
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }      
            $answer = [$categories, []];
        }
        //print_r($categories);
        return $answer;

    }

    function AddToDatabase($title, $description, $parent, $sort){

        //echo "$name, $color, $size, $price";
        $titleSequre = $this->conn->real_escape_string(htmlspecialchars($title));
        $descriptionSequre = $this->conn->real_escape_string(htmlspecialchars($description));
        $parentSequre = intval($parent);
        $sortSequre = intval($sort);
        //echo $title.' ',$description; 
        /*
        $stmt = $this->conn->prepare("INSERT INTO product_categories (title, desctription,date_added) VALUES (?,?,)");
        $stmt->bind_param("sss", $titleSequre, $descriptionSequre,date('Y-m-d H:i:s'));
        $result=$stmt->execute();
        */
        $sql = "INSERT INTO product_categories (parent,sort,title, description,date_added) VALUES ($parentSequre,$sortSequre,'$titleSequre','$descriptionSequre','".date('Y-m-d H:i:s')."')";
        //echo $sql;
        $result = $this->conn->query($sql);
        if(!$result){
            $answer = '{"success":0,"error":"Ошибка базы"}';
        }else{
            $answer = '{"success":1}';
        }
    

        $result = $this->conn->query("
            SELECT MAX(id) AS id FROM product_categories;
        ");

        $lastID = ($result->fetch_row())['id'];
    
        $log = date('Y-m-d H:i:s') . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $lastID $parentSequre $sortSequre '$titleSequre'";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/add_category.log', $log . PHP_EOL, FILE_APPEND);

        return $answer;

    }

    function EditInDatabase($title, $description, $parent, $sort){

        //Изменение товара

        $dateEdited = date('Y-m-d H:i:s');

        $titleSequre = $this->conn->real_escape_string(htmlspecialchars($title));
        $descriptionSequre = $this->conn->real_escape_string(htmlspecialchars($description));
        $parentSequre = intval($parent);
        $sortSequre = intval($sort);

        $stmt = $this->conn->prepare("UPDATE product_categories SET title = ?, description = ?, parent = ?, sort = ? WHERE id = ?");
        $stmt->bind_param("ssidi", $titleSequre, $descriptionSequre, $parentSequre, $sortSequre, $this->id);
        $result=$stmt->execute();
        if(!$result){
            $answer = '{"success":0,"error":"Ошибка базы"}';
        }else{
            $answer = '{"success":1}';
        }

        $log = $dateEdited . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $this->id '$titleSequre'";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/edit_category.log', $log . PHP_EOL, FILE_APPEND);

        return $answer;

    }

    function ChangeActive($status){

        $statusSequred = intval($status);
        $dateDeleted = date("Y-m-d H:i:s");
    
        //$stmt = $this->conn->prepare("UPDATE product_categories SET is_active = 0 WHERE id = ?");
        $stmt = $this->conn->prepare("UPDATE product_categories SET is_active = ? WHERE id = ?");
        //echo $this->id;
        $stmt->bind_param("ii", $statusSequred, $this->id);
        $result=$stmt->execute();
        if(!$result){
            $answer = '{"success":0,"error":"Ошибка базы данных"}';
        }else{
            $answer = '{"success":1}';
        }
    
        $log = $dateDeleted . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $this->id";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/delete_category.log', $log . PHP_EOL, FILE_APPEND);
    

        return $answer;
    
    }    
    
    function DeleteForever(){

        $dateDeleted = date("Y-m-d H:i:s");
    
        //$stmt = $this->conn->prepare("UPDATE product_categories SET is_active = 0 WHERE id = ?");
        $stmt = $this->conn->prepare("DELETE FROM product_categories WHERE id = ?");
        //echo $this->id;
        $stmt->bind_param("i", $this->id);
        $result=$stmt->execute();
        if(!$result){
            $answer = '{"success":0,"error":"Ошибка базы данных"}';
        }else{
            $answer = '{"success":1}';
        }
    
        $log = $dateDeleted . ': ' . getIP() . ' \'' . $_SERVER['HTTP_USER_AGENT'] . "' {$_SESSION['login']} $this->id";
        file_put_contents($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/log/delete_category.log', $log . PHP_EOL, FILE_APPEND);
    

        return $answer;
    
    }


}
