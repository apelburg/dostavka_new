<?php
    include ('lock_2.php');
    include ('dostavka_config.php');
	include ('class_karta_kurijera.php');
	
	$type = (!empty($_GET['type']))? $_GET['type'] : '' ;
	if(!empty($_GET['day'])) list($day,$month,$year) = explode('.',$_GET['day']);
     
	if(isset($_POST['add_data'])){
	   //print_r($_POST['form_data']);
	   extract($_POST['form_data']);
	   $date = $_GET['date'];
	   $query = "INSERT INTO `karta_kurijera_partners_list` VALUES ('','$name','$type','$adress')";
	   $result = mysql_query($query,$db);
	   if(!$result)exit(mysql_error());
	   header('location:'.$_SERVER['REQUEST_URI']);
	}
	if(isset($_POST['change_data'])){
	   extract($_POST['form_data']);
	   $query = "UPDATE `karta_kurijera_partners_list` SET `name` = '$name', `adress` = '$adress' WHERE `id` = '$id'"; 
	   $result = mysql_query($query,$db);
	   if(!$result)exit(mysql_error());
	}
	    if(isset($_POST['delete_data'])){
	   $query = "DELETE FROM `karta_kurijera_partners_list` WHERE `id` = '".$_POST['form_data']['id']."'";
	   $result = mysql_query($query,$db);
	   if(!$result)exit(mysql_error());
	} 
	 

    // выбираем из базы список компаний
    $query = "SELECT*FROM `karta_kurijera_partners_list` WHERE `type` = '".$type."' ORDER BY `name`";
	$result = mysql_query($query,$db);

	if(!$result)exit(mysql_error());
	$cols = '';
	while($item = mysql_fetch_assoc($result)){
		 $cols .= '<form  id="form_'.$item['id'].'" action="" method="POST">
		             <tr id="tr_for_id_'.$item['id'].'">
		               <td id="data_td_1_'.$item['id'].'" height="20">&nbsp;</td>
					   <td id="data_td_2_'.$item['id'].'" align="center" width="70"> 
					      <input name="change_data" type="hidden" value="изменить">
						  <input name="delete_data" type="button" value="удалить" style="width:55px;" onclick="(function(e){if(confirm(\'Вы действително собираетесь удалить эту запись?\')){ e.type=\'hidden\'; e.form.submit();}})(this);">
					      <input name="form_data[id]" type="hidden" value="'.$item['id'].'">
					   </td>
		               <td valign="top" onclick="change_type_coll(\''.$item['id'].'\',\'partners_list\');">
					      <div id="data_div_1_'.$item['id'].'" style="width:100%;padding:4px 4px 4px 4px;">'.nl2br($item['name']).'</div>
					      <textarea id="data_textarea_1_'.$item['id'].'" name="form_data[name]" style="width:100%;display:none;" onchange="submit_data(\''.$item['id'].'\',\'partners_list\');">'.$item['name'].'</textarea>
					   </td>
					   <td valign="top" onclick="change_type_coll(\''.$item['id'].'\',\'partners_list\');">
					      <div id="data_div_2_'.$item['id'].'" style="width:100%;padding:4px 4px 4px 4px;">'.nl2br($item['adress']).'</div>
					      <textarea id="data_textarea_2_'.$item['id'].'" name="form_data[adress]" style="width:100%;display:none;" onchange="submit_data(\''.$item['id'].'\',\'partners_list\');">'.$item['adress'].'</textarea>
					  </td>
					</tr>
				 </form>';
	}
?>
<html>
<head>
<link href="dostavka_css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="dostavka_js.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_top">
       <table class="page_top" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="330">
             <p><a href="<?php  if(!empty($_GET['date'])) echo '/dostavka/dostavka_podrobno.php?date='.$_GET['date'].'&day='.$day.'.'.$month.'.'.$year; else echo 'index.php?day='.$day.'.'.$month.'.'.$year; ?>"><<назад</a></p>
            </td>
            <td  width="100" align="center"> 
               <a href="#" onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">сохранить</a>
            </td> 
            <td  width="20" align="center">&nbsp;
                 
            </td>     
            <td  width="100" align="center"> 
               <a href="#" onClick="javascript:window.print();return false;" onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">распечатать</a>
            </td> 
            <td  width="20" align="center">&nbsp; 
                
            </td>      
            <td width="150" align="center">
               <a href="/dostavka/partners_list.php?type=suppliers&date=<?php  echo  $_GET['date']; ?>&day=<?php  echo $day.'.'.$month.'.'.$year; ?>"  onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">список поставщиков</a>
            </td>
            <td  width="20" align="center">&nbsp;
                 
            </td> 
            <td width="150" align="center"> 
               <a href="/dostavka/partners_list.php?type=clients&date=<?php  echo  $_GET['date']; ?>&day=<?php  echo $day.'.'.$month.'.'.$year; ?>"  onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">список клиентов</a>
            </td>
            <td  width="20" align="center">&nbsp; 
                
            </td> 
            <td width="70" align="center"> 
               <a href="/"  onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">на сайт</a>
            </td>
            <td  width="20" align="center">&nbsp; 
                
            </td> 
            <td width="70" align="center"> 
               <a href="#"  onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';show_hide_div('kuriers_list');" onMouseOut="this.parentNode.style.backgroundColor = null;show_hide_div('kuriers_list')" style="display:block; height:23px;padding-top:8px;">курьеры</a>
               <div id="kuriers_list" style="position:absolute;display:none; background-color:#FFFFFF; text-align:left; padding:10px; border:solid #CCCCCC 1px; line-height:18px;">
                  Юрий Александрович 980-76-80<br>
                  Олег +7 952 365 36 46
               </div>
            </td>
            <td>&nbsp;
                 
            </td>
            <td width="150" align="center"> 
               <a href="?auth_out">выйти</a>
            </td>
          </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="900" border="1" cellpadding="0" cellspacing="0">
        <tr>
          <td width="22" height="40">&nbsp;</td>
          <td colspan="2" width="310" align="center" style=" background-color:#EEEEEE;"><a href="/dostavka/index.php?day=<?php  echo $day.'.'.$month.'.'.$year; ?>" style="display:block;font-size:16px;height:30px;padding-top:6px;" onMouseOver="this.parentNode.style.backgroundColor='#D5D5D5'"  onMouseOut="this.parentNode.style.backgroundColor='#EEEEEE'">сводная карта</a></td>
          <td width="550"><p>&nbsp;</p></td>
        </tr>
          <?php  echo  $cols; ?>
        <form action="" method="POST">
        <tr>
          <td height="40">&nbsp;</td>
          <td align="center">
             <input name="add_data" style="width:55px;" type="submit" value="добавить">
          </td>
          <td valign="top">
             <textarea id="form_data_target" onClick="show_companies_list(this);" name="form_data[name]" style="width:100%;"></textarea>
             <input type="hidden" name="form_data[type]" value="<?php  echo  $type; ?>">
          </td>
          <td valign="top"><textarea  name="form_data[adress]" style="width:100%;"></textarea></td>
        </tr>
        </form>
      </table>
   </tr>
</table>
</body>
</html>
