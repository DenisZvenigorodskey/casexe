<?

function returnUserMoney($user_id, $N){
	$ar = array();
	
	$readPrizBD = readPrizBD($user_id);
	$countN = ceil(count($readPrizBD['Money'])/$N);
	
	if($countN > 0){
		$c = 0; $cc = 0; $ccc = 0;
		foreach ($readPrizBD['Money'] as $key => $value) {
			if($value['Status'] != 'TrasferTrue'){
				$ar[$c][$ccc] = $value;
				$ar[$c][$ccc]['Index'] = $key;
				$ar[$c][$ccc]['UserID'] = $user_id;
				$cc++;
				$ccc++;
				if($cc == $N){$cc = 0;$c++;$ccc=0;}
			}

		}
	}	

	return $ar;
	
}

function returnUsers(){
	$u = array();
	$query = query("SELECT * FROM `Users`  WHERE 1");		
	while($row = mysql_fetch_array($query)){
		$u[] = $row;
	}	

	return $u;
}

function query($query) { 
	$query = @mysql_query($query) or die(mysql_error());; 		
	return $query; 
} 

function sql_guard($method, $query, $type)
{
    if ($method == 'POST')
        $safe_text = ($type == 'int') ? intval($_POST["$query"]) : addslashes($_POST["$query"]);
    elseif ($method == "GET")
        $safe_text = ($type == 'int') ? intval($_GET["$query"]) : addslashes($_GET["$query"]);
    else
        $safe_text = ($type == 'int') ? intval($_REQUEST["$query"]) : addslashes($_REQUEST["$query"]);

    return $safe_text;
}


class LiqPay {
     public function __construct( array $cfg){
        foreach($cfg as $k=>$v){
            $this->{$k}=$v;
        }
    }
    
    public function __call( $fn, array $args){
    	print_r($this->{$fn});
        if(isset($this->{$fn})){  
        	$this->{$fn} =  (object) $args;    	            
        }
    }
}

function sendCurl($url, $res){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $res);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($ch);
	curl_close($ch);

	return $response;
}

function ChangeMoneyStatus($ar, $user_id){
	$readPrizBD = readPrizBD($user_id);
	foreach ($ar as $key => $value) {
		$readPrizBD['Money'][$value['Index']]['Status'] = $value['BankStatus'];		
	}
		
	writePrizArray($readPrizBD, $user_id);
}

function transferN_Priz($arPrizS){		
	
	foreach ($arPrizS as $key => $value) {		
		$liqpay = new LiqPay(array('api' => '', 'privat_key' => 123456, 'public_key' => 789456));						
		$res = $liqpay->api("request", array(
			'action'         => 'p2p',
			'version'        => '3',
			'phone'          => '380950000001',
			'amount'         => $value['Value'],
			'currency'       => 'CZK',
			'description'    => 'description text',
			'order_id'       => 'order_id_'.$value['Index'],
			'receiver_card'  => '4731195301524633',
			'card'           => '4731195301524634',
			'card_exp_month' => '03',
			'card_exp_year'  => '22',
			'card_cvv'       => '111'
		));			
		$response = sendCurl('https://shop-galaxy.com.ua/', $res);
		
		if($response){	
			$arPrizS[$key]['BankStatus'] = 'TrasferTrue';		
			echo "\n [transaction id ".$value['Index']."] Money transfer OK ";			 	
		}else{
			$arPrizS[$key]['BankStatus'] = 'TrasferFalse';
			echo "\n [".$value['Index']."] Money transfer False ";			 	
		}

	}	

	ChangeMoneyStatus($arPrizS, $value['UserID']);	 	

	

	
}

function transfertLasrPriz(){
	$ar = readPrizBD();

	$liqpay = new LiqPay(array('api' => '', 'privat_key' => 123456, 'public_key' => 789456));
	$res = $liqpay->api("request", array(
		'action'         => 'p2p',
		'version'        => '3',
		'phone'          => '380950000001',
		'amount'         => $ar['LastResult']['Value'],
		'currency'       => 'CZK',
		'description'    => 'description text',
		'order_id'       => 'order_id_1',
		'receiver_card'  => '4731195301524633',
		'card'           => '4731195301524634',
		'card_exp_month' => '03',
		'card_exp_year'  => '22',
		'card_cvv'       => '111'
	));
	
	$response = sendCurl('https://shop-galaxy.com.ua/', $res);
	

	if($response){
		unset($ar[$ar['LastResult']['Type']][$ar['LastResult']['Pos']]);	
		writePrizArray($ar);
		echo "Sum ".$ar['LastResult']['Value']."CZK is transferred to a bank account in your bank.";
	}
	
}

function sendPoshtaLasrPriz(){
	$ar = readPrizBD();	
	unset($ar[$ar['LastResult']['Type']][$ar['LastResult']['Value']][$ar['LastResult']['Pos']]);	
	writePrizArray($ar);
	echo "<div style='margin-top: 70px;'>The prize will be sent by mail.</div>";	
}

