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
$ip=$_SERVER['REMOTE_ADDR'];


	if(strlen($email)==0)
	{
		$error_msg[$error_msg_ind]="Please provide your email address to reset your password.";
		$error_msg_ind++;
	}else{
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
{	
	$error_string='';
	$error_string.= "<br />"."<b>Please correct errors below: </b>";
	foreach($error_msg as $key => $value)
	{	
		$error_string.= "<li><i>$value</i></li>"."<br />";
	}
	
	echo "<script type='text/javascript'>
		  document.getElementById('forget_password_posting').innerHTML='';
		  document.getElementById('forget_password_posting').innerHTML='".$error_string."';		  
		</script>"; 
	//echo $error_string;
	exit;
	
}else{
	//echo "okk";exit;
	$email_db='';
	$nick_db='';
	$id_db='';
	
	$sql="select id,nickname,email from `nicks` where `email`=?";
	if($stmt=$link->prepare($sql))
	{	
		$stmt->bind_param("s",$email); //bind variable to prepared stmt
		$stmt->execute(); // execute query 
		$result=$stmt->store_result(); // Store the whole result in buffer(to get properties)
		$num_rows=$stmt->num_rows;
		//bind result to variables
		$stmt->bind_result($id_db, $nick_db, $email_db);
		if($num_rows>0)
		{
			$stmt->fetch();
			//echo $email_db;
			$rand=rand(9999,99999999);
			$rand2=urlencode(encrypt($rand));
			$v2=urlencode(encrypt($id_db));
			$expired=date('Y-m-d', strtotime("+1 week"));
			$sql2="update `nicks` set `pass_reset_key`=?,`pass_reset_key_expired`=? where `id`=?"; 

			if($stmt2=$link->prepare($sql2))
			{	
			
				$stmt2->bind_param("ssi",$rand, $expired, $id_db); //bind variable to prepared stmt
				$stmt2->execute(); // execute query
				if($stmt2->affected_rows>0)
				{		
					$headers = "From: admin@vaguetext.com" . "\r\n" ;
					$email_msg="Hey $nick_db, \n Please click this link : http://www.vaguetext.com/menu/?section=Account&ac=pr&v1=$rand2&v2=$v2 and follow the instruction to reset your password.\n\n -- Thanks,\n www.vaguetext.com";
					$email_msg= wordwrap($email_msg,70);
					if(mail($email,"Password reset: vaguetext.com",$email_msg,$headers))
					{
						 $error_string="Please check your email for password reset link. The link is valid for next one week.";
						echo "<script type='text/javascript'>
						  document.getElementById('forget_password_posting').innerHTML='';
						  document.getElementById('forget_password_posting').innerHTML='".$error_string."';		  
						</script>"; 
					}else{
					//	echo "";
						 $error_string="Email sending problem..";
						echo "<script type='text/javascript'>
						  document.getElementById('forget_password_posting').innerHTML='';
						  document.getElementById('forget_password_posting').innerHTML='".$error_string."';		  
						</script>"; 
					}	
					
				}
				$stmt2->close();	
			}else{
				 $error_string="Something wrong.Please try again later..";
				echo "<script type='text/javascript'>
			  document.getElementById('forget_password_posting').innerHTML='';
			  document.getElementById('forget_password_posting').innerHTML='".$error_string."';		  
			</script>"; 
			}
			
		}else{
			 $error_string="Provided email $email is not found as registered with this site.";
			echo "<script type='text/javascript'>
			  document.getElementById('forget_password_posting').innerHTML='';
			  document.getElementById('forget_password_posting').innerHTML='".$error_string."';		  
			</script>"; 
		}
		$stmt->free_result(); // free result
		$stmt->close(); // close statement
	}else{
		
	}

		
		
}
db_close($link);
}
?>
