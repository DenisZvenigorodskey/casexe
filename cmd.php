<?php
/* 
	Не понятно по ТЗ
	"Нужно добавить консольную команду которая будет отправлять денежные призы на счета пользователей, которые еще не были отправлены пачками по N штук.""
	
	1. Создать консольную комманду на фреймворке (но в пункте 1 указано "в PHP 5.6+ без использования фреймворков" )
	2. Создать команду для запуска из терминала, например из Bash. 
	
	Выбрал второй вариант.

*/

	include ("bd.php"); // connect mySQL
	include ("core.php"); // function

	$users = returnUsers();	
	$N_TransferTranzaction = 3;
	echo "\n\nFind ".count($users)." user(s)\n";

	if(@$users){
		foreach ($users as $key => $value) {
			echo "\n\n Check money on UserID ".$value['id']." ";
			
			$money_this_user_ar = returnUserMoney($value['id'], $N_TransferTranzaction);
			if($money_this_user_ar){
				echo " [have MONEY]\n";
			}else{
				echo " [no MONEY]\n";
			}
			// die();
			foreach ($money_this_user_ar as $keyU => $valueU) {
				transferN_Priz($valueU);
			}
			// print_r($money_this_user_ar);
		}
	}

	echo "\n\n";

?>