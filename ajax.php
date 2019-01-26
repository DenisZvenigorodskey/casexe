<?
include ("bd.php"); // connect mySQL
include ("core.php"); // function
@session_start();
$sw = @$_POST['sw'];

switch ($sw) {
	case 'auth':
		$login = sql_guard('POST', 'login', '');
		$passwd = md5(md5(sql_guard('POST', 'passwd', '')));

		$query = query("SELECT * FROM `Users`  WHERE `login`='{$login}' AND `passwd` ='{$passwd}' LIMIT 1");		
		$row = mysql_fetch_array($query);	
		if ($row['id']){ 
			
			if($row['Status'] == 1){			
				$_SESSION['login'] = $login;
				$_SESSION['id'] = $row['id'];
				$_SESSION['status'] = $row['Status'];
				

				echo "1";
			}else{
				echo "<span class='err'>User is blocked.</spa>";	
			}

			
		}else{
			echo "<span class='err'>Error. Invalid username or password.</span>";	
		}	
		break;

	case 'lucky':
		$giftAr = runLucky();
		
		/* write a prize in a DB */
		addPrizBD($giftAr);
		
		echo "<h2>Мой приз</h2>";
		switch ($giftAr['Select']['Type']) {
			case 'Money':
				echo $giftAr['Lang']['Type'][$giftAr['Select']['Type']]." - ".$giftAr['Select']['Value'];
				echo '<br /><br /><span class="small button green transfer">Transfer the prize <br/> to the bank account</span> ';
				echo '<span class="small button orange convert">Convert <br /> to Bonus</span>';
				echo '<br /><br /><span class="small button red cancel">Discard the prize</span>';
				break;

			case 'Bonus':
				echo $giftAr['Lang']['Type'][$giftAr['Select']['Type']]." - ".$giftAr['Select']['Value'];
				echo '<br /><br />Points credited';

				echo '<br /><br /><span class="small button red cancel">Discard the prize</span>';
				break;

			case 'Item':
				echo $giftAr['Lang']['Type'][$giftAr['Select']['Type']]." - ".$giftAr['Lang']['Item'][$giftAr['Select']['Value']];
				echo '<br /><br /><span class="small button blue send_poshta">Send by mail</span>';

				echo '<br /><br /><span class="small button red cancel">Discard the prize</span>';
				break;
			
			default:
			 	echo "Error";
				break;
		}		
		break;

	case 'send_poshta':
		sendPoshtaLasrPriz();
		break;

	case 'transfer':
		transfertLasrPriz();
		break;

	case 'convert':
		convertLasrPriz();
		break;

	case 'cancel': 
		cancelLasrPriz();
		break;

	case 'readMyPriz':
		ReturnMyGiftList();		
		break;

	default:
		echo "<span class='err'>Error. No switch.";
		break;
}
?>