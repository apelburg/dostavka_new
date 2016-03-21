<?php 
include ('dostavka_config.php');

	
    if( empty($_POST['data'])){
        return false;
    }
    extract($_POST);
    if( is_array($data) && sizeof( $data ) > 0 ){
        foreach( $data as $sort_order => $id ){
			
			$id=substr($id, 10);
            $query = "UPDATE `karta_kurijera` SET num_rows = '".(int)$sort_order."' WHERE `id` = '".(int)$id."'";
			$result = mysql_query($query,$db);
	   		if(!$result)exit(mysql_error());
			header('location:'.$_SERVER['REQUEST_URI']);
 			
        }
    }


?>