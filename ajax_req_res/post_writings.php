<?php
sleep(1); // to test ajax loading is showing, this sleep function will be removed in future. 
include('../database/config.php');
date_default_timezone_set('America/Chicago');
$link=db_connect();
$paragraph_is_english=1;
$title_is_english=1;
$name_is_english=1;
if($_POST)
{
$written_by=mysqli_real_escape_string($link,$_POST['name']);
$email=mysqli_real_escape_string($link,(strtolower($_POST['email'])));
$paragraph=mysqli_real_escape_string($link,$_POST['paragraph']);
$title=mysqli_real_escape_string($link,$_POST['title']);
$pass=mysqli_real_escape_string($link,$_POST['pass']);
$privacy=mysqli_real_escape_string($link,$_POST['privacy']);
$existing_nick_id=mysqli_real_escape_string($link,$_POST['existing_nick_id']);

$ip=$_SERVER['REMOTE_ADDR'];
$nickname_sys=$written_by;
$nick_id='';

$error_msg=array();
$error_msg_ind=0;
$p_word_limit=5000;
$p_char_limit=20000;
$t_char_limit=200;
$n_char_limit=30;

$p_word_limit_show=5000;
$p_char_limit_show=20000;
$t_char_limit_show=200;
$n_char_limit_show=30;

//echo "strlen:".strlen($paragraph)." MB:".mb_strlen($paragraph, 'utf-8');
// if they are not equal then it has multibyte characters [other language..in that case strlen($paragraph) will be around three times],,
if(strlen($paragraph) != mb_strlen($paragraph, 'utf-8'))
{
	$paragraph_is_english=0;
	$p_word_limit=15000;
	$p_char_limit=60000;
	$p_word_limit_show=5000;
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
	$n_char_limit=100;
	//$n_char_limit_show=200;
}
//echo "para:".$paragraph_is_english."title:".$title_is_english."name:".$name_is_english;
//exit;
//mysql text dta type can hold 65535 bytes...which is equivalent to around 21844 utf characters.. 
if(str_word_count($paragraph,0)>$p_word_limit)
{
	$error_msg[$error_msg_ind]="Your writing need to be within $p_word_limit_show words.";
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
	$error_msg[$error_msg_ind]="Your writings  need to be at least two character long.";
	$error_msg_ind++;
}
if(strlen($paragraph)>$p_char_limit)
{
	$error_msg[$error_msg_ind]="Your writing need to be within $p_char_limit_show characters.";
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
	$error_msg[$error_msg_ind]="Your writings title need to be at least two character long.";
	$error_msg_ind++;
}
//echo $title; exit;
if(strlen($title)>$t_char_limit)
{
	$error_msg[$error_msg_ind]="Your writing title need to be within $t_char_limit_show characters.";
	$error_msg_ind++;
}

if($privacy=="nick" || $privacy=="nick_new")
{
	if(strlen($email)==0)
	{
		$error_msg[$error_msg_ind]="Please provide your email address.We will not show your email address in this site.It will be needed to retrive your Nickname.";
		$error_msg_ind++;
	}else{
		if (strlen($email)>0 && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		 $error_msg[$error_msg_ind]="Your email  is not valid one.";
		 $error_msg_ind++;
		}
		if(strlen($email)>50)
		{
			$error_msg[$error_msg_ind]="Your email  need to be within 50 characters.";
			$error_msg_ind++;
		}

		/*
		if(strlen($written_by)>0 && strcmp($written_by,$email)==0)
		{
		  $error_msg[$error_msg_ind]="Your email  and nickname should be different , so that nickname can not be easily guessed from email.Please select a different nickname.";
		  $error_msg_ind++;
		}
		*/
		/*
		$exploded_email=explode('@',$email);
		if(strlen($written_by)>0 && strcmp($written_by,$exploded_email[0])==0)
		{
		  $error_msg[$error_msg_ind]="Your nickname can not be part of your email address, so that nickname can not be easily guessed from email.Please select a different nickname.";
		  $error_msg_ind++;
		}
		*/
		
	}
	if( $privacy=="nick_new")
	{	
		if(strlen($written_by)==0)
		{
			$error_msg[$error_msg_ind]="Please provide a Nickname.";
			$error_msg_ind++;
		}
		if(strlen($written_by)>$n_char_limit)
		{
			$error_msg[$error_msg_ind]="Your nickname  need to be within $n_char_limit_show characters.";
			$error_msg_ind++;
		}
		if(strlen($pass)==0)
		{
			$error_msg[$error_msg_ind]="Please provide a password.";
			$error_msg_ind++;
		}
		if(strlen($pass)<4)
		{
			$error_msg[$error_msg_ind]="Your password is too small. Please set a password that is atleast 4 digits or characters or combination of both.";
			$error_msg_ind++;
		}
		if(strlen($pass)>20)
		{
			$error_msg[$error_msg_ind]="Your password is too big. Please set a password that is less than 21 digits or characters or combination of both.";
			$error_msg_ind++;
		}
    }
	
}



if(count($error_msg)>0)
{	
	$error_string='';
	$error_string.= "<br />"."<b>Posting failure due to the reasons below: </b>";
	foreach($error_msg as $key => $value)
	{	
		$error_string.= "<li><i>$value</i></li>"."<br />";
	}
	//echo $error_string;
	
	echo "<script type='text/javascript'>
		  document.getElementById('paragraph_posting').innerHTML='';
		  document.getElementById('paragraph_posting').innerHTML='".$error_string."';		  
		</script>"; 
	//echo $error_string;
	exit;
	
}else{
	$post_brief='';
	if($paragraph_is_english==1)
	{
		$post_brief=substr(	$paragraph,0,200);
	}else{
		$post_brief=substr(	$paragraph,0,650); //utf-8
	}
	//first check whether posting as a user from cookie..
	if($privacy=='post_as_this_nick' && $existing_nick_id>0)
	{
		$nick_id=$existing_nick_id;
		//echo "ok";
		// find out corresponding email, sys_nic_name and nickname..
		
		$sql="select id,nickname,nickname_sys,email from `nicks` where `id`=?";
		if($stmt=$link->prepare($sql))
			{	
				$stmt->bind_param("i",$nick_id); //bind variable to prepared stmt
				$stmt->execute(); // execute query 
				$result=$stmt->store_result(); // Store the whole result in buffer(to get properties)
				$num_rows=$stmt->num_rows;
				//bind result to variables
				
				if($num_rows>0)  // email found ..now check
				{
					$stmt->bind_result($id2, $nickname2, $nickname_sys2,$email2);
					$stmt->fetch();
					$nickname_sys=$nickname_sys2;
					$nickname=$nickname2;
					$written_by=$nickname2;
					$email=$email2;
				}else{
					$error_string.= "<li><i>The nick no more exists.</i></li>"."<br />";
					echo "<script type='text/javascript'>
					  document.getElementById('paragraph_posting').innerHTML='';
					  document.getElementById('paragraph_posting').innerHTML='".$error_string."';		  
					</script>"; exit;
				}
				$stmt->free_result();
				$stmt->close();	

			}	
	}else{ // may be new nick or a existing nick other than cookie

		if($privacy=="nick" || $privacy=="nick_new")
		{
			$sql="select id,nickname,nickname_sys,`pass` from `nicks` where `email`=?";
			if($stmt=$link->prepare($sql))
			{	
				$stmt->bind_param("s",$email); //bind variable to prepared stmt
				$stmt->execute(); // execute query 
				$result=$stmt->store_result(); // Store the whole result in buffer(to get properties)
				$num_rows=$stmt->num_rows;
				//bind result to variables
				$stmt->bind_result($id, $nickname, $nickname_sys,$hash_pass);
			//	echo "nickname:".$nickname." nickname sys:".$nickname_sys; exit;
				if($num_rows>0)  // email found ..now check whether new or existing user  and show appropriate message
				{
					if($privacy=="nick_new") // new user but email exists..show error message
					{
						$error_string.= "<br />"."<b>Posting failure due to the reasons below: </b>";
						$error_string.='Your provided email address already exist.';
						echo "<script type='text/javascript'>
						  document.getElementById('paragraph_posting').innerHTML='';
						  document.getElementById('paragraph_posting').innerHTML='".$error_string."';		  
						</script>";
						exit;
					}else{ // existing user with valid email, now check password..
						$stmt->fetch();
						/*
						if(strcmp($written_by,$nickname)!=0) //email match but nickname doesn't
						{
							$error_string.= "<br />"."<b>Posting failure due to the reasons below: </b>";
							$error_string.='Your nickname is not matching with the email address you provided.';
							echo "<script type='text/javascript'>
							  document.getElementById('paragraph_posting').innerHTML='';
							  document.getElementById('paragraph_posting').innerHTML='".$error_string."';		  
							</script>";
							exit;
						}else{ // both email and nickname match.
							$nickname_sys=$nickname_sys;
							$nick_id=$id;
						}*/
						
						if(password_verify($pass,$hash_pass)) // password match with email
						{
							$nickname_sys=$nickname_sys;
							$nick_id=$id;
							$written_by=$nickname;
							// update cookie..
							if(isset($_COOKIE['nick_id_c']) && isset($_COOKIE['nick_c'])) 
							{	
								setcookie ("nick_id_c", "", time()-3600, '/', $domain_name); //setting in past to delete cookie
								setcookie ("nick_c", "", time()-3600, '/', $domain_name); //setting in past to delete cookie
							}
								
								$expiry_time=15552000 + time(); //six month (60*60*24*180) + time(); // FALSE=not httpss only, TRUE= ONLY HTTP protocol
								setcookie ("nick_id_c", $nick_id , $expiry_time, '/', $domain_name,FALSE,TRUE); // creating new cookie
								setcookie ("nick_c", $nickname , $expiry_time, '/', $domain_name,FALSE,TRUE); //creating new cookie
						
						}else{ //password doesn't match
							$error_string.= "<br />"."<b>Posting failure due to the reasons below: </b>";
							$error_string.='Your password is not matching with the email address you provided.';
							echo "<script type='text/javascript'>
							  document.getElementById('paragraph_posting').innerHTML='';
							  document.getElementById('paragraph_posting').innerHTML='".$error_string."';		  
							</script>";
							exit;
						}
					}
					
				}else{
					// new writer but the nick using may exist in databse...
					// make hash password 
					$hash_pass=password_hash($pass, PASSWORD_DEFAULT);
					
					$sql="select id from `nicks` where `nickname`=?";
					if($stmt2=$link->prepare($sql))
					{	
						$stmt2->bind_param("s",$written_by); //bind variable to prepared stmt
						$stmt2->execute(); // execute query 
						$result=$stmt2->store_result(); // Store the whole result in buffer(to get properties)
						$num_rows=$stmt2->num_rows;
						//bind result to variables
						$stmt2->bind_result($id);
						if($num_rows>0) //same nick exists
						{
							$nick_num_t=$num_rows+1;
							$nickname_sys=$written_by.$nick_num_t;
							$sql="INSERT INTO `nicks` (`id`, `email`, `nickname`,`nickname_sys`,`created`,`pass`)
							VALUES (?,?,?,?,?,?)";
						
							if($stmt3=$link->prepare($sql))
							{	
								$id1=NULL;
								$created=date('Y-m-d H:i:s');
								$stmt3->bind_param("isssss",$id1, $email, $written_by, $nickname_sys,$created,$hash_pass); //bind variable to prepared stmt
								$stmt3->execute(); // execute query
								if($stmt3->affected_rows>0)
								{		
									$nick_id=$stmt3->insert_id;
									
									//create cookie
									$expiry_time=15552000 + time(); //six month (60*60*24*180) + time(); // FALSE=not httpss only, TRUE= ONLY HTTP protocol
									setcookie ("nick_id_c", $nick_id , $expiry_time, '/', 'vaguetext.com',FALSE,TRUE); // creating new cookie
									setcookie ("nick_c", $written_by , $expiry_time, '/', 'vaguetext.com',FALSE,TRUE); //creating new cookie
							
									
									
								}else{
									echo "<br />"."<b>Posting failure [Error:101] . Pelase try again...</b>"; //to-do ---write logs...
									exit;
								}	
								$stmt3->close();	
							}
							
							
						}else{ // new writer with completely new  nick
							$sql="INSERT INTO `nicks` (`id`, `email`, `nickname`,`nickname_sys`,`created`,`pass`)
							VALUES (?,?,?,?,?,?)";
							if($stmt4=$link->prepare($sql))
							{	
								$id1=NULL;
								$created=date('Y-m-d H:i:s');
								$stmt4->bind_param("isssss",$id1, $email, $written_by, $nickname_sys,$created,$hash_pass); //bind variable to prepared stmt
								$stmt4->execute(); // execute query
								if($stmt4->affected_rows>0)
								{		
									$nick_id=$stmt4->insert_id;
									//create cookie
									$expiry_time=15552000 + time(); //six month (60*60*24*180) + time(); // FALSE=not httpss only, TRUE= ONLY HTTP protocol
									setcookie ("nick_id_c", $nick_id , $expiry_time, '/', $domain_name,FALSE,TRUE); // creating new cookie
									setcookie ("nick_c", $written_by , $expiry_time, '/', $domain_name,FALSE,TRUE); //creating new cookie
	
								}else{
									echo "<br />"."<b>Posting failure [Error:102] . Pelase try again...</b>"; //to-do ---write logs...
									exit;
								}
								$stmt4->close();
							}
							
						}
						$stmt2->free_result(); // free result
						$stmt2->close(); // close statement
					}

					// now create cookie for new user provided email and nicks
					//$expiry_time=15552000 + time(); //six month (60*60*24*180) + time(); // FALSE=not httpss only, TRUE= ONLY HTTP protocol
					//setcookie ("nick_id_c", $nick_id , $expiry_time, '/', $domain_name,FALSE,TRUE); // creating new cookie
					//setcookie ("nick_c", $nickname , $expiry_time, '/', $domain_name,FALSE,TRUE); //creating new cookie
				}
				$stmt->free_result(); // free result
				$stmt->close(); // close statement
			}
		}
	}
	
	
	
	$sql="INSERT INTO `post_metas` (`id`, `post_brief`, `nickname`,`email`,`title`, `created`,`nickname_sys`,`nick_id`,`ip`)
	VALUES (?,?,?,?,?,?,?,?,?)";
	if($stmt=$link->prepare($sql))
	{	
		$id1=NULL;
		$created=date('Y-m-d H:i:s');
		$stmt->bind_param("issssssis",$id1, $post_brief, $written_by, $email,$title, $created, $nickname_sys,$nick_id,$ip); //bind variable to prepared stmt
		echo "$id1, $post_brief, $written_by, $email,$title, $created, $nickname_sys,$nick_id,$ip;";

		$stmt->execute(); // execute query
		$stmt->error;
		if($stmt->affected_rows>0)
		{		//	exit;
			//echo "ok";
			$posted_id=$stmt->insert_id;
			$stmt->close();
			
			$id2=NULL;
			$sql="INSERT INTO `posts` (`id`, `post_meta_id`,`post`) VALUES (?,?,?)";
			
			if($stmt=$link->prepare($sql))
			{
				$stmt->bind_param("iis",$id2, $posted_id, $paragraph); //bind variable to prepared stmt
				$stmt->execute(); // execute query
				if(!$stmt)
				{
					die('Error : ('. $link->errno .') '. $link->error);
				}
				if($stmt->affected_rows>0)
				{	
					$paragraph_tot="<li><i>Your writing posted successfully as below:</i></li>";
					$paragraph_tot.="<hr><b>".$title."</b><hr>";
					$paragraph_tot.=$paragraph."<br /><br />";
					if($written_by!='')
					{	
					$paragraph_tot.="<i>-- ".$written_by."</i>";
					}else{
					$paragraph_tot.="<i>-- ".'anonymous'."</i>";
					}
						echo "<script type='text/javascript'>
							  document.getElementById('paragraph').value='';
							  document.getElementById('title').value='';
							  document.getElementById('name').value='';
							  document.getElementById('email').value='';
							  document.getElementById('paragraph_posting').innerHTML='';
							  document.getElementById('paragraph_posting').innerHTML='".$paragraph_tot."';
						</script>";
					echo "<br />"."<br />";
				}else{
					echo "<font color='red'><b>Something wrong.Please check your inputs and try again...</b> </font>"."<br />";
				}
				$stmt->close();
			}	
			

		}else{
		echo "<font color='red'><b>Something wrong.Please check your inputs and try again.....</b> </font>"."<br />";
		}
	}	
}
db_close($link);
}
?>
