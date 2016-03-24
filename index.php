<?php

  // Безопасность от SQL иньекций?
  include ('../libs/mysqli.php');
  include ('lock_2.php');
  include ('dostavka_config.php');
  include ('class_karta_kurijera.php');
  include ('../config.php');
if($_SESSION['access']['user_id'] == 42){
	//@ini_set('date.timezone', 'Moscow/Europe');
	echo date('d.m.Y H:i',time());
	date_default_timezone_set('Moscow');
	echo PHP_VERSION;
}
  // вызываем класс
  
  if(!empty($_GET['day'])) list($day,$month,$year) = explode('.',$_GET['day']);
  else {
    $day = date("d");
    $month = date('m');
    $year = date('Y');
  }
  $karta = new karta_kurijera($day,$month,$year);
   
  // AJAX
  if(isset($_GET['show_details'])){
       $query = "SELECT*FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `id` = '".$_GET['show_details']."'";
	   $result = mysql_query($query,$db);

	   if(!$result)exit(mysql_error());
	   $echo1 = '{@1}'.nl2br(mysql_result($result,0,'target'));
	   //$echo2 = '{@2}'.nl2br(mysql_result($result,0,'actions'));
	   $echo2 = '{@2}';
	   $echo3 = '{@3}'.nl2br(mysql_result($result,0,'docs')).'{@4}'.nl2br(mysql_result($result,0,'date_delivery')).'{@5}'.nl2br(mysql_result($result,0,'contacts'));
	   
	   $query = "SELECT * FROM `".DOSTAVKA_SMALL_ROW_TBL."` inner join `os__manager_list` AS `omml` ON `karta_kurijera_task`.`id_manager` = `omml`.`id`   WHERE `karta_kurijera_task`.`id_parent`='".$_GET['show_details']."'";
	   $result = mysql_query($query,$db);
	   $ii=1;
	   if(mysql_num_rows($result) > 0){
	       while($item = mysql_fetch_assoc($result)){
	   	   $echo2 .= $ii.'). '.$item['actions'].' <span style="color:grey">'.$item['name'].' '.substr($item['last_name'], 0, 2).'.</span><br/>';
		   $ii++;
		   }
	   }
	   echo $echo1.$echo2.$echo3;
       exit;  
   }
   

   // контент
    $calendar = "";
    $content = "";
   if(!isset($_POST['search'])){
     // закладки по дням недели
     $work_week = 5;
      $content = '<table width="1000" border="1" cellpadding="0" cellspacing="0" align="left"><tr><td width="23">
           <button onclick="location = \'?day='.date('d.m.y',mktime(0,0,0,$month,($day - 7),$year)).'\';" class="day_switch_by_order_button"><<</button>
		   <br>
		   <button onclick="location = \'?day='.date('d.m.y',mktime(0,0,0,$month,($day + 7),$year)).'\';" class="day_switch_by_order_button">>></button>
        </td>';
   for($i=0; $i < $work_week ;$i++){
       //if($day != 0){
       $date = date('d.m.y',mktime(0,0,0,$month,$day -(($karta->cur_week_day - 1) - $i),$year));// d.m.y_H.i.s
	   $week_day = $karta->week_day_name_arr[date('l',mktime(0,0,0,$month,$day -(($karta->cur_week_day - 1) - $i),$year))];
	 //  }
	  // else{
//	    $date = date('d.m.y',mktime(0,0,0,date('m'),date("d") -(($karta->cur_week_day - 1) - $i), date("Y")));
//		$week_day = $karta->week_day_name_arr[date('l',mktime(0,0,0,date('m'),date("d") -(($karta->cur_week_day - 1) - $i), date("Y")))];
//	   }
	   
     $content.=  '<td width="200"  height="40"><p><a href="/dostavka_new/dostavka_podrobno.php?date='.urlencode($date.'/'.$week_day).'&day='.$day.'.'.$month.'.'.$year.'">'.$week_day.'<br>'.$date.'</a></p></td>';
	   // создаем массив всех доставок за неделю
	   $query_dop = "SELECT `target` FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `date` = '".$date."' AND `status` = 'off'";
	   $result_dop = mysql_query($query_dop,$db);
	   if(mysql_num_rows($result_dop) > 0){
	       while($item_dop = mysql_fetch_assoc($result_dop)){
		       $target_arr[] = $item_dop['target'];
	        }		
	   }
   }
   $content.=  '</tr><tr><td><p>&nbsp;</p></td>';
   $colors_array = array('','#FFDAB9','#87CEEB','#66CDAA','#BDB76B','#EEDD82','#BC8F8F','#DEB887','#FA8072','#FFA500','#FF69B4','#EE82EE','#9370DB','#EED5B7','#CDAD00','#EEB4B4','#EEC591','#EE9A49','#EE8262','#FFA07A','#CD919E','#FF83FA','#AB82FF','#90EE90','#8968CD');
   $color_counter = 0 ;
   for($i=0; $i < $work_week ;$i++){
	   //if($day != 0){
          $date = date('d.m.y',mktime(0,0,0,$month,$day -(($karta->cur_week_day - 1) - $i),$year));
	   //}
	  // else{
	    //  $date = date('d.m.y',mktime(0,0,0,date('m'),date('d') -(($karta->cur_week_day - 1) - $i),date('Y')));
	    //// $date = date('d.m.y',mktime() - (($karta->cur_week_day - 1) - $i)*86400);
	  // }
	   $query = "SELECT*FROM `".DOSTAVKA_BIG_ROW_TBL."` WHERE `date` = '".$date."' AND `disable_editing` = '0'";
	   echo $query;
     
     $result = mysql_query($query,$db);
	   $content.=  '<td valign="top" height="72"><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#EEEEEE">';
	   if(mysql_num_rows($result) > 0){

			while($item = mysql_fetch_assoc($result)){
		       $tr_bg_color = ($item['status'] == 'on')? '#9DBF29' : '#FFFFFF' ;
			   // проверяем повотряется ли адрес в переделах  недели
			   if($item['status'] == 'off'){   
				   $counter = 0;
				   if(empty($q[$item['target']])){
					   for($j = 0 ; $j < count($target_arr) ; $j++ ){
						   if($item['target'] == $target_arr[$j]){
							   $counter++ ;
							   if($counter == 2){
									$q[$item['target']] = ++$color_counter;
									$tr_bg_color =  $colors_array[$q[$item['target']]]; 
									break;
							   }
						   }
					   }
				   }
				   else{
				       $tr_bg_color =  $colors_array[$q[$item['target']]]; 
				   }
		       }
	           $content.=  '<tr style="background-color:'.$tr_bg_color.';">
					 <td valign="top" onclick="show_details(event,\''.$item['id'].'\');"><div><p>'.$item['target'].'</p></div><div><p style="padding:0px 4px 4px 4px;">'.$item['date_delivery'].'&nbsp;</p></div></td>
                     </tr>';
	        }
		
	   }
	   //else $content.=  '<tr><td valign="top">&nbsp;</td></tr>';
	   $content.=  '</table></td>';

   }
   $content.=  '</tr></table>';
   




   // календарь
   //$cur_month_day_num = $karta->cur_month_day_num;
   $start = $karta->num_first_day_in_week_on_cur_month;
   //$start=3;
   $counter = $karta->cur_month_day_quantity + $start - 1;
   //echo $start;
   $start = -($start - 1) ;
   //echo $start;
   $calendar = '<table class="calendar"><tr><td><a href="?day='.$day.'.'.($month-1).'.'.$year.'"><<</a></td><td colspan="5" align="center">'.$karta->month_day_name_arr[$karta->cur_month_num].'</td><td><a href="?day='.$day.'.'.($month+1).'.'.$year.'">>></a></td><tr><td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td bgcolor="#FF6633">Сб</td><td bgcolor="#FF6633">Вс</td></tr>';
   for( $i = 1 ; $i <= $counter; $i++){
       $calendar .= '<td>';
     $start++;
     if($start > 0){
         $calendar .= '<a href="?day='.$start.'.'.$karta->cur_month_num.'.'.$karta->cur_year_num.'">'.$start.'</a>';
     }
     $calendar .= '</td>';
     if( $i%7 == 0 && $i < $counter)  $calendar .= '</tr><tr>';
   }
   //echo $counter%7; 
   //echo ' '.$karta->num_first_day_in_week_on_cur_month;
   if( $counter%7 != 0 ){
       
     for( $j = 1 ; $j <= 7-($counter%7); $j++){
       $calendar.= '<td>&nbsp;</td>';
     }
   }
   $calendar.= '</tr></table>';
  

 }else{
  ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);
  //поиск
  $MANAGERS = get_manager_name();
  $content = "<table id='result_search_tbl'>";
  $i=1;
  // $query = "SELECT*FROM `".DOSTAVKA_SMALL_ROW_TBL."` WHERE `actions` like '%".$_POST['search']."%' ";
  $query = "SELECT 
              `".DOSTAVKA_SMALL_ROW_TBL."`.`actions`,
              `".DOSTAVKA_SMALL_ROW_TBL."`.`date`, 
              `".DOSTAVKA_SMALL_ROW_TBL."`.`id_manager`, 
              `".DOSTAVKA_BIG_ROW_TBL."`.`target` AS `company`, 
              `".DOSTAVKA_BIG_ROW_TBL."`.`docs` 
            FROM `".DOSTAVKA_SMALL_ROW_TBL."` 
            INNER JOIN `".DOSTAVKA_BIG_ROW_TBL."` 
            ON `".DOSTAVKA_SMALL_ROW_TBL."`.`id_parent` = `".DOSTAVKA_BIG_ROW_TBL."`.`id`  
            ";
  
  $n = 0;
  if(isset($_POST['search']) && trim($_POST['search'])!=""){
    if($n>0){ $query .= " AND ";}else{$query .= ' WHERE';$n++;}
    $query .= "  `".DOSTAVKA_SMALL_ROW_TBL."`.`actions` like '%".$_POST['search']."%'";
  }

  if(isset($_POST['search_docs']) && trim($_POST['search_docs'])!=""){
    if($n>0){ $query .= " AND ";}else{$query .= ' WHERE';$n++;}
    $query .= " `".DOSTAVKA_BIG_ROW_TBL."`.`docs` like '%".$_POST['search_docs']."%'";
  }

  if(isset($_POST['search_company']) && trim($_POST['search_company'])!=""){
    if($n>0){ $query .= " AND ";}else{$query .= ' WHERE';$n++;}
    $query .= " `".DOSTAVKA_BIG_ROW_TBL."`.`target` like '%".$_POST['search_company']."%'";
  }

  // if($_SESSION['access']['access']=='5'){
  // echo "Ведутся работы";
  // exit;
  // }
  // echo "<pre>";
  // print_r($_POST);
  // echo "<pre>";
  // echo $query;
  $result = $mysqli->query($query) or die($mysqli->error);
  $arr = array();
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $arr[] = $row;
    }
  }
  foreach ($arr as $key => $value) {
    if($i==1){
    $content .= "<tr>
                  <th>№</th>
                  <th>Дата</th>
                  <th>Компания</th>
                  <th>Строка задания</th>
                  <th>Менеджер</th>
                </tr>";
    }
    $content .= "<tr>
                  <td>".$i."</td>
                  <td><a target='_blank' href='http://".$_SERVER['HTTP_HOST']."/dostavka_new/dostavka_podrobno.php?date=".$value['date']."'>".$value['date']."</a></td>
                  <td>".$value['company']."</td>
                  <td>".$value['actions']."".((trim($value['docs'])!="")?"<br><br><span style='font-size:12px'>Документы:</span><br> ".$value['docs']:"")."</td>
                  <td>".$MANAGERS[$value['id_manager']]."</td>
                  </tr>";
    $i++;
  }

  $content .= "</table>";
  if($i==1){
    $content = "
                <div style='font-size:14px; text-align:center; padding:150px 15px'>
                  По вашему запросу ничего не найдено.
                </div>
    ";

  }
 }
