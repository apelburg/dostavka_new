// JavaScript Document.

//функции выполняющиеся после загрузки страницы
$(function(){
	
	greeen_table();
	$( ".datepicker" ).datepicker({
						showOn: "button",
						buttonImage: "img/calendar.gif",
						buttonImageOnly: true,
						onSelect: function(date){//перенос даты
		//alert('ID '+$(this).attr('name')+'  -  было перенесено с '+$(this).data('date')+' на '+date);
		//alert('Запись была перенесена на '+date);
		var id_big_row = $(this).attr('name');
		var z=0;//невыполненные задания
		var id_min_row = '';
		var id_min_row_ajax = '';
		var del_old_big_row = 0;
			if($('#tr_for_id_'+id_big_row).find('input.statusTask:checkbox').length > 0){
			//подсчет невыполненных заданий 			
			$('#tr_for_id_'+id_big_row).find('input.statusTask:checkbox').each(function(index, element){
				if($(this).prop('checked')!=true){
					if(z==0){
					id_min_row = $(this).attr('name')
					id_min_row_ajax = '#'+$(this).attr('name');
				}else{
					id_min_row +=', '+ $(this).attr('name');
					id_min_row_ajax += ', #'+$(this).attr('name');
					}
					z++;					
				}	
			});
			
			if(z>0){
				if($('#tr_for_id_'+id_big_row).find('input.statusTask:checkbox').length == z){
					$('#tr_for_id_'+id_big_row).fadeOut('fast').remove();
					del_old_big_row = 1;
					$('#myTable').find('.datepicker').each(function(index, element) {//пересчет строк
						$(this).parent().parent().prev().html('<p><span>'+(Number(index)+1)+'</span></p>');//пересчет строк
					});
					//alert('удаляем строку');					
				}else{
					$('#tr_for_id_'+id_big_row).find('input.statusTask:checkbox').each(function(index, element){
						if($(this).prop('checked')!=true){
							$(this).parent().parent().fadeOut('fast').remove();					
						}	
					});					
					//alert('перенос частичный');
				}
			}	
		}else{
			alert("Перенос строки без задачи не имеет смысла, попробуйте выполнить другую операцию");
			$(this).val('');
			return;
		}
		if(id_min_row==''){
			$(this).val('');
		}
		$(this).parent().parent().parent().find('td').css({'background-color':'#CDFAB1'});
		$(this).parent().parent().parent().find('.checking_all').prop('checked', true);
		$(this).parent().parent().parent().find('.checking_all').parent().css({'background':'url(img/check.png) no-repeat'});
		resortBigRow();
		var old_date = $(this).data('date');
		var date_log = $(this).data('log');
		//$('#bg, #loading').fadeIn("fast");
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
			if(data!='rec_date'){alert('Ошибка #00001: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
		});	
			
	}});
	
	//настройки датапикера
	$.datepicker.regional['ru'] = {//настройки датапикера должны инициализироваться под каждым объявлением пикира
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
	$('#add_new_address_id').css({'opacity':'1'}).attr('onClick','add_new_address(this)');
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
				if(!$.isNumeric(data)){alert('Ошибка #00002: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
				$('#added_small_row_new').find('.cell:nth-of-type(1)').attr('onClick','checkS(this)').css({'background':'url("img/unch.png") no-repeat'});
				greeen_table();
				$('#added_small_row_new').find('.Btngiv_take').remove();
				$('#added_small_row_new').find(':checkbox').attr('name',data);
				$('#added_small_row_new').find('textarea').val(com).show().attr('id','task_'+data).attr('class', 'action_text');
				$('#added_small_row_new').find('.cell:nth-of-type(3)').html($(i).html());
				if($(i).html()=='другое...'){//создаём редактируемое поле
					$('#added_small_row_new').find('.cell:nth-of-type(3)').attr('data-id',data).attr('onClick','changeTypeCell(this,\'addRow_point\')').attr('data-name','second_point');
					$('#added_small_row_new').find('.cell:nth-of-type(3)').click();
				}
				
				$('#added_small_row_new').find('.action_text').focus();
				//$('#added_small_row_new').attr('id','small_row_'+data);
				$('#myTable #delMyMinRow').removeAttr('onClick').removeAttr('id');
				if($('#added_small_row_new').parent().find('.row input:checkbox').prop('checked')){
					$('#added_small_row_new').next().find('.cell:nth-of-type(1)').attr('onClick','checkS(this)').css({'background':'url("img/minus.png") no-repeat'});
					}else{
					$('#added_small_row_new').next().find('.cell:nth-of-type(1)').attr('onClick','checkS(this)').css({'background':'url("img/unch.png") no-repeat'});
					}
						
				$('#added_small_row_new').next().find('.cell:nth-of-type(1)').attr('onClick','checkS(this)');
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
	
function check_long_tho(str){
	if(str.length == 1){
		return '0'+str;
	}else{
		return str;
	}
}

//добавить строку и предложить выбор: забрать/отдать 
function addRowSmall(i){
	//alert($(i).parent().parent().html())
	$(i).parent().parent().animate({margin: '59px 0 0 0' },0, function(){
		$(i).parent().parent().parent().css({'padding-top': 0});
		$('#template_add_min_row').children().attr('id','added_small_row_new');//присваиваем служебный id для добавляемого поля
		var date = new Date();

		var date_str = check_long_tho(date.getDate())+'.'+check_long_tho((date.getMonth()+1))+'.'+check_long_tho(date.getFullYear())+
		' '+check_long_tho(date.getHours())+':'+check_long_tho(date.getMinutes());
		$('#template_add_min_row').find('.cell:nth-of-type(4)').append(date_str);

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

//изменение обычного поля на редактироумое 
function changeTypeCell(i,type){
	if($(i).find('textarea').length<1){
		var html = $(i).html();
		html = $.trim(html);
		html = html.replace(/\<br\>/g, "\n").replace(/\<br \/\>/g, "\n");
		$(i).css({'padding':'0'});
		if(type=='addRow_point'){
			$(i).html('<textarea onBlur="blurText(this)" onkeydown="textareaChange(this)" class="redactor_textarea" style="width:'+(+$(i).width()-12)+'px; height:'+(+$(i).height()-12)+'px;overflow: hidden; border:1px solid #92B740; padding:0px;">'+html+'</textarea>');
		}else if(type=='addRow'){
			$(i).html('<textarea onBlur="blurText(this)" class="redactor_textarea" style="width:'(+$(i).width()-12)+'px; height:'+(+$(i).height()-12)+'px;overflow: hidden; border:1px solid #92B740; padding:5px;">'+html+'</textarea>');
		}else{
			$(i).html('<textarea onBlur="blurText(this)" onKeyDown="textareaChange(this,\'12212254197\')" onKeyPress="textareaChange(this,\'12212254197\')" onKeyUp="textareaChange(this,\'12212254197\')" class="redactor_textarea" style="width:'+(+$(i).width()-12)+'px; height:'+(+$(i).height()-12)+'px;overflow: hidden; border:1px solid #92B740; padding:5px;">'+html+'</textarea>');
		}
		$(i).find('textarea').focus();
		$('textarea').autogrow();
	}
}

//убираем поле ввода при потере фокуса 
function blurText(i){
	var text_val = $(i).val();
	if($(i).parent().is('td'))
	$(i).parent().html(text_val).css({'padding':'5px 5px 0px 5px'});
	else
	$(i).parent().html(text_val).css({'padding':'0px'});
}

//подсветка зеленым сразу после загрузки страницы
function greeen_table(){
	$('.table').each(function(index, element) {
        if($(this).find(':checkbox.statusTask').length>0 && $(this).find(':checkbox.statusTask').length==$(this).find(':checkbox.statusTask:checked').length){
			$(this).parent().parent().find('td').each(function(index, element) {

				$(this).addClass('green_class');	
				if($(this).html()==''){
					if($(this).index() == 2 || $(this).index() == 4 || $(this).index() == 5 || $(this).index() == 6){
						$(this).addClass('red_class');
					}					
				}
                
            });
			}else if($(this).find(':checkbox.statusTask:checked').length>0 && $(this).find(':checkbox.statusTask').length!=$(this).find(':checkbox.statusTask:checked').length){
				$(this).find('.row:last-child .cell:nth-of-type(1)').css({"background": "url(img/minus.png) no-repeat"});
				//$(this).find('.row:last-child .cell:nth-of-type(1) input:checkbox').prop('checked', false);
			}else{
			$(this).parent().parent().find('td').each(function(index, element) {
                //$(this).css({'background':'none'});
                if($(this).html()==''){
					if($(this).index() == 2 || $(this).index() == 4 || $(this).index() == 5 || $(this).index() == 6){
						$(this).addClass('red_class');
					}					
				}
            });
				//$(this).find('.row:last-child .cell:nth-of-type(1)').css({"background": "url(img/unch.png) no-repeat"});
			}
		$(this).find('.checking_all').parent().parent().css({'background':'none'});
		$(this).find(':checkbox.statusTask:checked').each(function(index, element) {
           $(this).parent().parent().addClass('green_class');
        });
    });	
}

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
		var id_big = $(i).parent().parent().parent().parent().parent().find('td:nth-of-type(2) input').attr('name');
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
				if(data!='update_ok'){alert('Ошибка #00003: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}				
			});	
	}else{
	$(i).parent().parent().parent().parent().parent().find('td').css({'background-color':'#fff'});
	var id_big = $(i).parent().parent().parent().parent().parent().find('td:nth-of-type(2) input').attr('name');
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
				if(data!='update_ok'){alert('Ошибка #00004: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
			});	
	}
}
//сортировка строк
function resortBigRow(){
	$('#myTable').find('.datepicker').each(function(index, element) {
	//alert($(this).parent().prev().prev().prev().html())
	$(this).parent().parent().prev().html('<p><span>'+(Number(index)+1)+'</span></p>');//пересчет строк
	});
}
//удаление поездки (big_row) 
function delBigRow(i){
		if(confirm('Подтвердите удаление поездки')){
			$(i).attr('id','deleteRow');
			var id_kurier = $(i).attr('name');
			if($(i).parent().parent().find('#added_small_row_new').length>0){
			var check = $(i).parent().parent().find('.table .statusTask').length-$(i).parent().parent().find('#added_small_row_new').length;
			}else{
			var check = $(i).parent().parent().find('.table .statusTask').length;
			}
			//alert(check);
			//return;
			$.post("ajax_func.php",
				{ 
				name: 'del_big_row',
				id_kurier: id_kurier,
				checkbox: check
				},
				function(data){
					if(data!='OK'){
						alert('Ошибка #00005: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
					$('#tr_for_id_'+id_kurier).fadeOut("fast",function(){
						$('#tr_for_id_'+id_kurier).remove();
						resortBigRow();						
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

// выбор клиента
$(document).on('click', '#get_client_addres_for_new_row .checkThisClient', function(event) {

	event.preventDefault();
	$('#get_client_addres_for_new_row input[name="client_id"]').val($(this).attr('data-id'));
	var serialize = $('#get_client_addres_for_new_row').serialize();

	// $('#adressOfTheRow').fadeOut(300).remove();
	// $('#bg').fadeOut(300).css({'background':'white'});
	$.post('ajax_func.php', serialize, function(data, textStatus, xhr) {
		$('#adressOfTheRow').html(data);//999999
		// $('.tableAddress').delay(200).fadeIn(200);
	});
});

//вывод адресов(OK)
function selectTheAdresData(i,id_row){
	var address_data = $(i).data('address');//тип адреса(поставщик, клиент, другое...)
	if(address_data=='other'){
		$('#adressOfTheRow').fadeOut("fast").remove();
		$('#bg').fadeOut("fast").css({'background':'white'});
		$('#'+id_row+' td:nth-of-type(3)').click();
		return;
	}
	$('#adressOfTheRow').html('В целях ускорения появления окна была снята фильтрация списка. <strong>Если контакты неполные, заполните необходимую информацию в ОС и поставьте галку напротив контактного лица</strong>').css({'background':'url(img/301.small.gif) 130px 120px no-repeat','background-color':'#fff','text-align':'center','color':'#737373'});
	var he = $(window).height()-60;
	var user_id = $('#userId').val();
	var crops = $('#crops').val();
	
	$.post("ajax_func.php",
		{ 
		name: 'queryForAddress',
		address_data: address_data,//тип адреса(поставщик, клиент, другое...
		user_id:  user_id,
		crops: crops,
		id_row:id_row
		},
		function(data){
			$('#adressOfTheRow').animate({width: '60%','left':'20%', marginLeft: 0},100).animate({height: he,'top':'25px', marginTop: 0},100).css({'background':'','text-align':'left'}).html(data).attr('data-address',address_data);//999999
			$('.tableAddress').delay(200).fadeIn(200);
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
		$('#adressOfTheRow').fadeOut(300).remove();
		$('#bg').fadeOut(300).css({'background':'white'});
    });	
	//alert($(i).parent().parent().data('address')+"\n"+$(i).find('.cell2:nth-of-type(1)').data('id'));return;
	var typeOfAddress = $(i).parent().parent().data('address');//клиент,поставщик,другое... 
	var parent_id_address = $(i).find('.cell2:nth-of-type(1)').data('id');//id записи по данному клиенту ,поставщику...если есть  
	$.post("ajax_func.php",//PHP обработчик запроса
			{ 		
				name: "getThisAddress",
				parent_id_address:parent_id_address,
				target_typpe: typeOfAddress,
				company: $('#'+id_row).find('td:nth-of-type(3)').html(),
				address: $('#'+id_row).find('td:nth-of-type(7)').html(),
				id_row: id_row			
			},
			function(data){			
				if(data!='OK'){alert('Ошибка #00006: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}	
			});
			
	$('.newBigRowLabel').removeClass('newBigRowLabel');
}
//удаление добаленной в дом ошибочной строки
function delMyDOMSmallRow(i){
	$(i).parent().parent().fadeOut('fast').remove();
	$('div.BtnAddOrDel.greenHover').css({'opacity':'1'}).attr('onClick','addRowSmall(this)');
	$('#add_new_address_id').css({'opacity':'1'}).attr('onClick','add_new_address(this)');
} 
//обработка команд на клавишах
$(document).keydown(function(e) {
	if(e.keyCode == 27){
		$('#adressOfTheRow').fadeOut(300).remove();
		$('#bg').fadeOut(300).css({'background':'white'});
		
		
		if($('#myTable #delMyMinRow').length>0){
			$('#myTable #delMyMinRow').parent().parent().fadeOut('fast').remove();
			$('div.BtnAddOrDel.greenHover').css({'opacity':'1'}).attr('onClick','addRowSmall(this)');
			$('#add_new_address_id').css({'opacity':'1'}).attr('onClick','add_new_address(this)');
		}
		
		if($('.newBigRowLabel').length>0){
			$('.newBigRowLabel').each(function(index, element) {
                var id_big_row = $(this).attr('rel');
				$(this).fadeOut('fast').remove();
				$('div.BtnAddOrDel.greenHover').css({'opacity':'1'}).attr('onClick','addRowSmall(this)');
				$('#add_new_address_id').css({'opacity':'1'}).attr('onClick','add_new_address(this)');
				
				$.post("ajax_func.php",{ 
					name: 'del_big_row',
					id_kurier: id_big_row
					},function(data){
						if(data!='OK'){alert('Ошибка #00007: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
					});
            });
			}
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
				if(data!='OK'){alert('Ошибка #00008: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
			});
		}
}

//запрос на редактирование textarea big_row
window.alert1 = 0;
function textareaChange(i,task){ 

	if($(i).val().length >0){
		$(i).parent().removeClass('red_class');
	}else{
		$(i).parent().addClass('red_class');
	}


	$.get("ajax_func.php",
		{ 
		name: 'changeTextareaTD',
		task: task,
		id_big_row: $(i).parent().data('id'),
		column: $(i).parent().data('name'),
		text: $(i).val()
		},
		function(data){
			window.alert1 = 0;
			if(data!='rec_text'){alert('Ошибка #00009: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
			var text_val = $(i).val();			
		});	
		check_loading_ajax();
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
						if(data!='rec_text'){alert('Ошибка #00010: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
					});	 	
					check_loading_ajax();			 
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
			changeStatusBigRow($(i).parent().parent().parent().parent().parent().find('td:nth-of-type(2) input').attr('name'),'on');
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
			changeStatusBigRow($(i).parent().parent().parent().parent().parent().find('td:nth-of-type(2) input').attr('name'),'off');		
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
	var target = $(i).parent().next().find('div:nth-of-type(1)').html();
	var adress_name = $(i).parent().parent().parent().parent().prev().html();	
	$.post("ajax_func.php",
			{ 
			name: 'checkOne',
			status_task: status_task,
			user_id: $('#userId').val(),
			date:$('#dateToDay').val(),
			id_smal_row: id_smal_row,
			adress: adress_name,
			target: target
			},
			function(data){
				if(data!='check_OK'){alert('Ошибка #00011: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
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
			if(data!='change_status_ok'){alert('Ошибка #00012: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
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
				if(!$.isNumeric(data)){alert('Ошибка #00013: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}			
				
				$('#endBigRow').before('<tr id="tr_for_id_'+data+'" class="newBigRowLabel" rel="'+data+'" style="cursor: move;"><td align="center">'+add_num_rows+'</td><td align="center"><input type="text" style="width:60px; display:none" class="datapicker" type="text" data-date="' + date + '" name="' + data + '" style="width:60px;"></td><td class="redactorTD" valign="top" data-name="target" data-id="'+data+'" onclick="changeTypeCell(this)" style="padding: 5px;"></td><td valign="top"><div class="table"><div class="row"><div class="cell" style="none; cursor:pointer; border-right:1px solid #ddd;width:30px;" onclick=""><input class="checking_all" onclick="checkAllRow(this)" style="display:none" type="checkbox"></div><div class="cell"><div onclick="addRowSmall(this)" id="clickMe" class="BtnAddOrDel greenHover" style="padding: 0 5px 2px 5px; margin:10px 5px 5px 5px; float:left;"><img src="img/add2.png" style="margin:0px 5px 0 0; float:left"></div></div><div class="cell"></div><div class="cell"></div></div></div></td><td class="redactorTD" valign="top" data-name="docs" data-id="' + data + '" onclick="changeTypeCell(this)" style="padding: 5px;"></td><td class="redactorTD" valign="top" data-name="date_delivery" data-id="' + data + '" onclick="changeTypeCell(this)" style="padding: 5px;"></td><td class="redactorTD" valign="top" data-name="contacts" data-id="' + data + '" onclick="changeTypeCell(this)" style="padding: 5px;"></td><td align="center"><input name="change_data" id="submit_' + data + '" value="изменить" style="display:none;" type="submit"><input name="' + data + '" onClick="delBigRow(this)" type="button" style=" background:url(img/del.png) no-repeat; width:30px; height:30px; border:none; cursor:pointer" value="" type="button"><input name="form_data[id]" value="' + data + '" type="hidden"></td></tr>');								
			//включение добавления
			$('#clickMe').click().removeAttr('id');	
			selectTheAdress('tr_for_id_'+data);
			//	инициализация датапикера в динамически созданнной строке
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
		var id_min_row_ajax = '';
		var del_old_big_row = 0;
			if($('#tr_for_id_'+id_big_row).find('input.statusTask:checkbox').length > 0){
			//подсчет невыполненных заданий 			
			$('#tr_for_id_'+id_big_row).find('input.statusTask:checkbox').each(function(index, element){
				if($(this).prop('checked')!=true){
					if(i==0){
					id_min_row = $(this).attr('name')
					id_min_row_ajax = '#'+$(this).attr('name');
				}else{
					id_min_row +=', '+ $(this).attr('name');
					id_min_row_ajax += ', #'+$(this).attr('name');
					}
					i++;					
				}	
			});
			
			if(z>0){
				if($('#tr_for_id_'+id_big_row).find('input.statusTask:checkbox').length == z){
					$('#tr_for_id_'+id_big_row).fadeOut('fast').remove();
					del_old_big_row = 1;
					$('#myTable').find('.datepicker').each(function(index, element) {//пересчет строк
						$(this).parent().parent().prev().html('<p><span>'+(Number(index)+1)+'</span></p>');//пересчет строк
					});
					//alert('удаляем строку');					
				}else{
					$('#tr_for_id_'+id_big_row).find('input.statusTask:checkbox').each(function(index, element){
						if($(this).prop('checked')!=true){
							$(this).parent().parent().fadeOut('fast').remove();					
						}	
					});					
					//alert('перенос частичный');
				}
			}	
		}else{
			alert("Перенос строки без задачи не имеет смысла, попробуйте выполнить другую операцию");
			$(this).val('');
			return;
		}
		if(id_min_row==''){
			$(this).val('');
		}
		$(this).parent().parent().parent().find('td').css({'background-color':'#CDFAB1'});
		$(this).parent().parent().parent().find('.checking_all').prop('checked', true);
		$(this).parent().parent().parent().find('.checking_all').parent().css({'background':'url(img/check.png) no-repeat'});
		resortBigRow();
		var old_date = $(this).data('date');
		var date_log = $(this).data('log');
		//$('#bg, #loading').fadeIn("fast");
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
			if(data!='rec_date'){alert('Ошибка #00014: \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
		});	
		
	}});
			});	
		
}

// ???
function chenge_this_point(i){
	$(i).html()
	}
	
	
//перемещение курсора в конец поля	
function moveCursorToEnd(el) {
    if (typeof el.selectionStart == "number") {
        el.selectionStart = el.selectionEnd = el.value.length;
    } else if (typeof el.createTextRange != "undefined") {
        el.focus();
        var range = el.createTextRange();
        range.collapse(false);
        range.select();
    }
}

//статус сохранения отредактированного поля
function check_loading_ajax(){
		window.l++;
		console.log(jQuery.active);
		if(jQuery.active>0){
			if($('#alert_saving_status').length==0){
				$('body').append('<div style="position:fixed; float:left;font-family: arial,sans-serif; left:50%; top:100px; margin-left:-100px; background-color:#F9EDBE;border:1px solid #F0C36D; padding:7px 15px; font-size:12px" id="alert_saving_status"><div id="ll">Данные сохраняются...</div><div id="lll" style="text-align:center"></div><div id="lll1"><div id="lll2" style="width:0%;background: #F0C36D; height:5px; border:0"></div></div></div>');	
				$('#alert_saving_status').stop(true, true).fadeIn('fast');
			}else{
				$('#alert_saving_status').fadeIn('fast');			
			}
			var p = jQuery.active;
			var q = window.l / 100;
			var per = Math.ceil((100-p/q));
			$('#lll').html(per +' %');
			$('#lll2').width(per+'%');
			setTimeout(check_loading_ajax, 300);
			return false;
		}else{
			
			$('#ll').html('Данные успешно сохранены.')
			$('#lll').html('100 %');
			$('#lll2').width('100%');		
			$('#alert_saving_status').delay(1000).animate({opacity:0},700,function(){$(this).remove()});
			
			//setTimeout($('#alert_saving_status').fadeOut('fast').remove(), 3000)	
			window.l = 0;
			return true;	
		}
	};
	$(document).ready(function(){
	window.l = 0;
	window.onbeforeunload = function () {return ((check_loading_ajax()==false) ? "Измененные данные не сохранены. Закрыть страницу?" : null);}
	});