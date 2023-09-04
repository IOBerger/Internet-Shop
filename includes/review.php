 <?php 

    require_once($_SERVER["DOCUMENT_ROOT"].$GLOBALS['siteFolder'].'/classes/product.php');

    $productClass = new Product($_GET['product']);
    $product = $productClass->getInfo();

    function getRewievList($userid){

        $reviewList = [];

        if(!empty($userid)){
            $conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbusername'], $GLOBALS['dbpassword'], $GLOBALS['dbdatabase']);
            if(!$conn->connect_error){
                $result = $conn->query('SELECT *, r.date_added as review_date, r.is_active as status, r.id as reviewid FROM reviews as r LEFT JOIN products as p ON p.id=r.productid WHERE r.userid =' . intval($userid));        
                while ($row = $result->fetch_assoc()) {
                    $reviewList[] = $row;
                }
            }
        }

        return $reviewList;

    }

    function getReview($id){

        if(!empty($id)){
            $conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbusername'], $GLOBALS['dbpassword'], $GLOBALS['dbdatabase']);
            if(!$conn->connect_error){
                $result = $conn->query('SELECT * FROM reviews WHERE is_active = 1 AND productid =' . intval($id));        
                $review = $result->fetch_assoc();
            }
        }

        return $review;

    }

    $review = getReview($_GET['product']);
    $reviews = getRewievList($_SESSION['id']);

    foreach($reviews as $key => $item){
        $reviews[$key]['comment'] = str_replace(array("\r\n", "\r", "\n"), '<br>', $item['comment']);
    }
?>