function convertLasrPriz(){
	$k = 1.6; // coefficient for transfer to Bonus
	$ar = readPrizBD();
	$sum = $ar['LastResult']['Value']/$k;
	unset($ar[$ar['LastResult']['Type']][$ar['LastResult']['Pos']]);	
	$iCount = @count($ar['Bonus']);
	$ar['Bonus'][$iCount]['Value']  = $sum;
	$ar['Bonus'][$iCount]['Status'] = 'Active';
	writePrizArray($ar);
	echo "<div style='margin-top: 70px;'>Cash prize transferred to Bonus ($sum)</div>";	
}

function cancelLasrPriz(){
	$ar = readPrizBD();

	if(@$ar['LastResult']['Type'] == 'Item'){		
		if(@$ar[$ar['LastResult']['Type']][$ar['LastResult']['Value']][$ar['LastResult']['Pos']]){ // удаляем если есть такой приз
			unset($ar[$ar['LastResult']['Type']][$ar['LastResult']['Value']][$ar['LastResult']['Pos']]);	
			writePrizArray($ar);
			echo "<div style='margin-top: 70px;'>You <b> successfully </ b> have refused a prize.</div>";
		}else{
			echo "<div style='margin-top: 70px;'>You have already rejected the prize <b> earlier </ b>.</div>";
		}
	}else{
		if(@$ar[$ar['LastResult']['Type']][$ar['LastResult']['Pos']]){ // удаляем если есть такой приз
			unset($ar[$ar['LastResult']['Type']][$ar['LastResult']['Pos']]);	
			writePrizArray($ar);
			echo "<div style='margin-top: 70px;'>You <b> successfully </ b> have refused a prize.</div>";
		}else{
			echo "<div style='margin-top: 70px;'>You have already rejected the prize <b> earlier </ b>.</div>";
		}
		
	}

}

function readPrizBD($user_id = 0){
	if(@!$user_id){$user_id = $_SESSION['id'];}
	$query = query("SELECT `Cart` FROM `Lottery`  WHERE `UserID` = '".$user_id."'");
	$row = mysql_fetch_array($query);
	return unserialize($row['Cart']);
}

