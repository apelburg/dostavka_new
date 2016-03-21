<?php
if(mysql_num_rows($result) > 0){
	    while($item = mysql_fetch_assoc($result)){
		   $status = ($item['status'] == 'on')? 'checked' : '' ;
		   $status_value = ($item['status'] == 'on')? 'on' : 'off' ;
		   $options_list = '<option>&nbsp;</option>';
		   date_default_timezone_set('Europe/Moscow');   
		   for($i = -5 ; $i < 10 ; $i++ ){
		       if($date != date('d.m.y',time() + $i*84600)) $options_list .= '<option>'.date('d.m.y',time() + $i*84600).'</option>';
		   }
		   
			//строка
			$content.=  '<tr id="tr_for_id_'.$item['id'].'" rel="'.$item['id'].'">';
	       
			//_1_колонка
			$content.=  '<td id="data_td_1_'.$item['id'].'" height="50" rel="sort_order" align="center" ><p><span>'.++$count.' </span></p></td>';
		   
			//_2_колонка
			$content.= '<td id="data_td_3_'.$item['id'].'" height="50" width="25" ><p align="center"><input type="text" style="width:60px; display:none" class="datepicker" name="'.$item['id'].'" data-date="'.substr($_GET['date'], 0, 8).'" ></p></td>';
		   
			//_3_колонка
			$red_class = (trim($item['target']) == '')?" red_class":"";
			$content.= '<td valign="top" data-id="'.$item['id'].'" data-name="target" class="redactorTD '.$red_class.'" onclick="changeTypeCell(this);">'.$item['target'].' </td>';
		   
			//_4_колонка
			$content.= '<td valign="top">';
			
			//**** START записи юзеров ****	
			//начало таблицы из DIV(ов)		
			$content .='<div class="table" style="height:100%">';
						unset($zet);
						$s_checked = 0;
						$s_row = 0;
					    foreach ($task as $key => $value){		
							if(@$value['id_parent']== $item['id']){
								if($_SESSION['access']['access']==7 || $_SESSION['access']['access']==8 || $_SESSION['access']['user_id']==$value['id_manager'] || $_SESSION['access']['access']=='1'){
									//редактируемые строки
									$s_row++;									
									//строка
									$content .= '<div class="row">';
									
									//колонка _1_
									$content .= '<div class="cell ';
									if($value['give_take']!='' && $value['give_take']=='give'){
									$content .= 'giveClass';	
									}else if($value['give_take']!='' && $value['give_take']=='take'){
									$content .= 'takeClass';	
										}
									$content .= '" style="vertical-align:top; width:30px; cursor:pointer;';
									if($value['status_task']>0){
									$content .= 'background:url(img/check.png) no-repeat"  onClick="checkS(this)" ><input type="checkbox" onClick="endThis(this)" name="'.$value['id'].'"  style="display:none" class="statusTask" checked>';	
									$s_checked++;
										}else{
									$content .= 'background:url(img/unch.png) no-repeat" onClick="checkS(this)" ><input type="checkbox" onClick="endThis(this)" name="'.$value['id'].'"  style="display:none" class="statusTask" >';	
											}
									$content .='</div>';
									
									//колонка _2_
									$content .='<div class="cell" style="width:340px; "><textarea style=\'font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px; background:none; width:95%; \' name="" class="action_text" id="task_'.$value['id'].'" onKeyDown="textareaChangeMin(this)" onKeyPress="textareaChangeMin(this)" onKeyUp="textareaChangeMin(this)">'.$value['actions'].'</textarea><div style="width:100%; float:left:">'.$value['date_log'].'</div></div>';
									//колонка _3_
									if($value['second_point']!='на про-во' && $value['second_point']!='со склада' && $value['second_point']!='с про-ва' && $value['second_point']!='из офиса' && $value['second_point']!='на склад' && $value['second_point']!='в офис'){
									
									$content .= '<div class="cell" onClick="changeTypeCell(this,\'addRow_point\')" data-name="second_point" data-id="'.$value['id'].'" style="width:80px; cursor:pointer; vertical-align:top; text-align:left; font-size:14px; color:grey">'.$value['second_point'].'</div>';	
									}else{
										
									$content .= '<div class="cell" style="width:80px; vertical-align:top; text-align:left; font-size:14px; color:grey">'.$value['second_point'].'</div>';
									}
									
									//колонка _4_
									$create_time = ((isset($value['create_time']) && $value['create_time'] != '0000-00-00 00:00:00')?'<br>'.date('d.m.Y H:i',strtotime($value['create_time'])):"");
									$content .='<div class="cell" style="vertical-align:top;width:90px;"><div style="float:left;width:100px;">'.$value['name'].' '.mb_substr($value['last_name'], 0, 2).'.'.$create_time.'</div></div>';
									//колонка _5_
									$content .='<div class="cell" onClick="cleanThisePosition(this)" style="vertical-align:top; cursor:pointer; padding:5px;"><div class="BtnAddOrDel" style="padding: 0 5px 0 5px;"><img src="img/del2.png" style="margin:0px 5px 0 0; "></div></div>
									</div>';								
									}else{
										
									//нередактируемые строки									
									//начало строки
									$content .= '<div class="row">';
									
									//колонка _1_
									$content .= '<div class="cell ';
									if($value['give_take']!='' && $value['give_take']=='give'){
									$content .= 'giveClass';	
									}else if($value['give_take']!='' && $value['give_take']=='take'){
									$content .= 'takeClass';	
										}
									$content .= '" style="vertical-align:top; width:30px; cursor:pointer;';
									if($value['status_task']>0){
									$content .= 'background:url(img/check.png) no-repeat"  onClick="checkS(this)" ><input type="checkbox" onClick="endThis(this)" name="'.$value['id'].'"  style="display:none" class="statusTask" checked>';	
									$s_checked++;
										}else{
									$content .= 'background:url(img/unch.png) no-repeat" onClick="checkS(this)" ><input type="checkbox" onClick="endThis(this)" name="'.$value['id'].'"  style="display:none" class="statusTask" >';	
											}
									$content .='</div>';
									
									//колонка _2_
									$content .= '<div class="cell" style="width:80%;"><div style=\'border:none;font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px; width:95%; height:95%\'>'.$value['actions'].'</div><div style="width:100%; float:left:">'.$value['date_log'].'</div></div>';
									//колонка _3_
									if($value['second_point']!='на про-во' && $value['second_point']!='со склада' && $value['second_point']!='с про-ва' && $value['second_point']!='из офиса' && $value['second_point']!='на склад' && $value['second_point']!='в офис'){
									
									$content .= '<div class="cell" onClick="chenge_this_point(this)" data-id_small_row="'.$value['id'].'" style="width:100px;">'.$value['second_point'].'</div>';	
									}else{
									$content .= '<div class="cell" style="width:80px;">'.$value['second_point'].'</div>';
									}
									//колонка _4_
									$create_time = ((isset($value['create_time']) && $value['create_time'] != '0000-00-00 00:00:00')?'<br>'.date('d.m.Y H:i',strtotime($value['create_time'])):"");
									$content .= '<div class="cell" style="width:90px;">'.$value['name'].' '.mb_substr($value['last_name'], 0, 2).'.'.$create_time.'</div>';
									//колонка _5_
									$content .= '<div class="cell" style="padding:5px;"><div style="padding: 0 5px 0 5px;"></div></div></div>';
									$s_row++;
								}								
							}
						}
						
						//строка - общий checkbox + ссылка добавить
						$content .='<div class="row">';
						if($s_row>0 && $s_row==$s_checked){//строки есть и все выделены
							$content .='<div class="cell" style="background:url(img/check.png) no-repeat; cursor:pointer;  border-right:1px solid #ddd" onClick="checkS(this)"> <input type="checkbox" class="checking_all" onClick="checkAllRow(this)"  style="display:none" checked></div>';
						}else if($s_row>0 && $s_row!=$s_checked && $s_checked>0){//строки есть и не все выделены
							$content .='<div class="cell" style="background:url(img/minus.png) no-repeat; cursor:pointer; border-right:1px solid #ddd;" onClick="checkS(this)"> <input type="checkbox" class="checking_all" onClick="checkAllRow(this)"  style="display:none" ></div>';///8888888
						}else if($s_row>0 && $s_checked==0){//строки есть и ниодна невыделена 
							$content .='<div class="cell" style="background:url(img/unch.png) no-repeat; cursor:pointer; border-right:1px solid #ddd;" onClick="checkS(this)"> <input type="checkbox" class="checking_all" onClick="checkAllRow(this)"  style="display:none" ></div>';///8888888
						}else{//строк нет
							$content .='<div class="cell" style="background:none; cursor:pointer; border-right:1px solid #ddd; width:30px;"> <input type="checkbox" class="checking_all" onClick="checkAllRow(this)"  style="display:none" ></div>';///8888888
							}						
						$content .='<div class="cell"><div onClick="addRowSmall(this)" class="BtnAddOrDel greenHover" style="padding: 0 5px 2px 5px; margin:10px 5px 5px 5px; float:left;"><img src="img/add2.png" style="margin:0px 5px 0 0; float:left"></div></div>';
						$content .='<div class="cell"></div><div class="cell"></div></div></div>';
						
					//**** END записи юзеров ****	
					$content .='</td>';
					
					//_5_колонка
					$red_class = (trim($item['docs']) == '')?" red_class":"";
					$content .='<td valign="top" data-id="'.$item['id'].'" class="redactorTD '.$red_class.'" data-name="docs" onclick="changeTypeCell(this);">'.nl2br($item['docs']).'</td>';
					//_6_колонка
					$red_class = (trim($item['date_delivery']) == '')?" red_class":"";
					$content .='<td valign="top" data-id="'.$item['id'].'" class="redactorTD '.$red_class.'" data-name="date_delivery"  onclick="changeTypeCell(this);">'.nl2br($item['date_delivery']).'</td>';
					//_7_колонка
					$red_class = (trim($item['contacts']) == '')?" red_class":"";
					$content .='<td valign="top" data-id="'.$item['id'].'" class="redactorTD '.$red_class.'" data-name="contacts"  onclick="changeTypeCell(this);">'.nl2br($item['contacts']).'</td>';
					//_8_колонка
					$content .='<td id="data_td_4_'.$item['id'].'" align="center">
				<input name="form_data[id]" type="hidden" value="'.$item['id'].'">
				<input name="change_data" type="submit" id="submit_'.$item['id'].'" value="изменить" style="display:none;">
				<input name="'.$item['id'].'" type="button" style=" background:url(img/del.png) no-repeat; width: 30px; height:30px; border:none; cursor:pointer" value="" onClick="delBigRow(this)" style="width:60px;">
					</td>
				  </tr>';
	    }
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="dostavka_new_css.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.datepicker.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jquery-1.10.2.js"></script>
<script type="text/javascript" src="jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="jquery.tablednd.0.5.js"></script>
<script type="text/javascript" src="jq_js.js"></script>
<script type="text/javascript" src="dostavka_js.js"></script>
<script type="text/javascript" src="autogrow-textarea.js"></script>
<script src="jQueryAssets/jquery-ui-1.9.2.datepicker.custom.min.js" type="text/javascript"></script>
<script language="javascript"> 

</script>
</head>
<body>
<div style="background:red; opacity:1; position:fixed; top:0; left:0; width:100% ; height:100%; display:none;"></div>
<div style="background:url(img/301.gif) no-repeat; width:128px;display:none;height:128px; position:fixed; top:50%; left:50%; margin:-64px 0 0 -64px"></div>
<div id="myName" style="display:none"><?php echo $_SESSION['user_name']; ?></div>
<table width="100%"  cellspacing="0" cellpadding="0">
  <tbody>
    <tr >
      <td><table class="page_top"  width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="230"><p>
                <?php 
                   echo 'Сегодня: '.date('d.m.y.').'&nbsp;'.$karta->cur_week_day_name_rus;
                   echo '<br/>'; 
               ?>
              </p></td>
            <td  width="90" align="center"><a href="#" onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">сохранить</a></td>
            <td  width="20" align="center">&nbsp;</td>
            <td  width="100" align="center"><a href="/dostavka_new/dostavka_podrobno.php?for_print&date=<?php  echo $_GET['date'] ?>" target="_blank" onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">распечатать</a></td>
            <td  width="20" align="center">&nbsp;</td>
            <td width="510" align="center">
              <form method="POST" action="http://<?php echo $_SERVER['HTTP_HOST']; ?>/dostavka_new/">  
                <input type="text" name="search_company" placeholder="по компании">
                <input type="text" name="search" placeholder="по задаче">
                <input type="text" name="search_docs" placeholder="по документам">
                <input type="submit" value="Найти">
              </form>
            </td>
            <td  width="0" align="center">&nbsp;</td>
            <td width="0" align="center"></td>
            <td  width="0" align="center">&nbsp;</td>
            <td width="70" align="center"><a href="/"  onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">на сайт</a></td>
            <td  width="20" align="center">&nbsp;</td>
            <td width="100" align="center"><a href="../admin/order_manager/?page=clients&show_clients=all"  onMouseOver="this.parentNode.style.backgroundColor = '#D5D5D5';" onMouseOut="this.parentNode.style.backgroundColor = null;" style="display:block; height:23px;padding-top:8px;">онлайн сервис</a></td>
            <td  width="20" align="center">&nbsp;</td>
            <td width="20" align="center"></td>
            <td>&nbsp;</td>
            <td width="100" align="center"><a href="http://www.apelburg.ru/?out">выйти</a></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td style="border:none;"><table width="900" id="myTable" border="1" cellpadding="0" cellspacing="0" align="center">
          <tr class="nodrop nodrag">
            <td width="55" height="40" class="table_top"><p>&nbsp;</p></td>
            <td colspan="2" width="70" align="center" style=" background-color:#EEEEEE;"><a href="/dostavka_new/index.php?day=<?php  echo $day.'.'.$month.'.'.$year; ?>" style="display:block;font-size:14px;height:30px;padding-top:6px;" onMouseOver="this.parentNode.style.backgroundColor='#D5D5D5'"  onMouseOut="this.parentNode.style.backgroundColor='#EEEEEE'">сводная карта</a></td>
            <td colspan="5" class="table_top"><table width="100%"  class="table_without_border"  style="margin-left:10px;" cellspacing="0" cellpadding="0">
                <tr class="nodrop nodrag">
                  <td width="20"><button onclick="location = 'dostavka_podrobno.php?date=<?php  echo urlencode(date('d.m.y',mktime(0,0,0,$inside_date[1],($inside_date[0] - 1),$inside_date[2])).'/'.$karta->week_day_name_arr[date('l',mktime(0,0,0,$inside_date[1],($inside_date[0] - 1),$inside_date[2]))]); ?>&day=<?php  echo $karta->back_cur_day_short ; ?>';" class="day_switch_by_order_button"><<</button></td>
                  <td width="240"><p> карта на:
                      <?php  echo $date.' '.$name_cur_day ; ?>
                    </p></td>
                  <td width="20"><button onclick="location = 'dostavka_podrobno.php?date=<?php  echo urlencode(date('d.m.y',mktime(0,0,0,$inside_date[1],($inside_date[0] + 1),$inside_date[2])).'/'.$karta->week_day_name_arr[date('l',mktime(0,0,0,$inside_date[1],($inside_date[0] + 1),$inside_date[2]))]); ?>&day=<?php  echo $karta->back_cur_day_short ; ?>';" class="day_switch_by_order_button">>></button></td>
                  <td style="padding-left:15px;"><strong><span style="color:red;">ВНИМАНИЕ!!!</span> <br/>
График поездок в Проект111, Интерпрезент, Оазис:<br/>

1.   Интерпрезент – едем только в среду и пятницу (т.к. машина из Москвы приходит во вторник и четверг)<br/>
2.   Проект111, Оазис  - ездим туда по пн,ср,пт</strong></td>
                </tr>
              </table></td>
          </tr>
          <tr class="nodrop nodrag">
            <td class="table_top"><p>№</p></td>
            <td class="table_top"><p>дата</p></td>
            <td class="table_top" width="105px"><p>Компания</p></td>
            <td class="table_top" width="650px"><p>№/Забрать-Отдать(что и куда везти)/Задача</p></td>
            <td class="table_top"><p>Документы</p></td>
            <td class="table_top" width="70px"><p>Время</p></td>
            <td class="table_top" width="200px"><p>Адреc/Контактное<br/>
                лицо/Телефон</p></td>
            <td class="table_top"><p>удалить</p></td>
          </tr>
          <?php  echo  $content; ?>
          <tr class="nodrop nodrag" id="endBigRow">
            <td width="25" height="50" align="center"><input type="hidden" style="width:15px;border:none;paddding:0;margin:0;float:left" name="form_data[num_rows]" value="<?php  echo  ++$count; ?>">
              <p><span>
                <?php  //echo  $count; ?>
                </span></p></td>
            <td align="center"></td>
            <!--<td align="center" onClick="selectTheAdress(this)" id="add_address"><input name="add_data" type="button" id="addBigRow" style=" background:url(img/add.png) no-repeat; width:30px; height:30px; border:none; cursor:pointer" value=""></td>-->
            <td align="center" id="add_new_address_id" onClick="add_new_address(this)"><input name="add_data" type="button" style=" background:url(img/add.png) no-repeat; width:30px; height:30px; border:none; cursor:pointer" value=""></td>
            <td valign="top" id="add_actions"></td>
            <td valign="top" id="add_docs" class="redactorTD" onclick="changeTypeCell(this, 'addRow')"></td>
            <td valign="top" id="add_date_delivery" class="redactorTD" onclick="changeTypeCell(this, 'addRow')"></td>
            <td valign="top" id="add_contacts" class="redactorTD" onclick="changeTypeCell(this, 'addRow')"></td>
            <td valign="top"><input type="hidden" id="add_num_rows" value="<?php  echo  $count; ?>"></td>
          </tr>
          <tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<div id="bg"></div>
<div id="loading"></div>
<input type="hidden" id="dateToDay" value="<?php echo $date; ?>">
<input type="hidden" id="userId" value="<?php echo $_SESSION['access']['user_id']; ?>">
<input type="hidden" id="crops" value="<?php echo $_SESSION['access']['access']; ?>">
<div style="display:none"> 
  <!-- шаблоны для javascript -->
  <div id="give_menu_prev"> 
    <!-- -->
    <div data-menu-of="give">
      <div class="selectTheAdresData" onClick="newSmallrow(this)">со склада</div>
      <div class="selectTheAdresData" onClick="newSmallrow(this)" >с про-ва</div>
      <div class="selectTheAdresData" onClick="newSmallrow(this)" >из офиса</div>
      <div class="selectTheAdresData" onClick="newSmallrow(this)" >другое...</div>
    </div>
  </div>
  <!-- -->
  <div id="take_menu_prev">
    <div data-menu-of="take">
      <div class="selectTheAdresData" onClick="newSmallrow(this)" >на склад</div>
      <div class="selectTheAdresData" onClick="newSmallrow(this)" >на про-во</div>
      <div class="selectTheAdresData" onClick="newSmallrow(this)" >в офис</div>
      <div class="selectTheAdresData" onClick="newSmallrow(this)" >другое...</div>
    </div>
  </div>
  <!-- -->
  <div id="template_add_min_row">
    <div class="row">
      <div class="cell" style=" vertical-align:top; width:30px;border-right:1px solid #dddddd; cursor:pointer;">
        <input type="checkbox" onClick="endThis(this)" class="statusTask" style="display:none">
      </div>
      <div class="cell" style="width: 390px;">
        <div class="Btngiv_take" onclick="AddOrDel(this)" data-com="take">забрать</div>
        <div class="Btngiv_take" onclick="AddOrDel(this)" data-com="give">отдать</div>
        <textarea class="small_row_txt" onfocus="moveCursorToEnd(this)" style="width: 95%; height:7px" name="" onKeyDown="textareaChangeMin(this)" onKeyPress="textareaChangeMin(this)" onKeyUp="textareaChangeMin(this)"></textarea>
      </div>
      <div class="cell" style="vertical-align:top;width:80px;font-size:14px; color:#808080; padding:0"></div>
      <div class="cell" style="vertical-align:top;width:100px;">
        <div style="float:left"><?php 
				$arr = explode(" ", $_SESSION['user_name']);
				
				echo $arr[0].' '.mb_substr($arr[1], 0, 2).'.'; ?></div>
      </div>
      <div class="cell" style="vertical-align:top; cursor:pointer; padding:5px; width:35px;">
        <div class="BtnAddOrDel" onClick="delMyDOMSmallRow(this)" id="delMyMinRow" style=" font-size:12px; padding: 0 5px 0 5px;"> <img src="img/del2.png" style="margin:0px 5px 0 0; "> </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	$(function(){
		$( ".datapicker" ).datepicker();
	});
</script>
</body>
</html>