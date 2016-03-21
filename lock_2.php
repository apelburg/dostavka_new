<?php
     session_start();
     $auth_pairs = array('driveservice' => '9500fdffc91fbf76ec35fb146470abc3');//apelburg2000
	 
	 if(empty($_SESSION['auth']) && empty($_COOKIE['auth']) && empty($_POST['auth_data'])){
	       header("Location: http://".$_SERVER['HTTP_HOST']);
		   //include('auth.tpl');
		   exit;
	 }
	 if(!empty($_POST['auth_data'])){
		  foreach($auth_pairs as $login => $password){
			  if($_POST['auth_data']['login'] === (string)$login && md5($_POST['auth_data']['password']) === (string)$password){
				  $auth_key = true; 
			  }
		  }	 
		  if($auth_key){
			  $_SESSION['auth'] = true;
			  if($_POST['auth_data']['save_auth'] == 'on') setcookie('auth',true, time() + 30*84600);
			  header('location:'.$_SERVER['REQUEST_URI']);
			  exit;
		  }
		  else{ 
			 include('auth.tpl');
			 exit;
		  }
		  
		 
	 }
	
	if(isset($_GET['auth_out'])){
			if(isset($_SESSION['auth']))unset($_SESSION['auth']);
			if(isset($_SESSION['access']))unset($_SESSION['access']);
			if(isset($_SESSION['admin_key']))unset($_SESSION['admin_key']);	
			if(isset($_SESSION['tune_key']))unset($_SESSION['tune_key']);
				
		
		  setcookie('auth',true, time() -100);
		  //header('location:/dostavka/');
		  header('location:../');
		  exit;
	 }
?>