<?php
header('Content-Type: text/html; charset=utf-8');




include_once("../config.php");
include_once("../libs/mysql.php");
include_once("../libs/mysqli.php");


if(isset($_POST['AJAX'])){
		// получаем список адресов по клиенту
		if ($_POST['AJAX'] == 'get_client_addres_for_new_row') {
			echo '<form id="get_client_address">';
			unset($_POST['AJAX']);
			foreach ($_POST as $key => $value) {
				echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
			}
			// CLIENT_ADRES_TBL

			// получаем адреса по клиенту
			$query = "SELECT * FROM `".CLIENTS_TBL."` WHERE id = '".$_POST['client_id']."'";
			$result = $mysqli->query($query) or die($mysqli->error);
					
			$client =  array();
			if ($result->num_rows > 0) {
			    while ($row = $result->fetch_assoc()) {
					// запоминаем id клиентов
					$client = $row;
			   	}
			}


			// получаем адреса по клиенту
			$query = "SELECT * FROM `".CLIENT_ADRES_TBL."` WHERE parent_id = '".$_POST['client_id']."' AND `table_name` = 'CLIENTS_TBL'";
			$result = $mysqli->query($query) or die($mysqli->error);					
			$client['adress'] =  array();
			if ($result->num_rows > 0) {
			    while ($row = $result->fetch_assoc()) {
					// запоминаем id клиентов
					$client['adress'][] = $row;
			   	}
			}

			// получаем телефоны по клиенту
			$query = "SELECT * FROM `".CLIENT_CONT_INFO."` WHERE parent_id = '".$_POST['client_id']."' AND `table` = 'CLIENTS_TBL' AND type = 'phone' ";
			$result = $mysqli->query($query) or die($mysqli->error);					
			$client['phone'] =  array();
			if ($result->num_rows > 0) {
			    while ($row = $result->fetch_assoc()) {
					// запоминаем id клиентов
					$client['phone'][] = $row;
			   	}
			}

			// получаем контактные лица по клиенту
			$query = "SELECT * FROM `".CLIENT_CONT_FACES_TBL."` WHERE client_id = '".$_POST['client_id']."'";
			$result = $mysqli->query($query) or die($mysqli->error);					
			$cont_face_arr =  array();
			if ($result->num_rows > 0) {
			    while ($row = $result->fetch_assoc()) {
					// запоминаем конт. лица
					$client['cont_face'][$row['id']] = $row;
					// запоминаем id конт. лиц
					$cont_face_arr[$row['id']] = $row['id'];
			   	}
			}

			if(count($cont_face_arr) > 0){
				// получаем телефоны по клиенту
				$query = "SELECT * FROM `".CLIENT_CONT_INFO."` WHERE parent_id IN ('".implode("','", $cont_face_arr)."') AND `table` = 'CLIENT_CONT_FACES_TBL' AND type = 'phone' ";
				$result = $mysqli->query($query) or die($mysqli->error);					
				$cont_face_phone_arr =  array();
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						// запоминаем id клиентов
						$cont_face_phone_arr[$row['parent_id']][] = $row;
				   	}
				}

				// echo '<pre>';
				// print_r($cont_face_phone_arr);
				// echo '<pre>';
				foreach ($client['cont_face'] as $key => $value) {
					$client['cont_face'][$key]['phone'] = $cont_face_phone_arr[$key];
				}
				 
			}










			
			// делаем запрос на контактные лица по клиенту и их данные
			// $query = "SELECT * FROM `".CLIENT_CONT_FACES_TBL."` ";
			// echo '<pre>';
			// print_r($client);
			// echo '<pre>';
			echo '<div class="tableAddress" style="display: table;">';	
			if(count($client['adress']) > 0){
				// foreach ($variable as $key => $value) {
				// 	# code...
				// }
				foreach($client['adress'] as $item){
					echo '<div class="row2" onClick="getThisAddress(this,\''.$_POST['id_row'].'\')">

					<div class="cell2" data-id="'.$client['id'].'">'.$client['company'].'</div><div class="cell2">'; 
					// вывод адреса
					echo ($item['postal_code']>0)?$item['postal_code'].', ':'';
					echo ($item['city']!="")?$item['city'].', ':'';
					echo ($item['street']!="")?$item['street'].', ':'';
					echo ($item['house_number']>0)?'дом '.$item['house_number'].', ':'';
					echo ($item['bilding']>0)?'строение '.$item['bilding'].'':'';
					echo ($item['korpus']>0)?'/'.$item['korpus'].',  ':'';
					echo ($item['liter']!="")?'/'.$item['liter'].', ':'';
					echo ($item['office']!="")?'оф. '.$item['office'].'<br/>':'';
					echo ($item['note']!="")?$item['note'].'<br/>':'';
					// вывод телефонов
					if(count($client['phone']) > 0){
						foreach ($client['phone'] as $value) {
							echo ($value['contact']!="")?$value['contact'].'':'';
							echo ($value['dop_phone']!="")?' доб. '. $value['dop_phone'].'<br/>':'';
						}
					}

					// вывод адресов
					if(count($client['cont_face']) > 0){
						echo '<br>Контактные лица:';
						
						foreach ($client['cont_face'] as $cont_face) {
							$html = '<br>';
							$html .= ($cont_face['name']!="")?$cont_face['name']:'';
							$html .= ($cont_face['last_name']!="")?' '.$cont_face['last_name']:'';
							$html .= ($cont_face['surname']!="")?' '.$cont_face['surname']:'';
							$html .= ($cont_face['position']!="")?' ('.$cont_face['position'].')':'';
							$html .= ($html != '')?'':'';
							echo $html;
							if(is_array($cont_face['phone']) && count($cont_face['phone']) >0){
								// echo '<pre>';
								// print_r($cont_face['phone']);
								// echo '<pre>';
								foreach ($cont_face['phone'] as $phones) {
									$phone_html = '<br>';
									$phone_html .= ($phones['contact']!="")?'тел.: '.$phones['contact'].'':'';
									$phone_html .= ($phones['contact']!="" && $phones['dop_phone']!="")?' доб. '. $phones['dop_phone'].'':'';
									echo ($phone_html!='<br>')?$phone_html:'';
								}
							}
						}

					


					// if($item['phone']!=""){echo 'тел.: '.$item['phone'].'<br/> ';}
					echo '</div></div>';
					}
				}
			}else{
				echo 'У клиента "'.$client['company'].'" не заведён адрес.';
			}
				  
			
				
			echo '</div>';
			echo '<input type="hidden" name="client_id" value="'.$clients_arr[0]['id'].'">';
			echo '<input type="hidden" name="AJAX" value="get_client_addres_for_new_row">';



			




			echo '</form>';

		}
		// контактное лицо
		// if ($_POST['AJAX'] == 'get_client_addres_for_new_row') {
			
		// }
		exit;
}

