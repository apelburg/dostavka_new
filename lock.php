<?php
     	
   $auth = array('karta' => 'online');//md5()

   if(empty($_SERVER['PHP_AUTH_USER'])){
        header ('WWW-Authenticate: Basic realm="Admin Page"'); //     realm="заголовок окна"
        header ('HTTP/1.0 401 Unauthorized');
        exit;
   }

   foreach($auth as $user => $pass){// разбираем массив $auth хранящий логины и пароли
       if($_SERVER['PHP_AUTH_USER'] === $user && $_SERVER['PHP_AUTH_PW'] === $pass) $кеу = true;
       else $key = false;
   }


   if(!$кеу){
        header ('WWW-Authenticate: Basic realm="Admin Page"'); //     realm="заголовок окна"
        header ('HTTP/1.0 401 Unauthorized');
        exit;
   }


?>