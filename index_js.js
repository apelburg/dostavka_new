// JavaScript Document


function change_type_coll(id,page){
	//alert(document.getElementById("tr_for_id_" + id).offsetHeight);
	var height = document.getElementById("data_td_1_" + id).offsetHeight;
	if(page == 'dostavka_podrobno') height = height - 9 ;
	if(page == 'partners_list')height = height - 1 ;
	for(var i = 1 ; i <= 5 ; i++ ){
		if(document.getElementById("data_textarea_" + i +  "_" + id)){
			document.getElementById("data_textarea_" + i +  "_" + id).style.height = height + 'px';
	        document.getElementById("data_textarea_" + i +  "_" + id).style.display = 'block';
			if(document.getElementById("status_" + id)){ 
			        if(document.getElementById("status_" + id).checked === true) document.getElementById("data_textarea_" + i +  "_" + id).style.backgroundColor = '#9DBF29';
			}
		}
	    if(document.getElementById("data_div_" + i +  "_" + id)) document.getElementById("data_div_" + i +  "_" + id).style.display = 'none';
	}
}

// Фабрика создания AJAX объектов
var HTTP = {}; 
HTTP._factories = [
	function(){ return new XMLHttpRequest(); }, // создаем объект
	function(){ return new ActiveXObject("Msxml2.XMLHTTP"); }, // создаем объект
	function(){ return new ActiveXObject("Microsoft.XMLHTTP"); }// создаем объект
]; 
HTTP._factory = null ;
HTTP.newRequest = function(){
	if(HTTP._factory != null) return HTTP._factory();
	
	for( var i = 0 ; HTTP._factories.length ; i++ ){
		try{
			var factory = HTTP._factories[i];
			var request = factory();
			if(request != null){
				HTTP._factory = factory; 
				return request;
			}
		}
		catch(e){
			continue;
		}
	}
	HTTP._factory = function(){
		throw new Error("Объект XMLHttpRequest не поддерживается");
	}
	HTTP._factory();
}
// отправить новые данные формы для обновления записи в базе
function submit_data(id,page){
	   //alert(id);
	   var form = document.getElementById('form_' + id);
	   var pairs = [];
	   var regexp = /%20/g; // Регулярное выражение соответствующее закодированному пробелу
	   for(var i = 0 ; i < form.elements.length ; i++){
		   if(form.elements[i].name != 'delete_data') pairs.push(encodeURIComponent(form.elements[i].name).replace(regexp,"+") + '=' + encodeURIComponent(form.elements[i].value).replace(regexp,"+")); 
		   //alert(form.elements[i].name + ' ' + form.elements[i].value);
	   }
	   var itog_pairs = pairs.join('&');
	   //alert(itog_pairs); 
	   var request = HTTP.newRequest();
	  
	   var url = page + ".php";
	   
	   // производим запрос
	   request.open("POST", url); 
	   request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	   request.send(itog_pairs);
	   
		request.onreadystatechange = function(){ // создаем обработчик события
		   if(request.readyState == 4){ // проверяем состояние запроса если запрос == 4 значет ответ получен полностью
			   if(request.status == 200){ // проверяем состояние ответа (код состояния HTTP) если все впорядке продолжаем 
				 ///////////////////////////////////////////
				  // обрабатываем ответ сервера
					
				 var request_response = request.responseText;
				// alert(request_response);
			
				 
			   }
			   else{
				  //alert("AJAX запрос не выполнен");
			   }
		   }
	   }
}

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


