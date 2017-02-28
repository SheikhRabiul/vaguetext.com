<?php
sleep(1); // to test ajax loading is showing, this sleep function will be removed in future. 
include('../../database/config.php');
include('../../function/common.php');
date_default_timezone_set('America/Chicago');
//echo $_POST['email'];
//exit;
$error_msg=array();
$error_msg_ind=0;

$link=db_connect();
if($_POST)
{
	 $email=mysqli_real_escape_string($link,(strtolower($_POST['email'])));
	 $pass1=mysqli_real_escape_string($link,$_POST['pass1']);
	 $pass2=mysqli_real_escape_string($link,$_POST['pass2']);
	 $v1=mysqli_real_escape_string($link,$_POST['v1']);
	 $v2=mysqli_real_escape_string($link,$_POST['v2']);
	//exit;
	 $ip=$_SERVER['REMOTE_ADDR'];


	if(strlen($email)==0 || strlen($pass1)==0 || strlen($pass2)==0)
	{
		$error_msg[$error_msg_ind]="Please provide your email address and the new password you want to set.";
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
		
		if(strcmp($pass1, $pass2) != 0)
		{
			$error_msg[$error_msg_ind]="Your New Password and Re-typed New Password do not match.";
			$error_msg_ind++;
		}
		
		if(strlen($pass1)<6 || strlen($pass1)>20)
		{
			$error_msg[$error_msg_ind]="Your password must be need to be within 6 to 20 character or digit or combination of both.";
			$error_msg_ind++;
		}
		if(strlen($v1)<1 || strlen($v2)<1)
		{
			$error_msg[$error_msg_ind]="Are you sure you didn't change the url?";
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
			  document.getElementById('forget_password_posting_v_v').innerHTML='';
			  document.getElementById('forget_password_posting_v_v').innerHTML='".$error_string."';		  
			</script>"; 
		//echo $error_string;
		exit;
		
	}else{

		$email_db='';
		$nick_db='';
		$id_db='';
		$pass_reset_key='';
		$pass_reset_key_expired='';
		
		$sql="select id,nickname,email,pass_reset_key,pass_reset_key_expired from `nicks` where `email`=?";
		if($stmt=$link->prepare($sql))
		{	
			$stmt->bind_param("s",$email); //bind variable to prepared stmt
			$stmt->execute(); // execute query 
			$result=$stmt->store_result(); // Store the whole result in buffer(to get properties)
			$num_rows=$stmt->num_rows;
			//bind result to variables
			$stmt->bind_result($id_db, $nick_db, $email_db,$pass_reset_key,$pass_reset_key_expired);
			if($num_rows>0)
			{ 
				$stmt->fetch();
			/*	echo "<br />";
				echo urldecode(encrypt($pass_reset_key));
				echo "<br />";
			    echo $v1;
				echo "<br />";
				echo  strcmp(urldecode(encrypt($pass_reset_key)),$v1) ;
				echo "<br />";
				
				echo "<br />";
				echo urldecode(encrypt($id_db));
				echo "<br />";
			    echo $v2;
				echo "<br />";
				echo  strcmp(urldecode(encrypt($id_db)), $v2) ;
				echo "<br />";
	*/
				if(strcmp(urldecode(encrypt($pass_reset_key)), $v1)==0 && strcmp(urldecode(encrypt($id_db)), $v2)==0)
				{
					/*echo date("Y-m-d");
					echo "<br />";
					echo $pass_reset_key_expired;
				
					if(strtotime(date("Y-m-d"))>=strtotime($pass_reset_key_expired)) echo "ok";
						exit; */
					if(strtotime(date("Y-m-d"))< strtotime($pass_reset_key_expired))
					{
				
						$sql2="update `nicks` set `pass`=? where `id`=?"; 
						$new_password=password_hash($pass1, PASSWORD_DEFAULT);
						if($stmt2=$link->prepare($sql2))
						{	
						
							$stmt2->bind_param("si",$new_password, $id_db); //bind variable to prepared stmt
							$stmt2->execute(); // execute query
							if($stmt2->affected_rows>0)
							{		
							
									 $error_string="<b>Your password reset is successful. Now you can use your new password.</b>";
									echo "<script type='text/javascript'>
									  document.getElementById('forget_password_posting_v').innerHTML='';
									  document.getElementById('forget_password_posting_v').innerHTML='".$error_string."';		  
									</script>"; 
								
							}
							$stmt2->close();	
						}else{
							 $error_string="Something wrong. Please try again later.";
							echo "<script type='text/javascript'>
						  document.getElementById('forget_password_posting_v').innerHTML='';
						  document.getElementById('forget_password_posting_v').innerHTML='".$error_string."';		  
						</script>"; 
						}
					}else{
						$error_string="Your password reset link is expired. Please try to reset the password from the beginning.";
							echo "<script type='text/javascript'>
						  document.getElementById('forget_password_posting_v').innerHTML='';
						  document.getElementById('forget_password_posting_v').innerHTML='".$error_string."';		  
						</script>";
					}
				}else{
					 $error_string="Something wrong. Please try again at a later time from the beginning..";
						echo "<script type='text/javascript'>
					  document.getElementById('forget_password_posting_v').innerHTML='';
					  document.getElementById('forget_password_posting_v').innerHTML='".$error_string."';		  
					</script>"; 
				}
				
			}else{
				 $error_string="Provided email $email is not found as registered with this site.";
				echo "<script type='text/javascript'>
				  document.getElementById('forget_password_posting_v').innerHTML='';
				  document.getElementById('forget_password_posting_v').innerHTML='".$error_string."';		  
				</script>"; 
			}
			$stmt->free_result(); // free result
			$stmt->close(); // close statement
		}	
	}
	db_close($link);
}
?>
