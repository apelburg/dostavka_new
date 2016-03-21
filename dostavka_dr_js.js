//подсветка зеленым сразу после загрузки страницы
$(function() {
		greeen_table();
});
////дубль того же кода в функции /////
function greeen_table(){
	$('.table').each(function(index, element) {
        if($(this).find(':checkbox.statusTask').length>0 && $(this).find(':checkbox.statusTask').length==$(this).find(':checkbox.statusTask:checked').length){
			$(this).parent().parent().find('td').each(function(index, element) {
                $(this).css({'background-color':'#9DBF29'});
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
            $(this).parent().parent().css({'background-color':'#9DBF29'});
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
function check_driver(i){
	$(i).children().children(':checkbox').click();		
	if($(i).children().children(':checkbox').prop('checked')!=true){
			$(i).find('.cell:nth-of-type(1)').css({'background':'url(img/unch.png) no-repeat'});			
		}else{			
			$(i).find('.cell:nth-of-type(1)').css({'background':'url(img/check.png) no-repeat'});			
		}
	//greeen_table();
//	alert($(i).children().attr('name'));
}

//выделяем все :checkbox
function checkAllRow(i){
	if($(i).prop('checked') == true){
		$(i).parent().parent().parent().parent().parent().find('td').css({'background-color':'#9DBF29'});	
		var id_big = $(i).parent().parent().parent().parent().parent().find('td input.datepicker').attr('name');
		var com = 'on';		
		changeStatusBigRow(id_big,com);//88888	
		//$(i).parent().parent().parent().find('.row').css({'background-color':'#9DBF29'});
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






//обработка команд на клавишах
$(document).keydown(function(e) {
	if(e.keyCode == 27){
		$('#adressOfTheRow').fadeOut(300).remove();
		$('#bg').fadeOut(300).css({'background':'white'});
	}
	if(e.keyCode == 13){//отработка клавиши enter
		
	}
});










//подсветка зеленым, подстановка значения общих чекбоксов	
function endThis(i){
	var id_smal_row = $(i).attr('name');
	var status_task = 0;
	var length_check = $(i).parent().parent().parent().find('.statusTask:checkbox:checked').length;
	var length_all = $(i).parent().parent().parent().find('.statusTask:checkbox').length;
	
	if($(i).prop('checked')){//выделен
		status_task = 1;//для запроса на отметку о выделении
		if(length_check == length_all){//все выделены
			changeStatusBigRow($(i).data('id_parent'),'on');
			$(i).parent().parent().parent().parent().parent().find('td').each(function(index, element) {
                    $(this).css({'background-color':'#9DBF29'});
            });
			$(i).parent().parent().parent().parent().parent().find('td .row').each(function(index, element) {
                    $(this).css({'background-color':'#9DBF29'});
            });
			$(i).parent().parent().css({'background-color':'#9DBF29'});
			$(i).parent().parent().parent().parent().find('.checking_all').prop("checked", true).parent().css({'background':'url(img/check.png) no-repeat'});//меняем общий checkboxи фон дива
		}else{//не все выделены
			$(i).parent().parent().css({'background-color':'#9DBF29'});
			$(i).parent().parent().parent().parent().find('.checking_all').prop("checked", true).parent().css({'background':'url(img/minus.png) no-repeat'});//меняем общий checkboxи фон дива
		}
	}else{//Не выделен
		
		if(length_check == 0){//все НЕ выделены
			changeStatusBigRow($(i).data('id_parent'),'off');
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

function check_oneClone(i){
//alert('#get_row_'+$(i).data('id')+' -- '+$(i).data('parent_id')+' -- '+$(i).attr('onClick'));
$('#get_row_'+$(i).data('id')).click();
$('#big_point').html($('#'+$(i).data('parent_id')).next().next().html());
$('#big_point').find('.cell:nth-of-type(2)').removeAttr('style').children().css({'font-size':'15px'});	
}

function display_big_window(i){
	$(i).next().find('.row').each(function(index, element) {
		$(this).removeAttr('id');
		$(this).attr('data-id',index);
		$(this).attr('data-parent_id',$(i).parent().find('td:nth-of-type(1)').attr('id'));		
    });
	
	$('#no_windows').hide();
	$('#window_big_size').show();
	$('#window_big_size').children('#big_name').html($(i).html()+'<div id="close_big" onClick="close_and_remove(this)">Закрыть X</div>');
	$('#window_big_size').children('#big_point').html($(i).next().html());	
	$('#window_big_size').find('.row').each(function(index, element) {
		$(this).attr('onClick','check_oneClone(this)');        
    });
	$(i).next().find('.row').each(function(index, element) {
		$(this).attr('id','get_row_'+index);		
    });
	$('#big_docs').html($(i).next().next().html());
	$('#big_time').html($(i).next().next().next().html());	
	$('#window_big_size').children('#big_contacts').html('<textarea style="width:100%;min-height:80px" data-id_parent="'+$(i).data('id_parent')+'" onkeypress="textareaChangeBig(this)" onkeyup="textareaChangeBig(this)">'+$(i).next().next().next().next().html()+'</textarea>');	
	
	$('#big_point').find('.cell:nth-of-type(2)').removeAttr('style').children().css({'font-size':'15px'});
	$('#big_point').find('.cell:nth-of-type(3) div').removeAttr('style');
}

function close_and_remove(i){
	$('#big_point').find('.row').each(function(index, element) {
        $('.row').removeAttr('id');
    });
	$('#no_windows').show(); 
	greeen_table();
	$(i).parent().parent().hide();	
}
function textareaChangeBig(i){
	$.post("ajax_func.php",
		{ 
		name: 'changeTextareaTD',
		column: 'contacts',
		id_big_row: $(i).data('id_parent'),
		text: $(i).val()
		},
		function(data){
			if(data!='rec_text'){alert('Ошибка #00007 (изменение не было применено): \n'+data+'\n\nпожалуйста скопируйте данное сообщение \nи отправьте администратору.');return;}
		});	
		$('#tr_for_id_'+$(i).data('id_parent')).find('td:last-child').html($(i).val());
	
	}