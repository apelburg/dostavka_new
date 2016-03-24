<?php
header('Content-Type: text/html; charset=utf-8');
    // Безопасность от SQL иньекций?
	include ('lock_2.php');
    include ('dostavka_config.php');
	include ('class_karta_kurijera.php');
	$date_arr = explode('/',urldecode($_GET['date']));
	$date = $date_arr[0];
	$inside_date = explode('.',$date);
	$name_cur_day = $date_arr[1];
	//print_r($_SESSION);
   // вызываем класс
  
   if(!empty($_GET['day'])) list($day,$month,$year) = explode('.',$_GET['day']);
   else {
      $day = date("d");
      $month = date('m');
      $year = date('Y');
   }
   $karta = new karta_kurijera($day,$month,$year);
 	
	
	$query = "SELECT `b` . * , `f`.`id` AS `id_2`, `f`.`name`, `f`.`last_name` FROM 
	`karta_kurijera_task` AS `b` INNER JOIN `os__manager_list` AS `f` ON `b`.`id_manager` = `f`.`id` ORDER BY `b`.`give_take` ASC";
	$result = mysql_query($query,$db);
	if(!$result)exit(mysql_error());
	$task[]='';
	if(mysql_num_rows($result) > 0){
	    while($item = mysql_fetch_assoc($result)){
			$task[] = $item;
		}
	}
	
	
	if(isset($_GET['for_print'])){
	
	    // $query = "SELECT*FROM `karta_kurijera` WHERE `date` = '".$date."' ORDER BY `num_rows`";
	    $query = "SELECT*FROM `karta_kurijera` WHERE `date` = '".$date."' AND `disable_editing` = '0' ORDER BY `num_rows`";
	    $result = mysql_query($query,$db);
	    if(mysql_num_rows($result) > 0){
		    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="dostavka_new_css.css" rel="stylesheet" type="text/css">
</head>
<body>
			      <style type="text/css">div{font-size:12px;line-height:18px;}</style>
			      <table border="1" align="center" width="1024" cellspacing="0" cellpadding="0">
			        <tr>
					    <td width="100" colspan="2" class="table_top" height="40"><p style="font-size:12px;">карта на:<br/>'.$date.'<br/>'.$name_cur_day.'</p></td> 
						<td colspan="4" class="table_top"><p><a href="#" onclick="javascript:window.print();return false;" style="font-size:12px;">печатать</a></p></td>
					</tr>
			        <tr>
						<td width="20" class="table_top" height="40" style="padding:3px;"><p>&nbsp;</p></td>
						<td width="80" class="table_top" style="padding:3px;"><p>Компания</p></td>
						<td width="400" class="table_top" style="padding:3px;"><p>№/Забрать-Отдать(что забрать, куда везти)/Задача/Менеджер<br/>
					пример : 1. Забрать кружки синие, 100 шт /везти в ДЛМ / Аня</p>
						</td>
						<td width="80" class="table_top" style="padding:3px;"><p>Документы</p></td>
						<td width="80" class="table_top" style="padding:3px;"><p>Время</p></td>
						<td width="200" class="table_top" style="padding:3px;"><p>Адрес/Контактное лицо/Телефон</p></td>
					</tr>';
					
	        while($item = mysql_fetch_assoc($result)){
		  		if($item['status']!="on"){
					echo  '<tr>
							<td height="50" valign="top" style="padding:5px;">
								<p>'.++$counter.'</p>
							</td>
							<td width="80" valign="top">
								<div style="padding:5px;">'.$item['target'].'</div>
							</td>
							<td width="450" valign="top"><div style="padding-left:5px;"><div class="table" style="width:100%">';
							
					foreach ($task as $key => $value){		
								if($value['id_parent']==$item['id']){
									if($value['status_task']==0){
										echo '<div class="row"><div class="cell" style="padding-top:5px;"><span style="border:1px solid grey;height:15px; width:15px;">&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div class="cell" style="padding-top:5px;"><span style="border:1px solid grey;height:15px; width:15px;">&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div class="cell" style="padding-top:5px;"><span style="border:1px solid grey;height:15px; width:15px;">&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div class="cell" style="width:70%;padding-top:5px;">'.$value['actions'].'</div><div class="cell" style="padding-top:0px;">'.$value['second_point'].'<br/><span style="font-size:10px;color:grey;">'.$value['name'].'<br> '.$value['last_name'].'</span></div></div>';
									}
								}
					}
					echo  '</div></div></td>
							<td width="80" valign="top">
								<div style="padding:5px;">'.nl2br($item['docs']).'</div> 
							</td>
							<td width="80" valign="top">
								<div style="padding:5px;">'.nl2br($item['date_delivery']).'</div>
							</td>
							<td valign="top">
								<div style="padding:5px;">'.nl2br($item['contacts']).'</div> 
							</td>
						  </tr>';
				}
	
	}
	echo '</table></body></html>';
	}
	
	
	
	 exit;
	}
    // AJAX
    if(isset($_GET['show_companies_list'])){
        $query = "SELECT DISTINCT `name` FROM `karta_kurijera_partners_list` WHERE `type` = '".$_GET['type']." 'ORDER BY `name`";
	    $result = mysql_query($query,$db);

	    if(!$result)exit(mysql_error());
	    while($item = mysql_fetch_assoc($result)){
		     if(trim($item['name']) != ''){ 
			      $query = "SELECT `adress` FROM `karta_kurijera_partners_list` WHERE `name` = '".$item['name']."'";
			      $result_2 = mysql_query($query,$db);
			 
		          echo $item['name']."{@#@}".mysql_result($result_2,0,'adress')."{@}";
			 }
		}
        exit;  
    }
	if(isset($_GET['change_deal_status'])){
	    $status = htmlspecialchars($_GET['change_deal_status']);
        $query = "UPDATE `karta_kurijera` SET `status` = '".$status."' WHERE `id` = '".$_GET['id']."'";
	    $result = mysql_query($query,$db);
	    if(!$result)exit(mysql_error());
		$_SESSION['stat']="true";
        exit;  
    }
	if(isset($_GET['change_date_deal'])){
        $query = "UPDATE `karta_kurijera` SET `date` = '".$_GET['change_date_deal']."' WHERE `id` = '".$_GET['id']."'";
	    $result = mysql_query($query,$db);
	    if(!$result)exit(mysql_error());
        exit;  
    }
	// end AJAX
	
    if(isset($_POST['add_data'])){
	   //print_r($_POST['form_data']);
	   extract($_POST['form_data']);
	   $query = "SELECT*FROM `karta_kurijera` WHERE `date` = '".$date."'";
	   $result = mysql_query($query,$db);
	   if(!$result)exit(mysql_error());
	   $num_rows=mysql_num_rows($result)+1;
	   $query = "INSERT INTO `karta_kurijera` VALUES ('','$num_rows','off','$date','$target','$actions','$docs','$date_delivery','$contacts')";
	   $result = mysql_query($query,$db);
	   if(!$result)exit(mysql_error());
	   header('location:'.$_SERVER['REQUEST_URI']);
	}
	if(isset($_POST['change_data'])){
	   extract($_POST['form_data']);
	   if($old_num_rows>$num_rows){
			$query = "SELECT*FROM `karta_kurijera` WHERE `date` = '".$date."' AND `num_rows`>='".$num_rows."' AND `num_rows`<='".$old_num_rows."'  ORDER BY `num_rows`";
			$result = mysql_query($query,$db);
			if(!$result)exit(mysql_error()); /// распичатать ошибки ... если есть...
			if(mysql_num_rows($result) > 0){
				while($item = mysql_fetch_assoc($result)){
					$num_rows_up=$item['num_rows']+1;
					$query2 = "UPDATE `karta_kurijera` SET num_rows = '".$num_rows_up."' WHERE `id` = '".$item['id']."'";
					//echo $item['num_rows']+1; echo "id = ".$item['id'];
					echo $query2."<br/>";
					$result2 = mysql_query($query2,$db);
					if(!$result)exit(mysql_error());
					header('location:'.$_SERVER['REQUEST_URI']);
				}
			}
	  }else if($old_num_rows<$num_rows){
			$query = "SELECT*FROM `karta_kurijera` WHERE `date` = '".$date."' AND num_rows<='".$num_rows."' AND num_rows>='".$old_num_rows."'  ORDER BY `num_rows`";
			$result = mysql_query($query,$db);
			if(!$result)exit(mysql_error()); /// распичатать ошибки ... если есть...
			if(mysql_num_rows($result) > 0){
				while($item = mysql_fetch_assoc($result)){
					$num_rows_up=$item['num_rows']-1;
					$query2 = "UPDATE `karta_kurijera` SET num_rows = '".$num_rows_up."' WHERE `id` = '".$item['id']."'";
					//echo $item['num_rows']+1; echo "id = ".$item['id'];
					echo $query2."<br/>";
					$result2 = mysql_query($query2,$db);
					if(!$result)exit(mysql_error());
					header('location:'.$_SERVER['REQUEST_URI']);
				}
			}
	  }
	   
	   /*    то что было   */
	   $status = ($status == 'on')? 'on' : 'off' ;
	   $query = "UPDATE `karta_kurijera` SET `status` = '$status',`target` = '$target', `actions` = '$actions', `docs` = '$docs', `date_delivery` = '$date_delivery',`contacts` = '$contacts' WHERE `id` = '$id'";
	   $result = mysql_query($query,$db);
	   if(!$result)exit(mysql_error());
	   
	  // echo 'wqwe';
	   //exit;
	}
    if(isset($_POST['delete_data'])){
	   $query = "DELETE FROM `karta_kurijera` WHERE `id` = '".$_POST['form_data']['id']."'";
	   $result = mysql_query($query,$db);
	   if(!$result){exit(mysql_error());};//else{echo 'удаление прошло успешно<br/>';};
	   $query = "UPDATE `karta_kurijera` SET `num_rows` = num_rows-1 WHERE `num_rows` > '".$_POST['form_data']['num_rows']."' AND `date` = '".$date."'";
	   $result = mysql_query($query,$db);
	   if(!$result){exit(mysql_error());};//else{echo 'обновление строк прошло успешно';};
	}
	
	/******************/

	
	$query = "SELECT*FROM `karta_kurijera` WHERE `date` = '".$date."' ORDER BY `num_rows` ASC";
	$result = mysql_query($query,$db);
	if(!$result)exit(mysql_error());
	/************************/
	$num_rows = mysql_num_rows($result);
	/**************************/
	
	switch ($_SESSION['access']['access']) {
	case '1'://админ
	include('access_admin.php');
	break;
	case '7'://кладовщик
	include('access_admin.php');
	break;
	case '5'://манагер
	include('access_manager.php');
	break; 
	case '4'://манагер
	include('access_manager.php');
	break; 
	case '6'://водитель
	include('access_driver.php');
	break;
	case '8'://снабженец
	include('access_admin.php');
	break;
	default:
		echo "<center>У вас недостаточно прав для доступа в этот раздел, обратитесь к Администрат</center>ору";
	}; 
	?>