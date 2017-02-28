<!DOCTYPE html>
<?php
include_once '../database/config.php';
$c_nick_id='';
$c_nick='';
if(isset($_COOKIE['nick_id_c']) && isset($_COOKIE['nick_c']))
{
	$c_nick_id=$_COOKIE['nick_id_c'];
	$c_nick=$_COOKIE['nick_c'];
}
$section='';
$active=1;
$include_page='';
$ac_fp_v1='';
$ac_fp_v2='';

if(isset($_GET['section']))
{
	$section=urldecode($_GET['section']);
	if(strtolower($section)=='profile') 
	{
		$active=2;
		$include_page='profile.php';
		/*
		if(isset($_GET['ac']) && $_GET['ac']=='pr')
		{   $link=db_connect();
			$ac_fp_v1=urldecode(mysqli_real_escape_string($link,$_GET['v1']));
			$ac_fp_v2=urldecode(mysqli_real_escape_string($link,$_GET['v2']));
			db_close($link);
		}
		*/
	}else if(strtolower($section)=='account')
	{
		$active=3;
		$include_page='account.php';
		
		if(isset($_GET['ac']) && $_GET['ac']=='pr')
		{   $link=db_connect();
			$ac_fp_v1=urldecode(mysqli_real_escape_string($link,$_GET['v1']));
			$ac_fp_v2=urldecode(mysqli_real_escape_string($link,$_GET['v2']));
			db_close($link);
		}
		
	}else if(strtolower($section)=='contact')
	{
		$active=4;
		$include_page='contact.php';
		
	}else if(strtolower($section)=='about')
	{
		$active=5;
		$include_page='about.php';
		
	}else{
		//someone trying with wrong url parameter..
		echo "<h1 align='center' style='color:red'>Wrong URL/address..</h1>";
		exit;
	}
}
include_once "../function/common.php";
?>
<html>
<title>Feeling belief like confession vague thought||Read write VagueText.com</title>
<?php header('Content-Type: charset=utf-8'); ?>
<meta charset="UTF-8"/>
<noscript>
<style type="text/css">
	#whole_page {display:none;}
</style>
<h1 align="center" style="color: red"> Please enable Javascript of your browser before you proceed. </h1>
</noscript>
<head>
<link rel="shortcut icon" href="../title.ico" />
<link rel="icon" href="title.ico" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<style>
body {
    font: normal 18px Book Antiqua;
	margin:0;
}

#top_most_nav {
	width:100%;	
	padding-top:35px;
}


#forgot_password {
	width: 400px;
	min-height:100px;
	float:left;
	background:#f2f2f2;
	margin:12px;
	padding:10px;
	text-align: justify;
	border-radius: 8px;
}
#forgot_password_v {
	width: 400px;
	min-height:100px;
	float:left;
	background:#f2f2f2;
	margin:12px;
	padding:10px;
	text-align: justify;
	border-radius: 8px;
}

#contact_us {
	width: 400px;
	min-height:100px;
	float:left;
	background:#f2f2f2;
	margin:12px;
	padding:10px;
	text-align: justify;
	border-radius: 8px;
}
#about {
	width: 600px;
	min-height:100px;
	float:left;
	background:#f2f2f2;
	margin:12px;
	padding:10px;
	text-align: justify;
	border-radius: 8px;
}
#content_paragraphs {
	width:100%;
	min-height:1100px;
}
.forgot_password_submit,.contact_us_submit,forgot_password_submit_v{
	color: black;
	background:#f2f2f2;
}
#content_paragraphs .content_paragraphs_individual{
	width: 400px;
	min-height:88px;
	float:left;
	background:#f2f2f2;
	margin:12px;
	padding:10px;
	text-align: justify;
	border-radius: 8px;
}
#content_paragraphs .content_paragraphs_individual .span_right{
 float:right;
 font-style: italic;
}

#content_paragraphs .content_paragraphs_individual .span_left{
 float:left;
 font-style: italic;
}
.px_padding{
	padding-top:5px;
	padding-bottom:5px;
}
hr { 
    display: block;
    margin-top: 0em;
    margin-bottom: 0.2em;
    margin-left: auto;
    margin-right: auto;
	border:1px solid #F8F8FF;
}
/* unvisited link */
a:link {
    color: #000000;
}

/* visited link */
a:visited {
    color: #808080;
}

a:focus {
  border-bottom: 1px solid;
  background: #808080;
}

/* mouse over link */
a:hover {
    color: #808080;
}

/* selected link */
a:active {
    color: #808080;
}
</style>
<style>
.active{
	background-color: #cccccc;
}
#top_menu {
	position: fixed;
	width:100%;
	
}
ul.topnav {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #e6e6e6;
  border-top:0px;
  border-bottom-width:1px;
  border-right:0px;
  border-left:0px;
  border-style:outset;
}

