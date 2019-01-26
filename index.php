<?
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('error_reporting',  E_ALL);
set_time_limit(10);

// user1@casexe.com | password1 | Active
// user2@casexe.com | password2 | Blocked
// user3@casexe.com | password3 | Active

include ("bd.php"); // connect mySQL
include ("core.php"); // function

@session_start();
$urlAr = explode("/",ltrim($_SERVER["REQUEST_URI"],"/"));

if($urlAr[0] == 'logout'){session_destroy();session_start();} // logout
  
  // session_destroy();session_start();

SysPage_ShowHeader();

if(@!$_SESSION['id']){ // check auth
  SysPage_ShowAuth();
}else{
  SysPage_ShowRafflePrizes();
}




SysPage_ShowFooter();


?>