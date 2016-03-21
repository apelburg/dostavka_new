<?php

 class karta_kurijera {
       
	   var $back_cur_day; // обратно преобразованная дата в обычный формат
	   var $back_cur_day_short; // короткий вариант
	   var $cur_week_day; // текущий день недели
	   var $num_first_day_in_week_on_cur_month; // номер первого дня месяца относительно недели
	   var $cur_month_num; // номер текущего месяца
	   var $cur_year_num; // номер текущего года
	   var $cur_month_day_num;
	   var $cur_month;
	   var $cur_month_day_quantity;
	   var $week_day_name_arr = array('Monday' => 'Понедельник','Tuesday' => 'Вторник','Wednesday' => 'Среда','Thursday' => 'Четверг','Friday' => 'Пятница','Saturday' => 'Суббота','Sunday' => 'Воскресенье');
	   var $month_day_name_arr = array('','январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь');
	   
       function karta_kurijera($day,$month,$year){
	      // if($day != 0){
		   
		       $this->back_cur_day = date('d.m.y_H.i.s',mktime(0,0,0,$month,$day,$year));
			   $this->back_cur_day_short = date('d.m.y',mktime(0,0,0,$month,$day,$year));
			   $this->cur_week_day = date('w',mktime(0,0,0,$month,$day,$year));
			   $this->cur_month_num = date('n',mktime(0,0,0,$month,$day,$year));
			   $this->cur_year_num = date('Y',mktime(0,0,0,$month,$day,$year));
			   $this->cur_month_day_num = date('j',mktime(0,0,0,$month,$day,$year));
			   $this->num_first_day_in_week_on_cur_month = date('w',mktime(0,0,0,$month,1,$year));
			   $this->cur_month_day_quantity = date('t',mktime(0,0,0,$month,$day,$year));
			   $this->cur_month = date('m',mktime(0,0,0,$month,$day,$year));
		 //  }
//		   else{
//		       $this->back_cur_day = date('d.m.y_H.i.s');
//			   $this->cur_week_day = date('w');
//			   $this->cur_month_num = date('n');
//			   $this->cur_year_num = date('Y');
//			   $this->cur_month_day_num = date('j');
//			   $this->num_first_day_in_week_on_cur_month = date('w',time() - (date('j',time())-1)*86400);
//			   $this->cur_month_day_quantity = date('t');
//			   $this->cur_month = date('m');
//		   }
	   
	   }
   
   }
?>