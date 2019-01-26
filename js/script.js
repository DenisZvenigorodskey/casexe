$(document).ready(
	function(){

		$('.auth .button.enter').live('click',
			function(){					
				$.post('ajax.php', {
					sw: 'auth',
					login: $('.auth .login').val(),
            		passwd: $('.auth .passwd').val(),
					}, function(q){
						if(q == 1){
							$('.auth .msg').hide();
							location.reload();
						}else{
							$('.auth .msg').html(q).show('fast');
						}
					}
				);	
			}
		);

		$('.auth .button.lucky').live('click',
			function(){					
				$.post('ajax.php', {
					sw: 'lucky'					
					}, function(q){					
						$.post('ajax.php', {
							sw: 'readMyPriz'					
							}, function(w){					
								$('.auth .r .str').html(w).show();					
								$('.auth .msg').html(q).show('fast');
							}
						);	
						
					}
				);	
			}
		);

	$('.auth .button.send_poshta').live('click',
			function(){					
				$.post('ajax.php', {
					sw: 'send_poshta'					
					}, function(q){						
						$.post('ajax.php', {
							sw: 'readMyPriz'					
							}, function(w){					
								$('.auth .r .str').html(w).show();
								$('.auth .msg').html(q).show('fast');
								console.log(q);
							}
						);	
					}
				);	
			}
		);
		
		$('.auth .button.transfer').live('click',
			function(){					
				$.post('ajax.php', {
					sw: 'transfer'					
					}, function(q){						
						$.post('ajax.php', {
							sw: 'readMyPriz'					
							}, function(w){					
								$('.auth .r .str').html(w).show();
								$('.auth .msg').html(q).show('fast');
								console.log(q);
							}
						);	
					}
				);	
			}
		);

		$('.auth .button.cancel').live('click',
			function(){					
				$.post('ajax.php', {
					sw: 'cancel'					
					}, function(q){						
						$.post('ajax.php', {
							sw: 'readMyPriz'					
							}, function(w){					
								$('.auth .r .str').html(w).show();
								$('.auth .msg').html(q).show('fast');
								console.log(q);
							}
						);	
					}
				);	
			}
		);

		$('.auth .button.convert').live('click',
			function(){					
				$.post('ajax.php', {
					sw: 'convert'					
					}, function(q){						
						$.post('ajax.php', {
							sw: 'readMyPriz'					
							}, function(w){					
								$('.auth .r .str').html(w).show();
								$('.auth .msg').html(q).show('fast');
								console.log(q);
							}
						);	
					}
				);	
			}
		);


	}
);