ul.topnav li {float: left;}

ul.topnav li a {
  display: inline-block;
  color: black;
  text-align: center;
  padding: 4px;
  text-decoration: none;
  transition: 0.3s;
  font-size: 18px;
  font-weight:bold;
}

ul.topnav li a:hover {background-color: #b3b3b3;}

ul.topnav li.icon {display: none;}

@media screen and (max-width:680px) {
  ul.topnav li:not(:first-child) {display: none;}
  ul.topnav li.icon {
    float: right;
    display: inline-block;
  }
}

@media screen and (max-width:680px) {
  ul.topnav.responsive {position: relative;}
  ul.topnav.responsive li.icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  ul.topnav.responsive li {
    float: none;
    display: inline;
  }
  ul.topnav.responsive li a {
    display: block;
    text-align: left;
  }
}
</style>
<script>
// menu
function menu_function() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

// password reset -before email
$(function()
{   
	$(".forgot_password_submit").click(function() 
	{
	var email=$("#fp_email").val();
	var form_data='email='+ email;
//alert(form_data);
		$("#fp_loading").show();
		$("#fp_loading").fadeIn(500).html('<img src="../public/images/ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading">Please wait...</span>');
		$.ajax({
		  type: "POST",
		  url: "../ajax_req_res/menu/password_reset.php",
		  data: form_data,
		  cache: false,
		  success: function(html){
		 
		  $("#forget_password_posting_v").append(html);
		  
		  $("#forget_password_posting_v").focus();
		  $("#fp_loading").hide();
			
		  }
		 });

    return false; 
	});
});


// password reset -after email
$(function()
{   
	$(".forgot_password_submit_v").click(function() 
	{
	
	var email=$("#fp_email_v").val();
	var pass1=$("#pass1").val();
	var pass2=$("#pass2").val();
	var v1=$("#v1").val();
	var v2=$("#v2").val();
	var form_data='email=' + email + '&pass1=' + pass1 + '&pass2=' + pass2+ '&v1=' + v1+ '&v2=' + v2;
    //alert(form_data);
 
		$("#fp_loading_v").show();
		$("#fp_loading_v").fadeIn(500).html('<img src="../public/images/ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading">Please wait...</span>');
		$.ajax({
		  type: "POST",
		  url: "../ajax_req_res/menu/password_reset_verification.php",
		  data: form_data,
		  cache: false,
		  success: function(html){
		 
		  $("#forget_password_posting_v").append(html);
		  
		  $("#forget_password_posting_v").focus();
		  $("#fp_loading_v").hide();
			
		  }
		 });

    return false; 
	});
});

// Contact us
$(function()
{   
	$(".contact_us_submit").click(function() 
	{
	var email=$("#cu_email").val();
	var cu_paragraph=$("#cu_paragraph").val();
	var cu_title=$("#cu_title").val();
	var cu_name=$("#cu_name").val();
	var form_data='email='+ email + '&paragraph='+ cu_paragraph + '&title='+ cu_title + '&name='+ cu_name;
//alert(form_data);
		$("#cu_loading").show();
		$("#cu_loading").fadeIn(500).html('<img src="../public/images/ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading">Please wait...</span>');
		$.ajax({
		  type: "POST",
		  url: "../ajax_req_res/menu/contact_us.php",
		  data: form_data,
		  cache: false,
		  success: function(html){
		 
		  $("#contact_us_posting").append(html);
		  
		  //$("#contact_us_posting").focus();
		  $("#cu_loading").hide();
			
		  }
		 });

    return false; 
	});
});

</script>
</head>
<body>
<div id="whole_page">
	<div id="top_menu">
	<?php include_once "t_top_menu_2.php"; ?>
	</div>
	<div id="top_most_nav">
		<span> <font color="black" size="6"><a style='text-decoration:none;color:black' href="../">Vague Text</a></font> </span>  &nbsp;&nbsp;&nbsp;
		<span> <font color="808080" size="4">Express your likes, feelings, beliefs, confessions, vague thoughts.  </font> </span>&nbsp;||&nbsp;&nbsp;
		<span> <font color="808080" size="4">Read similar writings.  </font> </span>&nbsp;||&nbsp;&nbsp;
		<span> <font color="808080" size="4">Stay anonymous.   </font> </span> 

	</div>
	<div style='clear:both; width:100%'>
		<div style="width:<?php if($active!=2){ ?>50%; <?php }else{ ?> 100%; <?php }?> min-width:500px;; margin:0 auto;">
			<?php include_once $include_page;?>
		</div>
	</div>
	
</div>

</body>
</html>