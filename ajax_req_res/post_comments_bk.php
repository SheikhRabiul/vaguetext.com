<?php
sleep(1); // to test ajax loading is showing, this sleep function will be removed in future. 
include('../database/config.php');
date_default_timezone_set('America/Chicago');
$link=db_connect();
$paragraph_is_english=1;
$name_is_english=1;
$post_meta_id = 0;
if (isset($_POST['id'])) {
	$post_meta_id = intval($_POST['id']);
}
if($_POST)
{
$written_by=mysqli_real_escape_string($link,$_POST['name_comment']);
$email=mysqli_real_escape_string($link,(strtolower($_POST['email_comment'])));
//exit;
$paragraph=mysqli_real_escape_string($link,$_POST['comment']);
$error_msg=array();
$error_msg_ind=0;
$p_word_limit=1000;
$p_char_limit=5000;
$n_char_limit=30;

$p_word_limit_show=1000;
$p_char_limit_show=5000;
$n_char_limit_show=30;

//echo "strlen:".strlen($paragraph)." MB:".mb_strlen($paragraph, 'utf-8');
// if they are not equal then it has multibyte characters [other language..in that case strlen($paragraph) will be around three times],,
if(strlen($paragraph) != mb_strlen($paragraph, 'utf-8'))
{
	$paragraph_is_english=0;
	$p_word_limit=5000;
	$p_char_limit=15000;
	$p_word_limit_show=5000;
	//$p_char_limit_show=20000;
}

if(strlen($written_by) != mb_strlen($written_by, 'utf-8'))
{
	$name_is_english=0;
	$n_char_limit=100;
}
//echo "para:".$paragraph_is_english."title:".$title_is_english."name:".$name_is_english;
//exit;
//mysql text dta type can hold 65535 bytes...which is equivalent to around 21844 utf characters.. 
if(str_word_count($paragraph,0)>$p_word_limit)
{
	$error_msg[$error_msg_ind]="Your comment need to be within $p_word_limit_show words.";
	$error_msg_ind++;
}


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


if($_POST['privacy_comment']=="nick")
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
		
		if(strlen($written_by)>0 && strcmp($written_by,$email)==0)
		{
		  $error_msg[$error_msg_ind]="Your email  and nickname should be different , so that nickname can not be easily guessed from email.Please select a different nickname.";
		  $error_msg_ind++;
		}
		
		$exploded_email=explode('@',$email);
		if(strlen($written_by)>0 && strcmp($written_by,$exploded_email[0])==0)
		{
		  $error_msg[$error_msg_ind]="Your nickname can not be part of your email address, so that nickname can not be easily guessed from email.Please select a different nickname.";
		  $error_msg_ind++;
		}
		
	}
	
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

	if(strlen($email)>50)
	{
		$error_msg[$error_msg_ind]="Your email  need to be within 50 characters.";
		$error_msg_ind++;
	}

}
$ip=$_SERVER['REMOTE_ADDR'];
$nickname_sys=$written_by;
$nick_id='';

if(count($error_msg)>0)
{	
	$error_string='';
	$error_string.= "<br />"."<b>Comment posting failure due to the reasons below: </b>";
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
	
	if($_POST['privacy_comment']=="nick")
	{
		// existing writer with valid email
	 	$sql="select id,nickname,nickname_sys from `nicks` where `email`=?";
		if($stmt=$link->prepare($sql))
		{	
			$stmt->bind_param("s",$email); //bind variable to prepared stmt
			$stmt->execute(); // execute query 
			$result=$stmt->store_result(); // Store the whole result in buffer(to get properties)
			$num_rows=$stmt->num_rows;
			//bind result to variables
			$stmt->bind_result($id, $nickname, $nickname_sys);
			if($num_rows>0)
			{
				$stmt->fetch();
				if(strcmp($written_by,$nickname)!=0) //email match but nickname doesn't
				{
					$error_string.= "<br />"."<b>Comment posting failure due to the reasons below: </b>";
					$error_string.='Your nickname is not matching with the email address you provided.';
					echo "<script type='text/javascript'>
					  document.getElementById('comment_posting').innerHTML='';
					  document.getElementById('comment_posting').innerHTML='".$error_string."';		  
					</script>";
					exit;
				}else{ // both email and nickname match.
					$nickname_sys=$nickname_sys;
					$nick_id=$id;
				}
			}else{
				// new writer
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
						$nick_num_t=$nick_num+1;
						$nickname_sys=$written_by.$nick_num_t;
						$sql="INSERT INTO `nicks` (`id`, `email`, `nickname`,`nickname_sys`,`created`)
						VALUES (?,?,?,?,?)";
					
						if($stmt3=$link->prepare($sql))
						{	
							$id1=NULL;
							$created=date('Y-m-d H:i:s');
							$stmt3->bind_param("issss",$id1, $email, $written_by, $nickname_sys,$created); //bind variable to prepared stmt
							$stmt3->execute(); // execute query
							if($stmt3->affected_rows>0)
							{		
								$nick_id=$stmt3->insert_id;								
							}else{
								echo "<br />"."<b>Comment posting failure [Error:101] . Pelase try again...</b>"; //to-do ---write logs...
								exit;
							}	
							$stmt3->close();	
						}												
					}else{ // new writer with unique nick
						$sql="INSERT INTO `nicks` (`id`, `email`, `nickname`,`nickname_sys`,`created`)
						VALUES (?,?,?,?,?)";
						if($stmt4=$link->prepare($sql))
						{	
							$id1=NULL;
							$created=date('Y-m-d H:i:s');
							$stmt4->bind_param("issss",$id1, $email, $written_by, $nickname_sys,$created); //bind variable to prepared stmt
							$stmt4->execute(); // execute query
							if($stmt4->affected_rows>0)
							{		
								$nick_id=$stmt4->insert_id;					
							}else{
								echo "<br />"."<b>Comment posting failure [Error:102] . Pelase try again...</b>"; //to-do ---write logs...
								exit;
							}
							$stmt4->close();
						}
					}
					$stmt2->free_result(); // free result
					$stmt2->close(); // close statement
				}				
			}
			$stmt->free_result(); // free result
			$stmt->close(); // close statement
		}
	}
	
	$sql="INSERT INTO `comments` (`id`, `post_meta_id`, `comment`,`nickname`,`nickname_sys`,`email`,`nick_id`, `created`,`ip`)
	VALUES (?,?,?,?,?,?,?,?,?)";
	if($stmt=$link->prepare($sql))
	{	
		$id1=NULL;
		$created=date('Y-m-d H:i:s');
		$stmt->bind_param("iissssiss",$id1, $post_meta_id,$paragraph, $written_by,$nickname_sys, $email,$nick_id,$created,$ip); //bind variable to prepared stmt
		$stmt->execute(); // execute query
		if($stmt->affected_rows>0)
		{	
			$posted_id=$stmt->insert_id;
			$stmt->close();
			$paragraph_tot="<li><i>Your comment posted successfully as below:</i></li>";
			$paragraph_tot.=$paragraph."<br /><br />";
			if($written_by!='')
			{	
			$paragraph_tot.="<i>-- ".$written_by."</i>";
			}else{
			$paragraph_tot.="<i>-- ".'anonymous'."</i>";
			}
			echo "<script type='text/javascript'>
					  document.getElementById('comment_posting').innerHTML='';
					  document.getElementById('comment_posting').innerHTML='".$paragraph_tot."';
				</script>";
			echo "<br />";
			
		}else{
		echo "<font color='red'><b>Something wrong.Please check your inputs and try again.....</b> </font>"."<br />";
		}
	}	
}
db_close($link);
}
?>
