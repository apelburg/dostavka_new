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
			$content.=  '<tr id="tr_for_id_'.$item['id'].'">';
	       
			//_1_колонка
			$content.=  '<td id="data_td_1_'.$item['id'].'" height="50" rel="sort_order" align="center" ><p><span>'.++$count.' </span></p></td>';
		   
			//_2_колонка
			$content.= '<td valign="top" data-id_parent="'.$item['id'].'" class="redactorTD" onclick="display_big_window(this)">'.($item['target']).'</td>';
		   
			//_3_колонка
			$content.= '<td valign="top">';
			
			//**** START записи юзеров ****	
			//начало таблицы из DIV(ов)		
			$content .='<div class="table" style="height:100%;width:100%">';
						unset($zet);
						$s_checked = 0;
						$s_row = 0;
					    foreach ($task as $key => $value){		
							if($value['id_parent']==$item['id']){
								
									//редактируемые строки
									$s_row++;									
									//строка
									$content .= '<div class="row" onClick="check_driver(this)">';
									
									//колонка _1_
									$content .= '<div class="cell ';
									if($value['give_take']!='' && $value['give_take']=='give'){
									$content .= 'giveClass';	
									}else if($value['give_take']!='' && $value['give_take']=='take'){
									$content .= 'takeClass';	
										}
									$content .= '" style="vertical-align:top; width:30px; cursor:pointer;';
									if($value['status_task']>0){
									$content .= 'background:url(img/check.png) no-repeat"   ><input type="checkbox" onClick="endThis(this)" name="'.$value['id'].'"  style="display:none" data-id_parent="'.$item['id'].'" class="statusTask" checked>';	
									$s_checked++;
										}else{
									$content .= 'background:url(img/unch.png) no-repeat"  ><input type="checkbox" onClick="endThis(this)" name="'.$value['id'].'"  style="display:none" data-id_parent="'.$item['id'].'" class="statusTask" >';	
											}
									$content .='</div>';
									
									//колонка _2_
									$content .= '<div class="cell" style="width:250px;"><div style=\'border:none;font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px; width:95%;font-size:12px;padding:5px; \'>'.$value['actions'].'</div><div style="width:100%; float:left;">'.$value['date_log'].'</div></div>';
									//колонка _3_
									$content .= '<div class="cell" colspan="2" style="vertical-align:top; text-align:left; font-size:12px; color:grey">'.$value['second_point'].'<br/><span  style=" width:40px; color:#000;font-size:12px;">'.$value['name'].'<br/> '.$value['last_name'].'</span></div>';
									//колонка _4_
									$content .= '';
									//колонка _5_
									$content .= '</div>';
									$s_row++;									
							}
						}
						
						//строка - общий checkbox + ссылка добавить
						$content .='<div class="row" style=" display:none">';
						if($s_row>0 && $s_row==$s_checked){//строки есть и все выделены
							$content .='<div class="cell" style="background:url(img/check.png) no-repeat; cursor:pointer;  border-right:1px solid #ddd" > <input type="checkbox" class="checking_all" onClick="checkAllRow(this)"  style="display:none" checked></div>';
						}else if($s_row>0 && $s_row!=$s_checked && $s_checked>0){//строки есть и не все выделены
							$content .='<div class="cell" style="background:url(img/minus.png) no-repeat; cursor:pointer; border-right:1px solid #ddd;" > <input type="checkbox" class="checking_all" onClick="checkAllRow(this)"  style="display:none" ></div>';///8888888
						}else if($s_row>0 && $s_checked==0){//строки есть и ниодна невыделена 
							$content .='<div class="cell" style="background:url(img/unch.png) no-repeat; cursor:pointer; border-right:1px solid #ddd;" > <input type="checkbox" class="checking_all" onClick="checkAllRow(this)"  style="display:none" ></div>';///8888888
						}else{//строк нет
							$content .='<div class="cell" style="background:url(img/no_active.png) no-repeat; cursor:pointer; border-right:1px solid #ddd; width:30px;"> <input type="checkbox" class="checking_all" data-id_parent="'.$item['id'].'" onClick="checkAllRow(this)"  style="display:none" ></div>';///8888888
							}						
						$content .='<div class="cell"><div class="BtnAddOrDel greenHover" style="padding: 0 5px 2px 5px; opacity:0; margin:10px 5px 5px 5px; float:left; cursor:default;"><img src="img/add2.png" style="margin:0px 5px 0 0; float:left">Новая запись</div></div>';
						$content .='<div class="cell"></div><div class="cell"></div><div class="cell"></div></div></div>';
						
					//**** END записи юзеров ****	
					$content .='</td>';
					
					//_5_колонка
					$content .='<td valign="top" data-id="'.$item['id'].'" class="redactorTD" data-name="docs" onclick="changeTypeCell(this);">'.nl2br($item['docs']).'</td>';
					//_6_колонка
					$content .='<td valign="top" data-id="'.$item['id'].'" class="redactorTD" data-name="date_delivery"  onclick="changeTypeCell(this);">'.nl2br($item['date_delivery']).'</td>';
					//_7_колонка
					$content .='<td valign="top" data-id="'.$item['id'].'" class="redactorTD" data-name="contacts"  onclick="changeTypeCell(this);">'.str_replace('<br>','',nl2br($item['contacts'])).'</td>';
					//_8_колонка
					/*$content .='<td id="data_td_4_'.$item['id'].'" align="center" width="0">
				<input name="form_data[id]" type="hidden" value="'.$item['id'].'">
				<input name="change_data" type="submit" id="submit_'.$item['id'].'" value="изменить" style="display:none;">
				<input name="'.$item['id'].'" type="button" style="display:none;">
					</td>
				  </tr>';*/
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
<script type="text/javascript" src="dostavka_dr_js.js"></script>
<script language="javascript"> 