// echo DOSTAVKA_BIG_ROW_TBL;exit;
//////////////////////////////////////////////

//////////////////////ФУНКЦИЯ ОТПРАВКИ БЕЗ ВЛОЖЕНИЯ *****START******
function sendmail($to,$fromemail,$from_name,$subject,$message,$file_path) {
	 	$charset="UTF-8";
		$from = '=?'. $charset .'?b?'. $from_name .'?='; 
		$mailfrom = ' <'. $fromemail .'>';
		$headers  = "Content-type: text/html;  \r\n";
	    $headers .= "From: ".$from." <dostavka@apelburg.ru>\r\n";
		//$subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?= ';
		//$fromemail = "dostavka@apelburg.ru";
		//mail($to, $subject, $message, $headers, '-f '.$fromemail);
	    //if(!mail($to, $subject, $message, $headers, '-f '.$fromemail)){
		if(!mail($to, $subject, $message, $headers)){
			echo "<br><b style='color:red'>Собщение НЕ отправлено</b>";  
		}
};	
//////////////////////ФУНКЦИЯ ОТПРАВКИ БЕЗ ВЛОЖЕНИЯ *****END******
//////////////////////////////////////////////
//////////////////////////////////////////////
////////next
	if(isset($_GET['name']) && $_GET['name']=='changeTextareaTD'){
		$column = $_GET['column'];
		if($column=='second_point'){
		$task = DOSTAVKA_SMALL_ROW_TBL;	
		}else{
		$task = DOSTAVKA_BIG_ROW_TBL;
		}		
		$id_big_row = $_GET['id_big_row'];
		$text = $_GET['text'];
		
		$query = "UPDATE `$task` SET `$column`='$text' WHERE `id` = $id_big_row";
		//echo $query;
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
		echo "rec_text";		
	}


