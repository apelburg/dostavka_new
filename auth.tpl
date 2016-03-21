<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title></title>
<style type="text/css">
html{
    height:100%;
}
body{
    margin:0px;
	height:100%;
	background:#DDDDDD;
	
}
</style>
</head>

<body>
<table width='100%' height='100%' border='0' align='center' valign='middle'>
	<tr>
		<td align='center' valign='middle'>
		<form action='' method='POST'>
		<table cellspacing='0' cellpadding='7' border='0'>
		  <tr>
		     <td style='background-color:#D4D0C8;border-top:#C0C0C0 outset 2px; border-left:#C0C0C0 outset 2px;'>
                &nbsp;логин
             </td>
			 <td style='background-color:#D4D0C8;border-top:#C0C0C0 outset 2px; border-right:#C0C0C0 outset 2px;'>
                <input type='text' name="auth_data[login]" style='background:#EEEEEE;'>
             </td>
		  </tr>
		  <tr>
			<td style='background-color:#D4D0C8;border-left:#C0C0C0 outset 2px;'>
               &nbsp;пароль
            </td>
			<td style='background-color:#D4D0C8;border-right:#C0C0C0 outset 2px;'>
               <input type='password' name="auth_data[password]" style='background:#EEEEEE;'>
            </td>
		  </tr>
           <tr>
			<td style='background-color:#D4D0C8;border-bottom:#C0C0C0 outset 2px; border-left:#C0C0C0 outset 2px;' align="right" valign="top">
               <input type="checkbox" name="auth_data[save_auth]">
            </td>
			<td style='background-color:#D4D0C8;border-bottom:#C0C0C0 outset 2px; border-right:#C0C0C0 outset 2px;' valign="top">
               &nbsp;запомнить меня
            </td>
		  </tr>
		  <tr>
		    <td><input type='hidden' name='session_id' value='<?php echo session_id(); ?>'></td>
			<td align='right'><button type='submit'>войти</button></td>
	      </tr>
        </table>
		</form>
		</td>
	</tr>
</table>
</body>
</html>
