<?
$hostname = "localhost";
$username = "casexe";
$password = "";
$dbName = "casexe";

$connectBD = MYSQL_CONNECT($hostname,$username,$password) or die("<table border=1><tr><td bgcolor=yellow><b>Error:</b></td></tr><tr><td bgcolor=lightgrey><font color=red><b>Нет соединения с базой данных хостинга!</b></font></td></tr>");
@mysql_select_db("$dbName") or die("Не могу выбрать базу данных "); 
mysql_query('SET NAMES UTF8');
?>