</script>
</head>
<body>
<div id="no_windows">


<div style="background:red; opacity:1; position:fixed; top:0; left:0; width:100% ; height:100%; display:none;"></div>
<div style="background:url(img/301.gif) no-repeat; width:128px;display:none;height:128px; position:fixed; top:50%; left:50%; margin:-64px 0 0 -64px"></div>


<div id="myName" style="display:none"><?php echo $_SESSION['user_name']; ?></div>
<table width="100%"  cellspacing="0" cellpadding="0">
  <tbody>
    <tr >
      <td>
      	<table class="page_top"  width="100%" cellspacing="0" cellpadding="0">
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
        </table>
    	</td>
    </tr>
    <tr>
      <td style="border:none;"><table id="myTable" border="1" style="width:100%;" cellpadding="0" cellspacing="0" align="center">
          <tr class="nodrop nodrag">
            <td width="20" height="40" class="table_top"><p>&nbsp;</p></td>
            <td width="60" align="center" style=" background-color:#EEEEEE;"><a href="/dostavka/index.php?day=<?php  echo $day.'.'.$month.'.'.$year; ?>" style="display:block;font-size:14px;height:30px;padding-top:6px; height:40px" onMouseOver="this.parentNode.style.backgroundColor='#D5D5D5'"  onMouseOut="this.parentNode.style.backgroundColor='#EEEEEE'">сводная карта</a></td>
            <td colspan="4" class="table_top"><table width="280"  class="table_without_border"  style="margin-left:10px;" cellspacing="0" cellpadding="0">
                <tr class="nodrop nodrag">
                  <td width="20"><button onclick="location = 'dostavka_podrobno.php?date=<?php  echo urlencode(date('d.m.y',mktime(0,0,0,$inside_date[1],($inside_date[0] - 1),$inside_date[2])).'/'.$karta->week_day_name_arr[date('l',mktime(0,0,0,$inside_date[1],($inside_date[0] - 1),$inside_date[2]))]); ?>&day=<?php  echo $karta->back_cur_day_short ; ?>';" class="day_switch_by_order_button"><<</button></td>
                  <td><p> карта на:
                      <?php  echo $date.' '.$name_cur_day ; ?>
                    </p></td>
                  <td width="20"><button onclick="location = 'dostavka_podrobno.php?date=<?php  echo urlencode(date('d.m.y',mktime(0,0,0,$inside_date[1],($inside_date[0] + 1),$inside_date[2])).'/'.$karta->week_day_name_arr[date('l',mktime(0,0,0,$inside_date[1],($inside_date[0] + 1),$inside_date[2]))]); ?>&day=<?php  echo $karta->back_cur_day_short ; ?>';" class="day_switch_by_order_button">>></button></td>
                </tr>
              </table></td>
          </tr>
          <tr class="nodrop nodrag">
            <td class="table_top" style="width:10px;"><p>№</p></td>
            <td class="table_top" width="105px"><p>Компания</p></td>
            <td class="table_top" width="650px"><p>№/Забрать-Отдать(что и куда везти)/Задача</p></td>
            <td class="table_top"><p>Документы</p></td>
            <td class="table_top" width="70px"><p>Время</p></td>
            <td class="table_top" width="200px"><p>Адре/Контактное лицо/Телефон</p></td>
          </tr>
          <?php  echo  $content; ?>
          
           
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


</div>
<div id="window_big_size">
	<div id="big_name" class="big_head">
    	
    </div>
    <div id="big_point" class="big_body">Забрать образцы в офис</div>
    
    <div style="width:48%; float:left; padding-bottom:5px">
        <div class="big_head2" style="width:120px">Документы:</div>
        <div id="big_docs" style="float:left; margin-left:20px;" class="big_body">Апп</div>
    </div>
    <div style="width:48%;float:left; padding-bottom:5px">
        <div class="big_head2" style="width:90px">Время: </div>
        <div id="big_time" style="float:left; margin-left:20px;" class="big_body">в любое время</div>
    </div>
	
    
    <div class="big_head2">Контакты</div>
    <div id="big_contacts" class="big_body">Свердловская наб. д. 62, тел 998-25-95, кон.лицо Дима, Игорь</div>
</div>

</body>
</html>