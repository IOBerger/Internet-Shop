<div class="main">

<?php 

    if(array_key_exists($page,$menu)){
        require_once($_SERVER["DOCUMENT_ROOT"] . $siteFolder . '/includes/'  . $page . '.php'); 
        require_once($_SERVER["DOCUMENT_ROOT"] . $siteFolder . '/templates/'  . $template . '/pages/' . $page . '.php'); 
    }

?>

</div>

