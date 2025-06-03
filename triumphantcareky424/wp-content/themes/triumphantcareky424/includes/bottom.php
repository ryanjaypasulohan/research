<?php

unset($_SESSION);

@session_start();

require_once 'wp-content/themes/triumphantcareky424/forms/FormsClass.php';

$input = new FormsClass();



$formname = 'Send Us a Message Form';

$prompt_message = '<span style="color:#ff0000;"></span>';

require_once 'wp-content/themes/triumphantcareky424/forms/config.php';


if(isset($_POST['submit_info'])){
    
    	$robot = $_POST['Robot'];



	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");

	curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_POSTFIELDS, "secret={$recaptcha_privite}&response={$_POST['g-recaptcha-response']}");

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	$result_recaptcha = json_decode($server_output);

	curl_close ($ch);



	$_SESSION['Full_Name'] = (isset($_POST['Full_Name'])) ? $_POST['Full_Name'] : '';

  	$_SESSION['Email_Address'] = (isset($_POST['Email_Address'])) ? $_POST['Email_Address'] : '';

   $_SESSION['Question_or_Comment'] = (isset($_POST['Question_or_Comment'])) ? $_POST['Question_or_Comment'] : '';
   
   	$_SESSION['Robot'] = (isset($_POST['Robot'])) ? $_POST['Robot'] : '';

	

	if(empty($_POST['Full_Name']) ||

		empty($_POST['Email_Address'])

	) {



	$asterisk = '<span style="color:#FF0000; font-weight:bold;">*&nbsp;</span>';

	$prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>* Required Fields are empty.</span></p><br/><p class="error-close">x</p></div></div>';

	}

	else if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i",stripslashes(trim($_POST['Email_Address']))))

		{ $prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>Please enter a valid email address.</span></p><br/><p class="error-close">x</p></div></div>'; }

	else if(!$result_recaptcha->success)

		{ $prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>Invalid reCAPTCHA</span></p><br/><p class="error-close">x</p></div></div>';}
		
		else if (!empty($robot)) {
		$prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>YOU\'RE A MALICIOUS SPAMMER! STAY AWAY!</span></p><br/><p class="error-close">x</p></div></div>';
	}else if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",stripslashes(trim($_POST['Full_Name'])))){
		$prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>YOU\'RE A MALICIOUS SPAMMER! STAY AWAY!</span></p><br/><p class="error-close">x</p></div></div>';
	}else if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",stripslashes(trim($_POST['Question_or_Comment'])))){
		$prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>YOU\'RE A MALICIOUS SPAMMER! STAY AWAY!</span></p><br/><p class="error-close">x</p></div></div>';
	}else if(preg_match("/[+&@#\/%?=~_|!:,.;]/",stripslashes(trim($_POST['Full_Name'])))){
		$prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>YOU\'RE A MALICIOUS SPAMMER! STAY AWAY!</span></p><br/><p class="error-close">x</p></div></div>';
	}else if(preg_match("/(Bali|BALI|bali)/",stripslashes(trim($_POST['Full_Name'])))){
		$prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>YOU\'RE A MALICIOUS SPAMMER! STAY AWAY!</span></p><br/><p class="error-close">x</p></div></div>';
	}else if(preg_match("/(http|ftp|href)/",stripslashes(trim($_POST['Question_or_Comment'])))){
		$prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>YOU\'RE A MALICIOUS SPAMMER! STAY AWAY!</span></p><br/><p class="error-close">x</p></div></div>';
	}else if(preg_match("/(Bali|BALI|bali|Villa|VILLA|villa)/",stripslashes(trim($_POST['Question_or_Comment'])))){
		$prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>YOU\'RE A MALICIOUS SPAMMER! STAY AWAY!</span></p><br/><p class="error-close">x</p></div></div>';
	}else if(preg_match("/(bagat|store|ru|yourmail|bagat-1.ru|bagat-4.ru)/",stripslashes(trim($_POST['Email_Address'])))){
		$prompt_message = '<div id="error-msg"><div class="message"><p class="fail-check"><span>YOU\'RE A MALICIOUS SPAMMER! STAY AWAY!</span></p><br/><p class="error-close">x</p></div></div>';
		    
		}else{



		$body = '<div class="form_table" style="width:700px; height:auto; font-size:12px; color:#333333; letter-spacing:1px; line-height:20px; margin: 0 auto;">

			<div style="border:8px double #c3c3d0; padding:12px;">

			<div align="center" style="font-size:22px; font-family:Times New Roman, Times, serif; color:#051d38;">'.COMP_NAME.'</div>

			<div align="center" style="color:#990000; font-style:italic; font-size:20px; font-family:Arial; margin:bottom: 15px;">('.$formname.')</div>



			<table width="90%" cellspacing="2" cellpadding="5" align="center" style="font-family:Verdana; font-size:13px">

				';



			foreach($_POST as $key => $value){

				if($key == 'secode') continue;

				elseif($key == 'submit_info') continue;

                elseif($key == 'g-recaptcha-response') continue;

				

				if(!empty($value)){

					$key2 = str_replace('_', ' ', $key);

					if($value == ':') {

						$body .= '<tr><td colspan="2" style="background:#F0F0F0; line-height:30px"><b>'.$key2.'</b></td></tr>';

					}else {

						$body .= '<tr><td><b>'.$key2.'</b>:</td> <td>'.htmlspecialchars(trim($value), ENT_QUOTES).'</td></tr>';

					}

				}

			}

			$body .= '

			</table>



			</div>

			</div>';

		// for email notification
		include 'wp-content/themes/triumphantcareky424/forms/send_email_curl.php';	


		// save data form on database

		include 'wp-content/themes/triumphantcareky424/forms/savedb2.php';



		// save data form on database

		$subject = $formname ;

		$attachments = array();



	 	//name of sender

		$name = $_POST['Full_Name'];

		$result = insertDB($name,$subject,$body,$attachments);

    	$parameter = array(
			'body' => $body,
			'from' => $from_email,
			'from_name' => $from_name,
			'to' => $to_email,
			'subject' => 'New Message Notification',	
			'attachment' => $attachments
		);

		$prompt_message = send_email($parameter);
		unset($_POST);
	}

}

