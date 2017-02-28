<?php
sleep(1); // to test ajax loading is showing, this sleep function will be removed in future. 
include('../database/config.php');
$link=db_connect();
if($_POST)
{
$written_by=mysql_real_escape_string($_POST['name']);BANGLADESH
$email=mysql_real_escape_string((strtolower($_POST['email'])));
$paragraph=mysql_real_escape_string($_POST['paragraph']);
$title=mysql_real_escape_string($_POST['title']);

$error_msg=array();
$error_msg_ind=0;
if(str_word_count($paragraph,0)>1000)
{
	$error_msg[$error_msg_ind]="Your writing need to be within 1000 words.";
	$error_msg_ind++;
}
echo str_word_count($paragraph,0);
if(str_word_count($paragraph,0)<1)
{
	$error_msg[$error_msg_ind]="Your writing need to be at least one word long.";
	$error_msg_ind++;
}


if(strlen($paragraph)>8000)
{
	$error_msg[$error_msg_ind]="Your writing need to be within 8000 characters.";
	$error_msg_ind++;
}

if(str_word_count($title,0)<1)
{
	$error_msg[$error_msg_ind]="Your writings title need to be at least one word long.";
	$error_msg_ind++;
}

if(strlen($title)>150)
{
	$error_msg[$error_msg_ind]="Your writing title need to be within 150 characters.";
	$error_msg_ind++;
}

if($_POST['privacy']=="nick")
{
	if(strlen($email)==0)
	{
		$error_msg[$error_msg_ind]="Please provide your email address.We will not show your email address in this site.It will be needed to retrive your Nickname.";
		$error_msg_ind++;
	}
	if (strlen($email)>0 && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		 $error_msg[$error_msg_ind]="Your email  is not valid one.";
		 $error_msg_ind++;
	}else{
		if(strlen($written_by>0) && strcmp($written_by,$email)==0)
		{
		  $error_msg[$error_msg_ind]="Your email  and nickname should be different , so that nickname can not be easily guessed from email.Please select a different nickname.";
		  $error_msg_ind++;
		}
		
		$exploded_email=explode('@',$email);
		if(strlen($written_by>0) && strcmp($written_by,$exploded_email[0])==0)
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
	if(strlen($written_by)>30)
	{
		$error_msg[$error_msg_ind]="Your nickname  need to be within 30 characters.";
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
	
	$result=mysql_query("select * from `nicks` where `email`='$email'") or die("1.".mysql_error());
	if(mysql_num_rows($result)>0)
	{ // existing writer with valid email
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		if(strcmp($written_by,$row['nickname'])!=0) //email match but nickname doesn't
		{
			$error_string.= "<br />"."<b>Posting failure due to the reasons below: </b>";
			$error_string.='Your nickname is not matching with the email address you provided.';
			echo "<script type='text/javascript'>
			  document.getElementById('paragraph_posting').innerHTML='';
			  document.getElementById('paragraph_posting').innerHTML='".$error_string."';		  
			</script>";
			exit;
		}else{ // both email and nickname match.
			$nickname_sys=$row['nickname_sys'];
			$nick_id=$row['id'];
		}
		
	}else{ // new writer

		$result_nick=mysql_query("select * from `nicks` where `nickname`='$written_by'") or die("2.".mysql_error());
		$nick_num=mysql_num_rows($result_nick);
		if($nick_num>0)// same nick exists
		{
			$nick_num_t=$nick_num+1;
			$nickname_sys=$written_by.$nick_num_t;
			//echo "10:"."INSERT INTO `nicks` (`id`, `email`, `nickname`,`nickname_sys`,`created`) VALUES (NULL, '$email', '$written_by', '$nickname_sys',CURRENT_TIMESTAMP)";
			mysql_query("INSERT INTO `nicks` (`id`, `email`, `nickname`,`nickname_sys`,`created`) VALUES (NULL, '$email', '$written_by', '$nickname_sys',CURRENT_TIMESTAMP)") or die("3.".mysql_error());
			if(mysql_affected_rows()>0)
			{
				$nick_id=mysql_insert_id();
			}else{
				echo "<br />"."<b>Posting failure [Error:101] . Pelase try again...</b>"; //to-do ---write logs...
				exit;
			}
		
		}else{ // new writer with unique nick
		//	echo "11:"."INSERT INTO `nicks` (`id`, `email`, `nickname`,`nickname_sys`,`created`) VALUES (NULL, '$email', '$written_by', '$nickname_sys',CURRENT_TIMESTAMP)";
			mysql_query("INSERT INTO `nicks` (`id`, `email`, `nickname`,`nickname_sys`,`created`) VALUES (NULL, '$email', '$written_by', '$nickname_sys',CURRENT_TIMESTAMP)") or die("4.".mysql_error());
			if(mysql_affected_rows()>0)
			{
				$nick_id=mysql_insert_id();
			}else{
				echo "<br />"."<b>Posting failure [Error:102] . Pelase try again...</b>"; //to-do ---write logs...
				exit;
			}
		}
	}
//echo $sq="INSERT INTO `paragraphs` (`id`, `paragraph`, `written_by`,`email`,`title`, `written_time`,`nickname_sys`,`nick_id`,`ip`) VALUES (NULL, '$paragraph', '$written_by', '$email','$title', CURRENT_TIMESTAMP, '$nickname_sys','$nick_id','$ip')";
	mysql_query("INSERT INTO `paragraphs` (`id`, `paragraph`, `written_by`,`email`,`title`, `written_time`,`nickname_sys`,`nick_id`,`ip`) VALUES (NULL, '$paragraph', '$written_by', '$email','$title', CURRENT_TIMESTAMP, '$nickname_sys','$nick_id','$ip')") or die("5.".mysql_error());
	if(mysql_affected_rows()>0)
	{
	sleep(1);
	//echo "<font color='green'><b>Your writing posted successfully as below:</b> </font>"."<br />";
	//$paragraph=$paragraph;
	$paragraph_tot="<li><i>Your writing posted successfully as below:</i></li>";
	if($title!='')
	{	
		$paragraph_tot.= "<b>".$title."</b>"."<hr>";
	}
	$paragraph_tot.= $paragraph; $paragraph_tot.= '<br />'.'<br />';
	if($written_by!='')
	{	$paragraph_tot.= "<span class='span_left'>"."--".$written_by."&nbsp;</span>";
		//$paragraph_tot.= "<span class='span_right'>".date('M d, Y', strtotime(time()))."</span>";
	}else{
		$paragraph_tot.= "<span class='span_left'>"."-- "."anonymous"."&nbsp;</span>";
		//$paragraph_tot.= "<span class='span_right'>".date('M d, Y', strtotime(time()))."</span>";
	}
	
	echo "<script type='text/javascript'>
			  document.getElementById('paragraph').value='';
			  document.getElementById('title').value='';
			  document.getElementById('name').value='';
			  document.getElementById('email').value='';
			  document.getElementById('paragraph_posting').innerHTML='';
			  document.getElementById('paragraph_posting').innerHTML='".$paragraph_tot."';
		</script>";
	//echo $paragraph_tot;
	echo "<br />"."<br />";

	}else{
	echo "<font color='red'><b>Something wrong.Please check your inputs and try again.....</b> </font>"."<br />";
	}
}
db_close($link);


}
?>