function writePrizArray($ar, $user_id = 0){
	if(@!$user_id){$user_id = $_SESSION['id'];}
	query("UPDATE `Lottery` SET  
			`Cart` = '".serialize($ar)."'				
			 WHERE `UserID` = '".$user_id."'");
}

function addPrizBD($giftAr){
	$ar = readPrizBD();

	if($giftAr['Select']['Type'] == 'Item'){
		$iCount = @count($ar[$giftAr['Select']['Type']][$giftAr['Select']['Value']]);		
		$ar[$giftAr['Select']['Type']][$giftAr['Select']['Value']][$iCount]['Value']  = 1;
		$ar[$giftAr['Select']['Type']][$giftAr['Select']['Value']][$iCount]['Status'] = 'Active';
	}else{
		$iCount = @count($ar[$giftAr['Select']['Type']]);
		$ar[$giftAr['Select']['Type']][$iCount]['Value']  = $giftAr['Select']['Value'];
		$ar[$giftAr['Select']['Type']][$iCount]['Status'] = 'Active';
	}

	$ar['LastResult']['Type'] = $giftAr['Select']['Type'];
	$ar['LastResult']['Pos'] = $iCount;
	$ar['LastResult']['Value'] = $giftAr['Select']['Value'];

	writePrizArray($ar);
}

function ReturnGift($min, $max, $ThisValue, $Limit, $giftAr_Type){ // random choice of prize type
	// $Limit['Item']['Lollipop'] = 10000;
	// $Limit['Item']['Bedge'] = 10000;
	// $Limit['Money'] = 10000;	
	$Priz_IsRandomOK = array();	
	$rand = array();
	
	/* отмечаем призы которые участвуют в розагрыше */
	foreach ($Limit as $key => $value) {		
		if($key == 'Item'){			
			foreach ($value as $keyI => $valueI) {			
				if($valueI != 0 && @$ThisValue[$keyI] < $valueI){					
					$Priz_IsRandomOK['Item'][$keyI] = 1;
				}
			}
		}else{			
			if($value[0] == 0 || $ThisValue[$key] < $value[0]){
				$Priz_IsRandomOK[$key] = 1;
			}
		}			
	}

	$rand_key = array_rand($Priz_IsRandomOK, 1);	

	if($rand_key == 'Item'){
		$rand= array("Item" => array(array_rand($Priz_IsRandomOK['Item'], 1) => 1));
	}else{
		$rand = array($rand_key => mt_rand (1, 50));
	}
	return $rand;
}

function runLucky(){	
	/* current values */
	$readPrizBD = readPrizBD();
	$ThisValue['Money'] 		= @returnSummPrizType($readPrizBD['Money']);
	$ThisValue['Lollipop'] 		= @returnSummPrizType($readPrizBD['Item']['Lollipop']);
	$ThisValue['Badge'] 		= @returnSummPrizType($readPrizBD['Item']['Badge']);
	$ThisValue['SomethingElse'] = @returnSummPrizType($readPrizBD['Item']['SomethingElse']);

	/* Limits */
	$Limit['Money'][] = 100;
	$Limit['Bonus'][] = 0;
	$Limit['Item']['Lollipop'] = 5;
	$Limit['Item']['Badge'] = 10;
	$Limit['Item']['SomethingElse'] = 11;


	/* Types of prizes */
	$giftAr['Type'][] = 'Money';
	$giftAr['Type'][] = 'Bonus';
	$giftAr['Type'][] = 'Item';
	
	$rands = ReturnGift(0, count($giftAr['Type'])-1, $ThisValue, $Limit, $giftAr['Type']);
	$ReturnGiftType = key($rands);
	
	/* наполнение значений типов призов */
	if(key($rands) == 'Item'){
		$giftAr['Value'][$ReturnGiftType] = $rands[$ReturnGiftType] = key($rands[$ReturnGiftType]); 
	}else{
		$giftAr['Value'][$ReturnGiftType] = $rands[$ReturnGiftType]; 
	}
		
	/* Types of random items */
	$giftAr['Item']['Type'][] = 'Lollipop';
	$giftAr['Item']['Type'][] = 'Badge';
	$giftAr['Item']['Type'][] = 'SomethingElse';
	
	/* Language */
	$giftAr['Lang']['Type']['Money'] 			= 'Money';
	$giftAr['Lang']['Type']['Bonus'] 			= 'Bonus';
	$giftAr['Lang']['Type']['Item'] 			= 'Item';
	$giftAr['Lang']['Item']['Lollipop'] 		= 'Lollipop';
	$giftAr['Lang']['Item']['Badge']		 	= 'Bedge';
	$giftAr['Lang']['Item']['SomethingElse'] 	= 'Somthing else';

	$giftAr['Select']['Type']  = $ReturnGiftType;
	$giftAr['Select']['Value'] = $giftAr['Value'][$ReturnGiftType];
	
	return $giftAr;
	

}

function returnSummPrizType($ar){
	$sum = 0;	

	if(@$ar){
		foreach ($ar as $key => $value) {
			if($value['Status'] != 'TrasferTrue'){
				$sum = $value['Value'] + $sum;
			}
		}
	}				

	return $sum;
}

function ReturnMyGiftList(){
	$readPrizBD = readPrizBD();
	$giftAr =   runLucky();

	foreach ($giftAr['Type'] as $keyT => $valueT) {
		echo "<span>".$giftAr['Lang']['Type'][$valueT];
		
		if(@$giftAr[@$valueT]){
			
			?><ul><?
			foreach ($giftAr['Item']['Type'] as $key => $value) {
				if(@!$readPrizBD['Item'][$value]){$readPrizBD['Item'][$value] = 0;}
				echo "<li><span>".$giftAr['Lang']['Item'][$value]."</span>: ".returnSummPrizType($readPrizBD['Item'][$value])."</li>";
			}
			?></ul><?

		}else{
			if(@!$readPrizBD[$valueT]){$readPrizBD[$valueT] = 0;}
			echo ": ".returnSummPrizType($readPrizBD[$valueT]);
		}
		echo "</span>";		
	}		

	
}

function SysPage_ShowRafflePrizes(){

	?>	

		<div class='auth ok'>
			<div><span class="large button green lucky">I'm lucky</span></div>
			<div class='l'>
				<div class="msg">bla</div>			
				<div class="str">
					<span></span>
					
				</div>
			</div>
			<div class='r'>
				<h2>My prizes</h2>
				<div class="str">
					<?ReturnMyGiftList()?>					
				</div>
			</div>

			
		</div>
	<?
}

function SysPage_ShowAuth(){
	?>	
		<div class='auth'>
			<div class="msg">bla</div>
			<div class="str">
				<span>Login:</span>
				<span><input type="text" class='login' value="user1@casexe.com"></span>
			</div>
			<div class="str">
				<span>Password:</span>
				<span><input type="password" class='passwd' value='password1'></span>
			</div>
			<div class="str">
				<span></span>
				<span class="large button blue enter" >Enter</span>
			</div>
		</div>
	<?
}

function SysPage_ShowHeader($param = ""){
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>		
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<meta name="robots" content="index, follow" />			
		<title>Casexe (TA)</title> 		
		<script type="text/javascript"  src="js/base.js"></script>
		<script type="text/javascript"  src="js/script.js"></script>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
	<?
}

function SysPage_ShowFooter($param = ""){
	?>
	</body>
	</html>
	<?
}
?>