if(isset($_POST['name'])){
	/////////next
	if($_POST['name']=='add_new_address'){
		$date = $_POST['date'];
		$add_num_rows = $_POST['add_num_rows'];
		$query = "
INSERT INTO `".DOSTAVKA_BIG_ROW_TBL."` (`id`, `num_rows`, `status`, `date`, `parent_id_address`, `target_typpe`, `target`, `actions`, `docs`, `date_delivery`, `contacts`, `disable_editing`) VALUES ('NULL', '$add_num_rows', 'off', '$date', '', '', '', '', '', '', '',''); ";
		//echo $query;
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
		echo mysql_insert_id();	
	}
	//////////next
	if($_POST['name']=='del_big_row'){
		$id =  trim($_POST['id_kurier']);	
		$checkbox = $_POST['checkbox'];
		if($checkbox>0){
			$query = "DELETE FROM `".DOSTAVKA_SMALL_ROW_TBL."` WHERE `".DOSTAVKA_SMALL_ROW_TBL."`.`id_parent` = $id";
			$result = mysql_query($query,$db);
		}
		$query = "DELETE FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `id` = $id";
		$result = mysql_query($query,$db);
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());	
		echo "OK";	
		
	}
	////////next
	if($_POST['name']=='queryForAddress'){
	

		$id_row = $_POST['id_row'];
		$id_big_row = $_POST['id_big_row'];
		$address_data = $_POST['address_data'];
		$access = $_POST['crops'];
		$amanager_id = $_POST['user_id'];
		
		$tableName['suppliers']='os__supplier_list';//nickName    addres
		$tableName['clients']='os__client_list'; //company      delivery_address
		
		switch($address_data)
		{
			case 'suppliers':
				$query = "
				SELECT `om_sl`.`id` , `om_sl`.`nickName` , `om_sl`.`addres` , `om_sl`.`phone` , `om_rscf`.`name` , `om_rscf`.`phone` AS `cf_phone`
				FROM `os__supplier_list` AS `om_sl`
				INNER JOIN `os__supplier_cont_faces_relation` AS `om_rscf` ON `om_sl`.`id` = `om_rscf`.`supplier_id`
				ORDER BY `om_sl`.`nickName`";
				// echo '*1*';
				$result = mysql_query($query,$db);
				if(!$result)exit(mysql_error());
				//echo $query;
				echo '<div class="tableAddress">';	  
				if(mysql_num_rows($result) > 0){
					while($item = mysql_fetch_assoc($result)){
						echo '<div class="row2" onClick="getThisAddress(this,\''.$id_row.'\')"><div class="cell2" data-id="'.$item['id'].'">'.$item['nickName'].'</div><div class="cell2">'.$item['addres'].' общ. тел.: '.$item['phone'].'<br>'.$item['name'].' '.$item['cf_phone'].'</div></div>';
					}
				}
				echo '</div>';
				// echo '*2*';
				break;
			case 'clients':
				// $query = "SELECT `c_l`.`id`,
				// 		`c_l`.`company`,`c_l`.`phone`,`c_l`.`addres`,`c_l`.`delivery_address`,`rel_clm`.`client_id`,`rel_clm`.`manager_id`,`rel_ccf`.`name` AS `cf_name`, `rel_ccf`.`department` AS `cf_phone1`,`rel_ccf`.`phone` AS `cf_phone2`,`rel_ccf`.`set_main` AS `cf_set_main` FROM `os__client_list` AS `c_l`
				// INNER JOIN `".RELATE_CLIENT_MANAGER_TBL."` AS `rel_clm` ON `c_l`.`id`=`rel_clm`.`client_id`
				// LEFT JOIN `".CLIENT_CONT_FACES_TBL."` AS `rel_ccf` ON `rel_ccf`.`id` IN (SELECT `id`
				// FROM `".CLIENT_CONT_FACES_TBL."` AS `tbl_4`
				// WHERE `tbl_4`.`client_id` = `c_l`.`id`
				// AND `tbl_4`.`set_main` = 'on') ";



				
				echo '<form id="get_client_addres_for_new_row">';

				unset($_POST['name']);
				foreach ($_POST as $key => $value) {
					echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
				}
				

				// запрашиваем данные по клиентам
				$query = "SELECT * FROM `".CLIENTS_TBL."`";
				// если юзер админ ненужно грузить базу лишней выборкой - выгружаем все
				if($access!=1){					
					// получаем id сонтактных клиентов и контактных лиц прикрепленнных к менеджеру
					$query_get_relate = "SELECT * FROM `".RELATE_CLIENT_MANAGER_TBL."` ";
					$query_get_relate .= " WHERE `manager_id`= $amanager_id ";

					$result = $mysqli->query($query_get_relate) or die($mysqli->error);
					
					$clients_id =  array();
					$i = 0;
					if ($result->num_rows > 0) {
					    while ($row = $result->fetch_assoc()) {
							// запоминаем id клиентов
							$clients_id[$row['client_id']] = $row['client_id'];
							$i++;
					   	}
					}
					

					if($i == 0){
						echo "<div style='padding:5px 5px 5px 10px; font-size:15px;color:grey; text-align:center; margin-top:200px'>К сожалению у вас пока не заведено ни одного клиента.</div>";
						return;
					}

					$clients_id_str = "'".implode("','", $clients_id)."'";
					$query .= " WHERE id IN (".$clients_id_str.")"; 
				}
				$query .= " ORDER BY company ASC";

				$result = $mysqli->query($query) or die($mysqli->error);
				if ($result->num_rows > 0) {
				    while ($row = $result->fetch_assoc()) {
						// $clients_row[$row['client_id']]['address_row'] = array();
						$clients_arr[$row['id']] = $row;
				   	}
				}
				// echo $query;
				// echo '<pre>';
				// print_r($clients_arr);
				// echo '<pre>';
				echo '<div class="tableAddress" style="display:table">';	
				// делаем запрос на контактные лица по клиенту и их данные
				// $query = "SELECT * FROM `".CLIENT_CONT_FACES_TBL."` ";
				echo '<h2>Выберите клиента из списка:</h2>';
				foreach ($clients_arr as $key => $item) {

					echo '<div class="row2 checkThisClient" data-id="'.$item['id'].'">
							<div class="cell2">'.$item['company'].'</div>
						</div>';
				}
				  
				echo '<input type="hidden" name="client_id" value="'.$clients_arr[0]['id'].'">';
				echo '<input type="hidden" name="AJAX" value="get_client_addres_for_new_row">';
				
				echo '</div>';
				// echo '*2*';
				echo '</form>';
				break;
			default:		 
				echo ("адреса не найдены");
		} 
	}


	


	////////next
	if($_POST['name']=='getThisAddress'){
		$company = $_POST['company'];
		$address = $_POST['address'];
		$id_row = $_POST['id_row'];
		$id_row = str_replace('tr_for_id_','',$id_row);
		$parent_id_address=$_POST['parent_id_address'];
		$target_typpe=$_POST['target_typpe'];
		
		$query = "UPDATE `".DOSTAVKA_BIG_ROW_TBL."` SET 
			`target`='$company',
			`contacts`='$address',
			`parent_id_address`='$parent_id_address', 
			`target_typpe`='$target_typpe' 
			WHERE `id` = $id_row";

		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
		//echo "$company    $address    $id_row     $target_typpe     $parent_id_address";
		echo "OK";
	}
	////////next
	if($_POST['name']=='kk_task'){
		$id_parent = $_POST['id_parent'];
		$id_manager = $_POST['id_manager'];
		$date = $_POST['date'];
		$give_take = $_POST['give_take'];
		$actions = $_POST['actions'];
		$second_point = $_POST['point_start'];
		//print_r ($_POST);
		//".DOSTAVKA_SMALL_ROW_TBL."
		$query = "
		INSERT INTO `".DOSTAVKA_SMALL_ROW_TBL."` (
			`id` ,
			`id_parent` ,
			`id_manager` ,
			`date` ,
			`actions` ,
			`query_change` ,
			`dop_redactor` ,
			`status_task`,
			`give_take`,
			`second_point`
			)
			VALUES (
			NULL , '$id_parent', '$id_manager', '$date', '$actions', '', '', '', '$give_take', '$second_point'
			);
		";
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
		echo mysql_insert_id();
		//echo $query;
	}
	////////next
	if($_POST['name']=="cleanThisePosition"){
	$text_id = trim($_POST['textarea_id']);
	$id = str_replace('task_','',$text_id);
	$query = "DELETE FROM `".DOSTAVKA_SMALL_ROW_TBL."` WHERE `".DOSTAVKA_SMALL_ROW_TBL."`.`id` = $id";
	$result = mysql_query($query,$db);
	//echo $query.'     ';
	if(!$result)exit(mysql_error());
	echo "OK";	}
	////////next
	if($_POST['name']=='checkOne'){
		$id_smal_row = $_POST['id_smal_row'];
		$status_task = $_POST['status_task'];
		$man_id = $_POST['user_id'];
		
		//////////////////////меняем статус задачи
		$query = "UPDATE `".DOSTAVKA_SMALL_ROW_TBL."` SET 
			`status_task`='".$status_task."' 
			WHERE `id` = '".$id_smal_row."'";
		//echo $query;
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
		echo "check_OK";	
		
		//////////////////////узнаем email автора задачи, отправляем оповещение		
		$query = "SELECT * FROM `".DOSTAVKA_SMALL_ROW_TBL."` INNER JOIN `".MANAGERS_TBL."` AS `omml` ON `".DOSTAVKA_SMALL_ROW_TBL."`.`id_manager` = `omml`.`id`   WHERE `".DOSTAVKA_SMALL_ROW_TBL."`.`id` = $id_smal_row";
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
		
		if(mysql_num_rows($result) > 0){
			while($item = mysql_fetch_assoc($result)){
			////*****
			$email = $item['email'];
			$email_2 = $item['email_2'];
			}
			if($status_task == 1){$status = 'ВЫПОЛНЕНО';}else{$status = 'НЕ ВЫПОЛНЕНО';}
			if(trim($email_2)=='')$email_2=trim($email);
			if(trim($_POST['target'])!='')$text="\"".$_POST['target']."\"<br/><br/>";
			$message="
			<strong>Статус Вашей задачи на ".$_POST['date'].":</strong><br/><br/>
			$text
			<strong>по адресу:</strong> \"".$_POST['adress']."\" <br/> 
			<br/>
			<strong>изменен на</strong> \"$status\".
			<br/><br/>
			С уважением, APELBURG.RU<br/>
			СПБ:      +7  (812)  438-00-55<br/>
			Москва:  +7 (495)  781-57-09<br/>
			www.apelburg.ru
			";
		}
		$fromname="APELBURG / Служба доставки";
		$fromemail="dostavka@apelburg.ru";
		$subject="Оповещение службы доставки APELBURG.RU";
		$file_path='';
		//$email = 'kapitonoval2012@gmail.com';
		sendMail($email_2,$fromemail,$fromname,$subject,$message,$file_path); /// ОТПРАВКА СООБЩЕНИЯ О ГОтовности			
	}
	////////next
	/*
	/// изменил запрос на GET
	if($_POST['name']=='changeTextareaTD'){
		$column = $_POST['column'];
		if($column=='second_point'){
		$task = '".DOSTAVKA_SMALL_ROW_TBL."';	
		}else{
		$task = '".DOSTAVKA_BIG_ROW_TBL."';
		}		
		$id_big_row = $_POST['id_big_row'];
		$text = $_POST['text'];
		
		$query = "UPDATE `$task` SET `$column`='$text' WHERE `id` = $id_big_row";
		//echo $query;
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
		echo "rec_text";		
	}
	*/
	////////next
	if($_POST['name']=='changeTextareaTableMin'){
		$id_min_row = $_POST['id_min_row'];
		$text = $_POST['text'];
		
		$query = "UPDATE `".DOSTAVKA_SMALL_ROW_TBL."` SET `actions`='".$text."' WHERE `id` = '".$id_min_row."'";
		//echo $query;
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
		echo "rec_text";	
	}
	////////next
	if($_POST['name']=='checkAllRow'){
		$id_str = $_POST['checkbox'];
		$query = "UPDATE `".DOSTAVKA_SMALL_ROW_TBL."` SET `status_task` = '1' WHERE `id` IN (";
		$query .= "$id_str)";
		//echo $query;
		$result = mysql_query($query,$db);	
		if(!$result)exit(mysql_error());
		echo "update_ok";	
	}
	////////next
	if($_POST['name']=='uncheckAllRow'){
		$id_str = $_POST['checkbox'];
		$query = "UPDATE `".DOSTAVKA_SMALL_ROW_TBL."` SET `status_task` = '0' WHERE .`id` IN (";
		$query .= "$id_str)";
		//echo $query;
		$result = mysql_query($query,$db);	
		if(!$result)exit(mysql_error());
		echo "update_ok";
	}
	////////next
	if($_POST['name']=='checkOneRow'){
		$id_str = $_POST['checkbox'];
		$query = "UPDATE `".DOSTAVKA_SMALL_ROW_TBL."` SET `status_task` = '0' WHERE `id` = $id_str";
		//echo $query;
		$result = mysql_query($query,$db);	
		if(!$result)exit(mysql_error());
		echo "update_ok";
	}
	////////next
	if($_POST['name']=='changeStatusBigRow'){
		$id_big_row = $_POST['id_big_row'];
		$status = $_POST['status'];
		
		$query = "UPDATE `".DOSTAVKA_BIG_ROW_TBL."` SET `status`='$status' WHERE `id` = $id_big_row";
		//echo $query;
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
		echo "change_status_ok";	
	}
	////////next
	
	if($_POST['name']=='rec_date'){
	
	if(isset($_POST['id_min_row']) && $_POST['id_min_row']==''){echo "Невозможно перенести уже выполненное задание"; return;}
	$id_row = $_POST['id_big_row'];
	$id_min_row = $_POST['id_min_row'];//список ID мелких строк через запятэ
	$old_date = trim($_POST['old_date']);
	$new_date = trim($_POST['new_date']);
	if($old_date == $new_date){echo "Невозможно перенести доставку на текущий день"; return;}
	$date_log = "пермещено с $old_date на $new_date <br/>";
	
	//проверяем наличие данного адреса в  день на который производится перемещение
	
		$query = "SELECT `id` FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `parent_id_address` = (SELECT `parent_id_address` FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `id` = '$id_row') AND `target_typpe`= (SELECT `target_typpe` FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `id` = '$id_row') AND `date`= '$new_date'";
	
	
	
	$query = "SELECT * FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `id`= '".$id_row."'";
		$result = mysql_query($query,$db);
		if(!$result)exit(mysql_error());
			
		if(mysql_num_rows($result) > 0){//если адрес уже существует - берем его ID
			while($item = mysql_fetch_assoc($result)){
			$target_type = $item['target_typpe'];		
			}
		}	
	if($target_type == "" || $target_type == 0){
		$query = "SELECT `id` FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `target` = (SELECT `target` FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `id` = '$id_row') AND `date`= '$new_date'";
	}
	
	$result = mysql_query($query,$db);
	if(!$result)exit(mysql_error());
		
	if(mysql_num_rows($result) > 0){//если адрес уже существует - берем его ID
		while($item = mysql_fetch_assoc($result)){
		$id_big_row = $item['id'];//запоминаем ID адреса
		}
	}else{//если адреса в данный день не существует, создаем новый и берем его ID
		$query = "
	INSERT INTO `".DOSTAVKA_BIG_ROW_TBL."` (`num_rows`,`status`, `parent_id_address`, `target_typpe`,`target` , `actions`, `docs`, `date_delivery`, `contacts`, `disable_editing`) 
  	SELECT  `num_rows`,`status`, `parent_id_address`, `target_typpe`,`target`, `actions`, `docs`, `date_delivery`, `contacts`, `disable_editing` FROM `".DOSTAVKA_BIG_ROW_TBL."` 
    WHERE `id` = $id_row	
	";
		$result = mysql_query($query,$db);
		$id_big_row = mysql_insert_id();//запоминаем id созданной поездки
		}
	
	//----------------- удаляем старую поездку при условии, что все поставленные задачи не были выполнены (условие отработано в javascript)--------------//
	if(isset($_POST['del_old_big_row']) && $_POST['del_old_big_row']>0){
	$query = "DELETE FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `id` = $id_row";	
	//echo $query .'    ';
	$result = mysql_query($query,$db);
	}
	
	//----------------- пишем в новую поездку новую дату --------------//	
	$query = "UPDATE `".DOSTAVKA_BIG_ROW_TBL."` SET `date`='$new_date' WHERE `id` = $id_big_row";
	//echo $query .'    ';
	$result = mysql_query($query,$db);
	
	
	//----------------- присваиваем невыполненным задачам новую поездку --------------//	
	$query = "UPDATE `".DOSTAVKA_SMALL_ROW_TBL."` SET `id_parent` = '$id_big_row', date_log = concat( date_log, '".$date_log."') WHERE `id` IN ($id_min_row)";
	$result = mysql_query($query,$db);		
	//echo $query .'     ';
	if(!$result)exit(mysql_error());
	echo "rec_date";	
	
	}
}
//////////////////////////////////////////////



if(isset($_POST['change_action']) && $_POST['change_action']!=''){
	$action = $_POST['change_action'];
	$text_id = trim($_POST['id_task']);
	$id = str_replace('task_','',$text_id);
}

//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
?>