function get_manager_name(){
  global $mysqli;
  // MANAGERS_TBL
  $query = "SELECT * FROM `". MANAGERS_TBL ."`";
  $result = $mysqli->query($query) or die($mysqli->error);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $name[$row['id']] = $row['name'].' '.$row['last_name'];
    }
  }
  return $name;
}
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="dostavka_css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="index_js.js"></script>
</head>
<body>
<div id="show_details_div" style="position:absolute;top:200px;left:300px;border:#CCCCCC solid 1px;padding:4px;background-color:#FFFFFF;display:none;">
</div>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_top">
         <table class="page_top" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="180">
               <p>
			         <?php 
                   echo 'Сегодня: '.date('d.m.y.').'&nbsp;'.$karta->week_day_name_arr[date('l')];//.'&nbsp;'.$karta->cur_week_day.'&nbsp;'.$karta->num_first_day_in_week_on_cur_month.'&nbsp;'.$karta->back_cur_day.'&nbsp;__&nbsp;'.(date('j',mktime(0,0,0,$month,$day,$year))-1)*86400;
                   echo '<br>'; 
               ?>
               </p>
            </td>
            <td  width="0" align="center">
              <?php 
              if(isset($_POST['search'])) echo '<a onMouseOver="this.parentNode.style.backgroundColor = \'#D5D5D5\';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;" href="http://'.$_SERVER['HTTP_HOST'].'/dostavka_new/">назад</a>';
              ?>
            </td> 
            <td  width="20" align="center">&nbsp; 
                
            </td>     
            <td  width="100" align="center"> 
               <a href="#" onclick="javascript:window.print();return false;" onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">распечатать</a>
            </td> 
            <td  width="20" align="center">&nbsp;
                  
            </td> 
            <td width="510" align="center">
              <form method="POST" action="http://<?php echo $_SERVER['HTTP_HOST']; ?>/dostavka_new/">  
                <input type="text" name="search_company" placeholder="по компании">
                <input type="text" name="search" placeholder="по задаче">
                <input type="text" name="search_docs" placeholder="по документам">
                <input type="submit" value="Найти">
              </form>
            </td>
            <td  width="0" align="center">&nbsp; 
                
            </td> 
            <td width="0" align="center"> 
               
            </td>
            <td  width="0" align="center">&nbsp; 
                
            </td> 
            <td width="70" align="center"> 
               <a href="/"  onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">на сайт</a>
            </td>
	    <td  width="20" align="center">&nbsp; 
                
            </td>
	    <td width="100" align="center"> 
               <a href="../os/?page=cabinet&section=requests&subsection=no_worcked_men"  onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">онлайн сервис</a>
            </td>
            <td  width="20" align="center">&nbsp; 
                
            </td> 
            <td width="70" align="center"> 
               <a href="#"  onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';show_hide_div('kuriers_list');" onMouseOut="this.parentNode.style.backgroundColor = null;show_hide_div('kuriers_list')" style="display:block; height:23px;padding-top:8px;">курьеры</a>
               <div id="kuriers_list" style="position:absolute;display:none; background-color:#FFFFFF; text-align:left; padding:10px; border:solid #CCCCCC 1px; line-height:18px;">
                  Кирилл +79217775395
               </div>
            </td>
            <td>&nbsp;
                   
            </td>
            <td width="150" align="center"> 
               <a href="http://www.apelburg.ru/?out">выйти</a>
            </td>
          </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td style="padding:0px;">
	  <?php  

      echo  $content;
      echo  $calendar; 

    ?>
    
 
   </td>
  </tr>
</table>
</body>
</html>