function show_companies_list(element,param){
	  
	   var offset = function(element){
		   var x = 0 ;
		   var y = 0 ;
		   while(element){
			   x += element.offsetLeft;
			   y += element.offsetTop;
			   element = element.offsetParent;
		   }
		   return new Array( y , x );		   
	   };
	   var position = offset(element);
	   
	   // показываем список для предварительного выбора
	   if(param == 'prev'){
	       var div = document.getElementById("companies_list_prev");
	       div.style.display = "block";
		   div.style.top = position[0] + element.offsetHeight + 4 - 56 + 'px';
		  // div.style.bottom = "0px";
		   div.style.left = position[1] + 108 + 'px'; 
		   this.onmouseup = function(){ 
		      document.getElementById("companies_list_prev").style.display = "none"; 
	       }
		   return;
	   }
       if(param == 'suppliers' || param == 'clients'){
		
		  document.getElementById("companies_list_prev").style.display = "none"; 
	   }

       this.onmouseup = function(){ 
	      document.getElementById("companies_list").style.display = "none"; 
	   }
	   var request = HTTP.newRequest();
	   var url = "dostavka_podrobno.php?show_companies_list=yes&type=" + param;
	   
	   // производим запрос
	   request.open("GET", url, true);
	   request.send(null);
	   
		request.onreadystatechange = function(){ // создаем обработчик события
		   if(request.readyState == 4){ // проверяем состояние запроса если запрос == 4 значет ответ получен полностью
			   if(request.status == 200){ // проверяем состояние ответа (код состояния HTTP) если все впорядке продолжаем 
				 ///////////////////////////////////////////
				  // обрабатываем ответ сервера
					
				 var request_response = request.responseText;
				 //alert(request_response);
				 var pairs = request_response.split('{@}');
				 var names = '';
				 for(var i = 0 ; i < pairs.length; i++){
					 var content = pairs[i].split('{@#@}');
				     names += '<div class="show_companies_list" onclick="full_textarea(this);"><div>' + content[0] +'</div><div style="display:none;">' + content[1] +'</div></div>';
				 }
				 document.getElementById('companies_list').innerHTML =  names ;
				 document.getElementById('companies_list').style.display = 'block';
				 document.getElementById('companies_list').style.top = position[0] + element.offsetHeight + 4 -160 + 'px';
				 document.getElementById('companies_list').style.left = position[1] + 108 + 'px'; 
			
				 
			   }
			   else{
				  //alert("AJAX запрос не выполнен");
			   }
		   }
	   }	
}
function full_textarea(div){
	 document.getElementById('form_data_target').innerHTML = div.firstChild.innerHTML;
	 document.getElementById('form_data_contacts').innerHTML = div.lastChild.innerHTML;
}
function change_deal_status(elemet,id){
     var status = (elemet.checked === true)? 'on' : 'off' ;
	 var tr_bg_color = (status == 'on')? '#9DBF29' : '#FFFFFF' ;
     var request = HTTP.newRequest();
     var url = "dostavka_podrobno.php?change_deal_status=" + status + "&id=" + id;
     //alert(elemet.value);
     // производим запрос
     request.open("GET", url, true);
     request.send(null);
   
	 request.onreadystatechange = function(){ // создаем обработчик события
	    if(request.readyState == 4){ // проверяем состояние запроса если запрос == 4 значет ответ получен полностью
		    if(request.status == 200){ // проверяем состояние ответа (код состояния HTTP) если все впорядке продолжаем 
			    ///////////////////////////////////////////
			    // обрабатываем ответ сервера
				
			    var request_response = request.responseText;
			    //alert(request_response);
			    
				if(elemet.value)elemet.value = status;
				document.getElementById('tr_for_id_' + id).style.backgroundColor = tr_bg_color;
				
			    for(var i = 1 ; i <= 5 ; i++ ){
	                 document.getElementById("data_textarea_" + i +  "_" + id).style.backgroundColor = tr_bg_color;
			    }
			 
		    }
		    else{
			    //alert("AJAX запрос не выполнен");
		    }
	    }
    }	
}
function change_date_deal(element,id){
	 // alert(element.options[element.options.selectedIndex].value);
     var request = HTTP.newRequest();
     var url = "dostavka_podrobno.php?change_date_deal=" + element.options[element.options.selectedIndex].value + "&id=" + id;
  
     // производим запрос
     request.open("GET", url, true);
     request.send(null);
   
	 request.onreadystatechange = function(){ // создаем обработчик события
	    if(request.readyState == 4){ // проверяем состояние запроса если запрос == 4 значет ответ получен полностью
		    if(request.status == 200){ // проверяем состояние ответа (код состояния HTTP) если все впорядке продолжаем 
			    ///////////////////////////////////////////
			    // обрабатываем ответ сервера
				
			    var request_response = request.responseText;
			    location = location.href;
		    }
		    else{
			    //alert("AJAX запрос не выполнен");
		    }
	    }
    }
	
}
    // отбражение скрытого дива
	
	function show_hide_div(id){
		var div = document.getElementById(id);
		div.style.display = (div.style.display == 'none')?'block':'none' ;
	}