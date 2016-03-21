// JavaScript Document.
//добавление строки в базу и превращение в редактируемую, замена служебного id
function newSmallrow(i){
	var id_parent = $('#adressOfTheRow').data('id_parent');
	var give_take = $('#adressOfTheRow').data('com');
	var id_manager = $('#userId').val();
	var date = $('#dateToDay').val();
	var point_start = $(i).html();
	
	if(give_take == 'give')var com = 'Отдать ';
	if(give_take == 'take')var com = 'Забрать ';
	
	$('#adressOfTheRow, #bg').hide();
	$('#adressOfTheRow').remove();
	$('div.BtnAddOrDel.greenHover').css({'opacity':'1'}).attr('onClick','addRowSmall(this)');
	$('#add_new_address_id').css({'opacity':'0.3'}).attr('onClick','add_new_address(this)');
	//alert('id_parent = '+id_parent+'\nid_manager = '+id_manager+'\ndate = '+date+'\ngive_take = '+give_take+'\nответ ajax = ');return;
	$.post("ajax_func.php",//PHP обработчик запроса
		{ 		
			name: "kk_task", 
			id_parent: id_parent,
			id_manager: id_manager,
			date: date,
			actions: com,
			give_take: give_take,
			point_start: point_start
		
		},
		function(data){
				if(!$.isNumeric(data)){alert('Ошибка #00004: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
				$('#added_small_row_new').find('.cell:nth-of-type(1)').attr('onClick','checkS(this)').css({'background':'url("img/unch.png") no-repeat'});
				greeen_table();
				$('#added_small_row_new').find('.Btngiv_take').remove();
				$('#added_small_row_new').find(':checkbox').attr('name',data);
				$('#added_small_row_new').find('textarea').val(com).show().attr('id','task_'+data).attr('class', 'action_text');
				$('#added_small_row_new').find('.cell:nth-of-type(3)').html($(i).html());
				//$('#added_small_row_new').attr('id','small_row_'+data);
				$('#added_small_row_new').removeAttr('id');
				
		});
}

//открытие меню2 (забрать отдать)
function AddOrDel(i){
	//alert($(i).parent().parent().parent().parent().find('input[name="form_data[id]"]').val());return;
			//  alert($(i).parent().parent().parent().parent().html())
	var id_parent = $(i).parent().parent().parent().parent().parent().find('input[name="form_data[id]"]').val();
	var give_take = $(i).data('com');
	
	$('#'+give_take+'_menu_prev').children().attr('id','adressOfTheRow');
	$('body').append($('#'+give_take+'_menu_prev').html());
	$('#'+give_take+'_menu_prev').children().removeAttr('id');
	$('#adressOfTheRow').animate({opacity:1},300).show();
	$('#bg').fadeIn().css({'background':'#000'});
	$('#adressOfTheRow').attr('data-id_parent',id_parent);
	$('#adressOfTheRow').attr('data-com',give_take);
	
	$(i).parent().next().next().next().attr('onClick','cleanThisePosition(this)').find('.BtnAddOrDel').fadeIn('slow');//добавляем onclick удаления строкии, показываем кнопку
	
	//alert(id_parent+' '+date+' '+give_take);
	return;
	
}
	
//добавить строку и предложить выбор: забрать/отдать (РАБОТАЕТ)
function addRowSmall(i){
	//alert($(i).parent().parent().html())
	$(i).parent().parent().animate({margin: '59px 0 0 0' },0, function(){
		$(i).parent().parent().parent().css({'padding-top': 0});
		$('#template_add_min_row').children().attr('id','added_small_row_new');//присваиваем служебный id для добавляемого поля
		$(i).parent().parent().before($('#template_add_min_row').html());
		$('#template_add_min_row').children().removeAttr('id');
		});
	$(i).parent().parent().parent().parent().parent().find('td').each(function(index, element) {
		$(this).css({'background':'none'});
	});	
	//$('div.BtnAddOrDel.greenHover').css({'opacity':'1'}).attr('onClick','addRowSmall(this)');
	$('div.BtnAddOrDel.greenHover').css({'opacity':'0.3'}).attr('onClick','alert("закончите выбор забрать/отдать");');
	$('#add_new_address_id').css({'opacity':'0.3'}).attr('onClick','alert("закончите выбор забрать/отдать");');
}




//изменение обычного поля на редактироумое (РАБОТАЕТ)
function changeTypeCell(i,type){
	if($(i).find('textarea').length<1){
		var html = $(i).html();
		html = $.trim(html);		
		html = html.replace(/\<br\>/g, "\n").replace(/\<br \/\>/g, "\n");
		$(i).css({'padding':'0'});
		if(type!='addRow'){
			$(i).html('<textarea onBlur="blurText(this)" onKeyDown="textareaChange(this)" onKeyPress="textareaChange(this)" onKeyUp="textareaChange(this)" class="redactor_textarea" style="width:'+(+$(i).width()-12)+'px; heyght:100%;overflow: hidden; border:1px solid #92B740; padding:5px;">'+html+'</textarea>');
		}else{
			$(i).html('<textarea onBlur="blurText(this)" class="redactor_textarea" style="width:'(+$(i).width()-12)+'px; heyght:100%;overflow: hidden; border:1px solid #92B740; padding:5px;">'+html+'</textarea>');	
		}		
		$(i).find('textarea').focus();
		$('textarea').autogrow();
	}
}

//убираем поле ввода при потере фокуса (РАБОТАЕТ)
function blurText(i){
	var text_val = $(i).val();
	$(i).parent().html(text_val).css({'padding':'5px'});
}

//подсветка зеленым сразу после загрузки страницы
$(function() {
		greeen_table();
});
////дубль того же кода в функции /////
function greeen_table(){
	$('.table').each(function(index, element) {
        if($(this).find(':checkbox.statusTask').length>0 && $(this).find(':checkbox.statusTask').length==$(this).find(':checkbox.statusTask:checked').length){
			$(this).parent().parent().find('td').each(function(index, element) {
                $(this).css({'background-color':'#CDFAB1'});
            });
			}else if($(this).find(':checkbox.statusTask:checked').length>0 && $(this).find(':checkbox.statusTask').length!=$(this).find(':checkbox.statusTask:checked').length){
				$(this).find('.row:last-child .cell:nth-of-type(1)').css({"background": "url(img/minus.png) no-repeat"});
				//$(this).find('.row:last-child .cell:nth-of-type(1) input:checkbox').prop('checked', false);
			}else{
			$(this).parent().parent().find('td').each(function(index, element) {
                $(this).css({'background':'none'});
            });
			//$(this).find('.row:last-child .cell:nth-of-type(1)').css({"background": "url(img/unch.png) no-repeat"});
			}
		$(this).find('.checking_all').parent().parent().css({'background':'none'});
		$(this).find(':checkbox.statusTask:checked').each(function(index, element) {
            $(this).parent().parent().css({'background-color':'#CDFAB1'});
        });
    });	
}
/////////
//клик по checkbox внутри (OK)
function checkS(i){
	$(i).children(':checkbox').click();
	if($(i).children(':checkbox').prop('checked')!=true){
			$(i).css({'background':'url(img/unch.png) no-repeat'});
		}else{
			$(i).css({'background':'url(img/check.png) no-repeat'});
		}
	greeen_table();
//	alert($(i).children().attr('name'));
}	

//выделяем все :checkbox
function checkAllRow(i){
	if($(i).prop('checked') == true){
		$(i).parent().parent().parent().parent().parent().find('td').css({'background-color':'#CDFAB1'});	
		var id_big = $(i).parent().parent().parent().parent().parent().find('td input.datepicker').attr('name');
		var com = 'on';		
		changeStatusBigRow(id_big,com);//88888	
		//$(i).parent().parent().parent().find('.row').css({'background-color':'#CDFAB1'});
		//alert($(i).parent().parent().parent().html())
		//alert($(i).parent().parent().parent().parent().html());
		$(i).parent().parent().parent().parent().find('.table').find(':checkbox').prop('checked', true);
		$(i).parent().parent().parent().parent().find('.table').find('.cell:nth-of-type(1)').css({'background':'url(img/check.png) no-repeat'});
		
		
		var id_str = '';
		$(i).parent().parent().parent().parent().find('.table').find('.statusTask:checkbox').each(function() {
			//alert($(this).attr('name'))
			if(id_str==''){
				id_str = $(this).attr('name');
			}else{
				id_str +=', '+$(this).attr('name')}
    	});
		
		
		//return;
		//$('#bg, #loading').fadeIn("fast");//закрываем на время исполнения
		//alert(id_str);
		$.post("ajax_func.php",
			{ 
			name: 'checkAllRow',
			checkbox: id_str
			},
			function(data){
				if(data=='update_ok'){
					//$('#bg, #loading').fadeOut("fast");//закрываем на время исполнения			
				}else{
					alert(data);			
				}
			});	
	}else{
	$(i).parent().parent().parent().parent().parent().find('td').css({'background-color':'#fff'});
	var id_big = $(i).parent().parent().parent().parent().parent().find('td input.datepicker').attr('name');
	var com = 'off';	
	changeStatusBigRow(id_big,com);//88888	
	$(i).parent().parent().parent().parent().parent().parent().find('.row').css({'background-color':'#fff'});			
	$(i).parent().parent().parent().parent().find('.table').find('.cell:nth-of-type(1) input:checkbox').prop('checked', false);
	$(i).parent().parent().parent().parent().find('.table').find('.cell:nth-of-type(1)').css({'background':'url(img/unch.png) no-repeat'});
	var id_str = '';
	
		$(i).parent().parent().parent().parent().find('.table').find('.statusTask:checkbox').each(function() {
			if(id_str==''){
				id_str = $(this).attr('name');
			}else{
				id_str +=', '+$(this).attr('name')}
    	});
		//$('#bg, #loading').fadeIn("fast");//закрываем на время исполнения
		//alert(id_str);
		$.post("ajax_func.php",
			{ 
			name: 'uncheckAllRow',
			checkbox: id_str
			},
			function(data){
				if(data=='update_ok'){
					//$('#bg, #loading').fadeOut("fast");//закрываем на время исполнения			
				}else{
					alert(data);			
				}
			});	
	}
}
//удаление поездки (big_row) (РАБОТАЕТ)
function delBigRow(i){
		if(confirm('Подтвердите удаление поездки')){
			$(i).attr('id','deleteRow');
			var check = $(i).parent().parent().find('table .input[type="checkbox"]').length;
			var id_kurier = $(i).attr('name');
			
			$.post("ajax_func.php",
				{ 
				name: 'del_big_row',
				id_kurier: id_kurier,
				checkbox: check
				},
				function(data){
					if(data!='OK'){alert('Ошибка #00002: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
					$('#tr_for_id_'+id_kurier).fadeOut("fast",function(){
						$('#tr_for_id_'+id_kurier).remove();
						$('#myTable').find('.table').each(function(index, element) {
						//alert($(this).parent().prev().prev().prev().html())
                		$(this).parent().prev().prev().prev().html('<p><span>'+(Number(index)+1)+'</span></p>');//пересчет строк
            			});
					});		
								
				});	
				
	}
};

//поставщикы/клиенты/другое
function selectTheAdress(id_row){
	if($('#adressOfTheRow').length > 0){
		$('#adressOfTheRow').show();
	$('#adressOfTheRow').html('<div class="selectTheAdresData" onClick="selectTheAdresData(this,\''+id_row+'\')" data-address="suppliers">поставщики</div><div class="selectTheAdresData" onClick="selectTheAdresData(this,\''+id_row+'\')" data-address="clients">клиенты</div><div class="selectTheAdresData" onClick="selectTheAdresData(this,\''+id_row+'\')" data-address="other">другое...</div>');
	}else{
	$('body').append('<div id="adressOfTheRow"><div class="selectTheAdresData" onClick="selectTheAdresData(this,\''+id_row+'\')" data-address="suppliers">поставщики</div><div class="selectTheAdresData" onClick="selectTheAdresData(this,\''+id_row+'\')" data-address="clients">клиенты</div><div class="selectTheAdresData" onClick="selectTheAdresData(this,\''+id_row+'\')" data-address="other">другое...</div></div>');
	}
$('#adressOfTheRow').animate({opacity:1},300);
$('#bg').fadeIn().css({'background':'#000'});
}

//вывод адресов(OK)
function selectTheAdresData(i,id_row){
	var address_data = $(i).data('address');
	if(address_data=='other'){
		$('#adressOfTheRow').fadeOut("fast").remove();
		$('#bg').fadeOut("fast").css({'background':'white'});
		$('#'+id_row+' td:nth-of-type(3)').click();
		return;
	}
	$('#adressOfTheRow').html('').css({'background':'url(img/301.small.gif) 130px 60px no-repeat','background-color':'#fff'});
	var he = $(window).height()-60;
	var user_id = $('#userId').val();
	var crops = $('#crops').val();
	$.post("ajax_func.php",
		{ 
		name: 'queryForAddress',
		address_data: address_data,//поставщик или клиент
		user_id:  user_id,
		crops: crops,
		id_row:id_row
		},
		function(data){
			$('#adressOfTheRow').animate({width: '60%','left':'20%', marginLeft: 0},300).animate({height: he,'top':'25px', marginTop: 0},300).css({'background':''}).html(data);//999999
			$('.tableAddress').delay(600).fadeIn(600);
				//$('#adressOfTheRow');
		});	
}

//присвоение адреса
function getThisAddress(i,id_row){
	$(i).find('.cell2').each(function(index, element){
        if(index==0){
			$('#'+id_row).find('td:nth-of-type(3)').html($(this).html()).css({'padding':'5px'});
		}else if(index==1){
			$('#'+id_row).find('td:nth-of-type(7)').html($(this).html()).css({'padding':'5px'});
		}
		$('#adressOfTheRow').fadeOut(1000).remove();
		$('#bg').fadeOut(1000).css({'background':'white'});
    });	
	
	$.post("ajax_func.php",//PHP обработчик запроса
			{ 		
				name: "getThisAddress",
				company: $('#'+id_row).find('td:nth-of-type(3)').html(),
				address: $('#'+id_row).find('td:nth-of-type(7)').html(),
				id_row: id_row			
			},
			function(data){			
				//if(data!='OK'){alert('Ошибка #00003: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}	
			});
	
}


//обработка команд на клавишах
$(document).keydown(function(e) {
	if(e.keyCode == 27){
		$('#adressOfTheRow').fadeOut(300).remove();
		$('#bg').fadeOut(300).css({'background':'white'});
	}
	if(e.keyCode == 13){//отработка клавиши enter
		
	}
});


//удаление строки smal_row (OK)
function cleanThisePosition(i){
		if(confirm('Подтвердите удаление записи')){
			//$('#bg, #loading').fadeIn("fast");//закрываем на время исполнения
			var textarea_id = $(i).parent().find('.action_text').attr('id');//готовим к отправке id строки для task
			//alert($(i).parent().html())
			$(i).parent().attr('id','selector');
			var heigthRow = $('#selector').height()
			$('#selector').height(heigthRow);
			$('#selector').parent().find('.row:last-child .cell:nth-of-type(1)').width('30px')
			$('#selector').children().remove()
			//$('#bg, #loading').fadeOut("fast");//скрипт отработал, открываем доступ юзера 				
			$('#selector').animate({height: 0},1000);//анимация изменения высоты		
			$('#selector').delay(500).remove();			
			
			$.post("ajax_func.php",//PHP обработчик запроса
			{ 		
				name: "cleanThisePosition",
				textarea_id: textarea_id			
			},
			function(data){			
				if(data!='OK'){alert('Ошибка #00005: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
			});
		}
}

/*//////////////////////////////////////////////*/
/*////////      МОЙ  СТАРЫЙ        ///////////*/
/*//////////////////////////////////////////////*/








//запрос на редактирование textarea big_row
window.alert1 = 0;
function textareaChange(i){ 
	$.post("ajax_func.php",
		{ 
		name: 'changeTextareaTD',
		id_big_row: $(i).parent().data('id'),
		column: $(i).parent().data('name'),
		text: $(i).val()
		},
		function(data){
			window.alert1 = 0;
			if(data=='rec_text'){
				var text_val = $(i).val();
				//$(i).parent().html(text_val);			
			}else{
				alert(data);			
			}
		});	
}

//запрос на редактирование textarea min_row
window.alert2 = 0;
function textareaChangeMin(i){
			var text = $(i).val(); 
			setTimeout(function(){
				if(text==$(i).val()){
					$.post("ajax_func.php",
					{ 
					name: 'changeTextareaTableMin',
					id_min_row: $(i).parent().prev().find(':checkbox').attr('name'),
					text: $(i).val()
					},
					function(data){
						window.alert2 = 0;
						if(data=='rec_text'){			
						}else{
							alert(data);			
						}
					});	 				 
				}	
			},600);			
}

//подсветка зеленым, подстановка значения общих чекбоксов	
function endThis(i){
	var id_smal_row = $(i).attr('name');
	var status_task = 0;
	var length_check = $(i).parent().parent().parent().find('.statusTask:checkbox:checked').length;
	var length_all = $(i).parent().parent().parent().find('.statusTask:checkbox').length;
	
	if($(i).prop('checked')){//выделен
		status_task = 1;//для запроса на отметку о выделении
		if(length_check == length_all){//все выделены
			changeStatusBigRow($(i).parent().parent().parent().parent().parent().find('td input.datepicker').attr('name'),'on');
			$(i).parent().parent().parent().parent().parent().find('td').each(function(index, element) {
                    $(this).css({'background-color':'#CDFAB1'});
            });
			$(i).parent().parent().parent().parent().parent().find('td .row').each(function(index, element) {
                    $(this).css({'background-color':'#CDFAB1'});
            });
			$(i).parent().parent().css({'background-color':'#CDFAB1'});
			$(i).parent().parent().parent().parent().find('.checking_all').prop("checked", true).parent().css({'background':'url(img/check.png) no-repeat'});//меняем общий checkboxи фон дива
		}else{//не все выделены
			$(i).parent().parent().css({'background-color':'#CDFAB1'});
			$(i).parent().parent().parent().parent().find('.checking_all').prop("checked", true).parent().css({'background':'url(img/minus.png) no-repeat'});//меняем общий checkboxи фон дива
		}
	}else{//Не выделен
		
		if(length_check == 0){//все НЕ выделены
			changeStatusBigRow($(i).parent().parent().parent().parent().parent().find('td input.datepicker').attr('name'),'off');		
			$(i).parent().parent().css({'background-color':'#fff'});
				
			$(i).parent().parent().parent().parent().find('.checking_all').prop("checked", false).parent().css({'background':'url(img/unch.png) no-repeat'});//меняем общий checkboxи фон дива
			$(i).parent().parent().parent().parent().parent().find('td').each(function(index, element) {
                    $(this).css({'background-color':'#fff'});
            });
		}else{//не все НЕ выделены
		$(i).parent().parent().css({'background-color':'#fff'});
		$(i).parent().parent().parent().parent().parent().find('td').each(function(index, element) {
                    $(this).css({'background-color':'#fff'});
            });
			$(i).parent().parent().parent().parent().parent().find('td .row :checkbox').each(function(index, element) {
                    if($(this).prop('checked')==false){						
						$(this).parent().parent().css({'background-color':'#fff'});
						}
					
            });
			$(i).parent().parent().parent().parent().find('.checking_all').prop("checked", true).parent().css({'background':'url(img/minus.png) no-repeat'});//меняем общий checkboxи фон дива
		}
				
	}
	//запрос на смену значения статуса small_row		
	$.post("ajax_func.php",
			{ 
			name: 'checkOne',
			status_task: status_task,
			id_smal_row: id_smal_row
			},
			function(data){
				if(data!='check_OK'){
					alert(data);
				}
			});					
}

//меняем статус при big_row при выделении все checkbox в ней, необходимо для нормального отображения статуса в карте курьера на неделю
function changeStatusBigRow(id, com){
	$.post("ajax_func.php",
		{ 
		name: 'changeStatusBigRow',
		id_big_row: id,
		status: com
		},
		function(data){
			if(data!='change_status_ok'){
				alert(data);			
			}
		});	
	
}




//добавление строки адреса
function add_new_address(i){
	var name = 'add_new_address';
	var add_num_rows = $(i).parent().parent().find('tr').length-3;//номер поля
	var date = $('#dateToDay').val(); //дата данной таблицы
	$.post("ajax_func.php",{ 
			name: name,
			add_num_rows: add_num_rows,
			date: date
			},function(data){
				if(!$.isNumeric(data)){alert('Ошибка #00001: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}			
				
				$('#endBigRow').before('<tr id="tr_for_id_'+data+'" rel="'+data+'" style="cursor: move;"><td align="center">'+add_num_rows+'</td><td align="center"><input type="text" style="width:60px; display:none" class="datapicker hasDatepicker" type="text" data-date="' + date + '" name="' + data + '" style="width:60px;"></td><td class="redactorTD" valign="top" data-name="target" data-id="'+data+'" onclick="changeTypeCell(this)" style="padding: 5px;"></td><td valign="top"><div class="table"><div class="row"><div class="cell" style="background:url(img/no_active.png) no-repeat; cursor:pointer; border-right:1px solid #ddd;width:30px;" onclick=""><input class="checking_all" onclick="checkAllRow(this)" style="display:none" type="checkbox"></div><div class="cell"><div onclick="addRowSmall(this)" id="clickMe" class="BtnAddOrDel greenHover" style="padding: 0 5px 2px 5px; margin:10px 5px 5px 5px; float:left;"><img src="img/add2.png" style="margin:0px 5px 0 0; float:left">Новая запись</div></div><div class="cell"></div><div class="cell"></div></div></div></td><td class="redactorTD" valign="top" data-name="docs" data-id="' + data + '" onclick="changeTypeCell(this)" style="padding: 5px;"></td><td class="redactorTD" valign="top" data-name="date_delivery" data-id="' + data + '" onclick="changeTypeCell(this)" style="padding: 5px;"></td><td class="redactorTD" valign="top" data-name="contacts" data-id="' + data + '" onclick="changeTypeCell(this)" style="padding: 5px;"></td><td align="center"><input name="change_data" id="submit_' + data + '" value="изменить" style="display:none;" type="submit"><input name="' + data + '" onClick="delBigRow(this)" type="button" style=" background:url(img/del.png) no-repeat; width:30px; height:30px; border:none; cursor:pointer" value="" type="button"><input name="form_data[id]" value="' + data + '" type="hidden"></td></tr>');								
			//включение добавления
			$('#clickMe').click().removeAttr('id');	
			selectTheAdress('tr_for_id_'+data);
			//	инициализация датапикера в динамически созданнной строке
			
			/************************************/
			$( ".datapicker" ).datepicker({
						showOn: "button",
						buttonImage: "img/calendar.gif",
						buttonImageOnly: true,
						onSelect: function(date){//перенос даты
		//alert('ID '+$(this).attr('name')+'  -  было перенесено с '+$(this).data('date')+' на '+date);
		//alert('Запись была перенесена на '+date);
		var id_big_row = $(this).attr('name');
		var i=0;
		var id_min_row = '';
		var del_old_big_row = 0;
			if($('#tr_for_id_'+id_big_row).find('input:checkbox').length > 0){
			$('#tr_for_id_'+id_big_row).find('input:checkbox').each(function(index, element) {
				if($(this).prop('checked')!=true){
					if(i==0){id_min_row = $(this).attr('name')}else{id_min_row +=', '+ $(this).attr('name')}
					
					i++;
					if($('#tr_for_id_'+id_big_row).find('input:checkbox').length == i){
						$('#tr_for_id_'+id_big_row).fadeOut();
						del_old_big_row = 1;
					}else{
						$(this).parent().parent().fadeOut();
					}
				}			
			});
		}else{
			alert("Перенос пустой строки не имеет смысла, попробуйт выполнить другую операцию");
			$(this).val('');
			return;
		}
		if(id_min_row==''){
			$(this).val('');
		}
		
		var old_date = $(this).data('date');
		var date_log = $(this).data('log');
		$('#bg, #loading').fadeIn("fast");
		$.post("ajax_func.php",
		{ 
		rec_date: 'rec_date',
		date_log: date_log,
		id_big_row: id_big_row,
		old_date: old_date,
		new_date: date,
		id_min_row: id_min_row,
		del_old_big_row: del_old_big_row
		},
		function(data){
			if(data=='rec_date'){
				//alert(data);
				$(this).parent().parent().find('.selectorOne').attr('onClick','uncheckAllRow(this)').html('Отменить все');//меняем кнопку
				setTimeout(function(){
						$('#bg, #loading').fadeOut("fast");//закрываем на время исполнения
					},600);
							
				
			}else{
				$('#bg, #loading').fadeOut("fast");//закрываем на время исполнения
				alert(data);
			}
		});	
	}});
	/***********************************************/	
			});	
		
}


//отработка датапикера (его функции переноса даты)
$(function(){
	$( ".datepicker" ).datepicker({
						showOn: "button",
						buttonImage: "img/calendar.gif",
						buttonImageOnly: true,
						onSelect: function(date){//перенос даты
		//alert('ID '+$(this).attr('name')+'  -  было перенесено с '+$(this).data('date')+' на '+date);
		//alert('Запись была перенесена на '+date);
		var id_big_row = $(this).attr('name');
		var i=0;
		var id_min_row = '';
		var del_old_big_row = 0;
			if($('#tr_for_id_'+id_big_row).find('input:checkbox').length > 0){
			$('#tr_for_id_'+id_big_row).find('input:checkbox').each(function(index, element) {
				if($(this).prop('checked')!=true && $.isNumeric($(this).attr('name'))){
					if(i==0){id_min_row = $(this).attr('name')}else{id_min_row +=', '+ $(this).attr('name')}
					
					i++;
					if($('#tr_for_id_'+id_big_row).find('input:checkbox').length == (i+1)){
						$('#tr_for_id_'+id_big_row).fadeOut();
						del_old_big_row = 1;
					}else{
						$(this).parent().parent().fadeOut();
					}
				}			
			});
		}else{
			alert("Перенос пустой строки не имеет смысла, попробуйт выполнить другую операцию");
			$(this).val('');
			return;
		}
		if(id_min_row==''){
			$(this).val('');
		}
		
		var old_date = $(this).data('date');
		var date_log = $(this).data('log');
		$('#bg, #loading').fadeIn("fast");
		$.post("ajax_func.php",
		{ 
		name: 'rec_date',
		date_log: date_log,
		id_big_row: id_big_row,
		old_date: old_date,
		new_date: date,
		id_min_row: id_min_row,
		del_old_big_row: del_old_big_row
		},
		function(data){
			if(data=='rec_date'){
				//alert(data);
				$(this).parent().parent().find('.selectorOne').attr('onClick','uncheckAllRow(this)').html('Отменить все');//меняем кнопку
				setTimeout(function(){
						$('#bg, #loading').fadeOut("fast");//закрываем на время исполнения
					},600);
							
				
			}else{
				$('#bg, #loading').fadeOut("fast");//закрываем на время исполнения
				alert(data);
			}
		});	
	}});
	
//настройки датапикера
$.datepicker.regional['ru'] = {//настройки датапикера должны инициализироваться под каждым пикиром, т.е. тут
						closeText: 'Закрыть',
						prevText: '&#x3c;Пред',
						nextText: 'След&#x3e;',
						currentText: 'Сегодня',
						monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
						'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
						monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
						'Июл','Авг','Сен','Окт','Ноя','Дек'],
						dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
						dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
						dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
						dateFormat: 'dd.mm.y',
						firstDay: 1,
						minDate: "0",
						isRTL: false
						};
$.datepicker.setDefaults($.datepicker.regional['ru'],function(){alert($(this).attr('name'))});
						
//растягивающийся текстареа						
$('textarea').autogrow();
	
});


// показать детали
function show_details(mouse_event,id){
	   var request = HTTP.newRequest();
	   var url = "index.php?show_details=" + id;
	  
	   // производим запрос
	   request.open("GET", url, true);
	   request.send(null);
	   
		request.onreadystatechange = function(){ // создаем обработчик события
		   if(request.readyState == 4){ // проверяем состояние запроса если запрос == 4 значет ответ получен полностью
			   if(request.status == 200){ // проверяем состояние ответа (код состояния HTTP) если все впорядке продолжаем 
				 ///////////////////////////////////////////
				  // обрабатываем ответ сервера
					
				 var request_response = request.responseText;
				 var content = request_response;
				 content = content.replace(/\{@1\}/,'<div class="notice_details_button_close"><a href="#" onclick="document.getElementById(\'show_details_div\').style.display = \'none\';return false;" style="font-size:16px;">&times;</a></div><div class="notice_details">Компания</div><div class="notice_details_content">');
				 content = content.replace(/\{@2\}/,'</div><div class="notice_details">Цель</div><div class="notice_details_content">');
				 content = content.replace(/\{@3\}/,'</div><div class="notice_details">Документы</div><div class="notice_details_content">');
				 content = content.replace(/\{@4\}/,'</div><div class="notice_details">Время</div><div class="notice_details_content">');
				 content = content.replace(/\{@5\}/,'</div><div class="notice_details">Контакты</div><div class="notice_details_content">');
				 document.getElementById('show_details_div').innerHTML = content + '</div>';
				 document.getElementById('show_details_div').style.display = 'block';
				 document.getElementById('show_details_div').style.top = (mouse_event.clientY - 5) + 'px';
				 document.getElementById('show_details_div').style.left = (mouse_event.clientX + 10) + 'px';
				 //alert(request_response);
			
				 
			   }
			   else{
				  //alert("AJAX запрос не выполнен");
			   }
		   }
	   }
}