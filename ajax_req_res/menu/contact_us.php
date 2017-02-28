<?php
sleep(1); // to test ajax loading is showing, this sleep function will be removed in future. 
include('../../database/config.php');
include('../../function/common.php');
date_default_timezone_set('America/Chicago');
//echo $_POST['email'];
//exit;
$error_msg=array();
$error_msg_ind=0;
//echo "OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO";
//exit;
$link=db_connect();
if($_POST)
{
	
	$email=mysqli_real_escape_string($link,(strtolower($_POST['email'])));
	$paragraph=mysqli_real_escape_string($link,(strtolower($_POST['paragraph'])));
	$title=mysqli_real_escape_string($link,(strtolower($_POST['title'])));
	$written_by=mysqli_real_escape_string($link,(strtolower($_POST['name'])));
	$ip=$_SERVER['REMOTE_ADDR'];

	$p_word_limit=5000;
	$p_char_limit=20000;
	$t_char_limit=200;
	$n_char_limit=60;

	$p_word_limit_show=5000;
	$p_char_limit_show=20000;
	$t_char_limit_show=200;
	$n_char_limit_show=60;

	//echo "strlen:".strlen($paragraph)." MB:".mb_strlen($paragraph, 'utf-8');
	// if they are not equal then it has multibyte characters [other language..in that case strlen($paragraph) will be around three times],,
	if(strlen($paragraph) != mb_strlen($paragraph, 'utf-8'))
	{
		$paragraph_is_english=0;
		$p_word_limit=15000;
		$p_char_limit=60000;
	
		//$p_char_limit_show=20000;
	}
	if(strlen($title) != mb_strlen($title, 'utf-8'))
	{	
		$title_is_english=0;
		//$t_word_limit=5000;
		$t_char_limit=600;
		//$t_char_limit_show=50;
		
	}
	if(strlen($written_by) != mb_strlen($written_by, 'utf-8'))
	{
		$name_is_english=0;
		$n_char_limit=200;
		//$n_char_limit_show=200;
	}
	//echo "para:".$paragraph_is_english."title:".$title_is_english."name:".$name_is_english;
	//exit;
	//mysql text dta type can hold 65535 bytes...which is equivalent to around 21844 utf characters.. 
	if(str_word_count($paragraph,0)>$p_word_limit)
	{
		$error_msg[$error_msg_ind]="Your message need to be within $p_word_limit_show words.";
		$error_msg_ind++;
	}
	/*
	if(str_word_count($paragraph,0)<1)
	{
		$error_msg[$error_msg_ind]="Your writing need to be at least one word long.";
		$error_msg_ind++;
	}

	*/

	if(strlen($paragraph)<2)
	{
		$error_msg[$error_msg_ind]="Your message  need to be at least two character long.";
		$error_msg_ind++;
	}
	if(strlen($paragraph)>$p_char_limit)
	{
		$error_msg[$error_msg_ind]="Your message need to be within $p_char_limit_show characters.";
		$error_msg_ind++;
	}
	/*
	if(str_word_count($title,0)<1)
	{
		$error_msg[$error_msg_ind]="Your writings title need to be at least one word long.";
		$error_msg_ind++;
	}
	*/
	//echo strlen($title).exit;
	if(strlen($title)<2)
	{
		$error_msg[$error_msg_ind]="Your message subject need to be at least two character long.";
		$error_msg_ind++;
	}
	//echo $title; exit;
	if(strlen($title)>$t_char_limit)
	{
		$error_msg[$error_msg_ind]="Your message subject need to be within $t_char_limit_show characters.";
		$error_msg_ind++;
	}

	if(strlen($email)==0)
	{
		$error_msg[$error_msg_ind]="Please provide your email address.";
		$error_msg_ind++;
	}else{
		//echo $email; exit;
		if (strlen($email)>0 && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		 $error_msg[$error_msg_ind]="Your email format is not valid.";
		 $error_msg_ind++;
		}
		if(strlen($email)>50)
		{
			$error_msg[$error_msg_ind]="Your email  need to be within 50 characters.";
			$error_msg_ind++;
		}
	}
	
	if(count($error_msg)>0)
	{		//echo "okf";exit;
		$error_string='';
		$error_string.= "<br />"."<b>Please correct errors below: </b>";
		foreach($error_msg as $key => $value)
		{	
			$error_string.= "<li><i>$value</i></li>"."<br />";
		}
		
		echo "<script type='text/javascript'>
			  document.getElementById('contact_us_posting').innerHTML='';
			  document.getElementById('contact_us_posting').innerHTML='".$error_string."';		  
			</script>"; 
		//echo $error_string;
		exit;
		
	}else{
		//echo $written_by; exit;
		$sql="INSERT INTO `contact_us` (`id`, `message`, `name`,`email`,`subject`, `created`,`ip`)
		VALUES (?,?,?,?,?,?,?)";
		if($stmt=$link->prepare($sql))
		{	//echo "ok";exit;
			$id1=NULL;
			$created=date('Y-m-d H:i:s');
			$stmt->bind_param("issssss",$id1, $paragraph, $written_by, $email,$title, $created,$ip); //bind variable to prepared stmt
			$stmt->execute(); // execute query
			if($stmt->affected_rows>0)
			{		
				$posted_id=$stmt->insert_id;
				$stmt->close();
				
				//send email
				
					
				 $error_string="We have received your message.We will contact with you shortly.";
				echo "<script type='text/javascript'>
				  document.getElementById('contact_us_posting').innerHTML='';
				  document.getElementById('contact_us_posting').innerHTML='".$error_string."';		  
				</script>"; 
				$headers = "From: admin@vaguetext.com" ."\r\n" .
						"CC: sislam42@students.tntech.edu";
	
				$email_msg="$title \n $paragraph \n -- $written_by \n $email";
				$email_msg= wordwrap($email_msg,70);
				mail($email,"Contact us: vaguetext.com",$email_msg,$headers);
			}else{
				 $error_string="Someting wrong. Please try again later.";
				echo "<script type='text/javascript'>
				  document.getElementById('contact_us_posting').innerHTML='';
				  document.getElementById('contact_us_posting').innerHTML='".$error_string."';		  
				</script>"; exit;
			}		
		}else{
			echo "someting wrong"; exit;
		}		
			
	}
db_close($link);
}
?>