/*************declaration starts here************/



?>



<?php echo $prompt_message; ?>



<!-- Bottom -->

<div id="bottom1">

    <div class="wrapper animatedParent animateOnce">

        <div class="btm1_main">



        <div class="btm1_info">

        <?php dynamic_sidebar('btm1_info');?>

        </div>



        <figure class="btm1_image"><img src="<?php bloginfo('template_url');?>/images/btm1-img.png" alt="nurse using mobile phone"/></figure>





        <div class="btm1_box1">

        <?php dynamic_sidebar('btm1_box1');?>

        </div>



        </div>

        	<div class="clearfix"></div>

    </div>

</div>



<div id="bottom2">

    <div class="wrapper animatedParent animateOnce">

        <div class="btm2_main">



        <div class="btm2_holder">

        <?php dynamic_sidebar('btm2_holder');?>

        </div>



        <div class="btm2_info">

        <?php dynamic_sidebar('btm2_info');?>

        </div>







        </div>

        	<div class="clearfix"></div>

    </div>

</div>



<div id="bottom3">

    <div class="wrapper animatedParent animateOnce">

    <div class="btm3_main">



        <div class="btm3_holder">

            <section class="btm3_box1">

            <div class="btm3_cont">

            <?php dynamic_sidebar('btm3_box1');?>

            </div>

            </section>



            <section class="btm3_box2">

            <div class="btm3_cont">

            <?php dynamic_sidebar('btm3_box2');?>

            </div>

            </section>



            <section class="btm3_box3">

            <div class="btm3_cont">

            <?php dynamic_sidebar('btm3_box3');?>

            </div>

            </section>

        </div>



        </div>

        	<div class="clearfix"></div>

    </div>

</div>



<div id="bottom4">

    <div class="wrapper animatedParent animateOnce">

    <div class="btm4_main">



    <div class="btm4_info">

	<?php dynamic_sidebar('btm4_info');?>

    </div>


	<div id="invalid-msg"></div>
    <form class="form_btn" action="#" method="post" id="submit_formmessage" >

		<input type="text" name="Robot" placeholder="Spam" value="<?php echo $_SESSION['Robot']; ?>" style="display:none;">		

    <input type="text" class="form_fullname" value="<?php echo $_SESSION['Full_Name']; ?>" name="Full_Name" placeholder="*Full Name" required="">

	<input type="email" class="form_email" value="<?php echo $_SESSION['Email_Address']; ?>" name="Email_Address" placeholder="*Email Address" required="">

	<textarea name="Question_or_Comment" class="textarea" placeholder="Message(s)"><?php echo $_SESSION['Question_or_Comment']; ?></textarea>

	<div class="captcha-box"><div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_sitekey; ?>"></div></div>

    <input type="submit" class="submitform" name="submit_info" value="Submit">

	</form>



        </div>

        	<div class="clearfix"></div>

    </div>

</div>





<!-- End Bottom -->

