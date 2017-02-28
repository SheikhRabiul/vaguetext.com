<!DOCTYPE html>
<?php
// Author: Sheikh Rabiul Islam, Date: 01/02/2017
$c_nick_id='';
$c_nick='';
if(isset($_COOKIE['nick_id_c']) && isset($_COOKIE['nick_c']))
{
	$c_nick_id=$_COOKIE['nick_id_c'];
	$c_nick=$_COOKIE['nick_c'];
}
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
<link rel="shortcut icon" href="title.ico" />
<link rel="icon" href="title.ico" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
$(function()
{   
	$(".paragraph_submit").click(function() 
	{
	var paragraph=$("#paragraph").val();
	var title=$("#title").val();
	var privacy=$("#privacy").val();
	var name=$("#name").val();
	var email=$("#email").val();
	var pass=$("#pass").val();
	var existing_nick_id=$("#existing_nick_id").val();
	var form_data='paragraph='+ paragraph + '&title=' + title + '&name=' + name + '&email=' + email + '&privacy=' + privacy + '&pass=' + pass + '&existing_nick_id=' + existing_nick_id;

	if(paragraph=='')
	{
		 alert("Please write something");
	}else{
		$("#loading").show();
		$("#loading").fadeIn(500).html('<img src="public/images/ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading">Posting your writing...</span>');
		$.ajax({
		  type: "POST",
		  url: "ajax_req_res/post_writings.php",
		  data: form_data,
		  cache: false,
		  success: function(html){
		 
		  $("#paragraph_posting").append(html);
		  
		  $("#paragraph").focus();
		  $("#loading").hide();
			
		  }
		 });
	}	
    return false; 
	});
});
</script>

<script type="text/javascript">
// loading more contents based on page scrolling..
var last_id = 0; //track user scroll as page number, right now page number is 1
var loading  = false; //prevents multiple loads
load_contents(last_id); //initial content load
$(window).scroll(function() { //detect page scroll
	if($(window).scrollTop() + $(window).height() >= $(document).height()-200) { //if user scrolled to bottom of the page
		//track_page++; //page number increment
		load_contents(last_id); //load content	
	}
});		
function load_contents(last_id){
	
    if(loading == false){
		loading = true;  //set loading flag on
		$('#loader').show(); //show loading animation 
		$.post( 'ajax_req_res/para_load_more.php', {'last_id': last_id}, function(data){
			loading = false; //set loading flag off once the content is loaded
			if(data.trim().length == 0){
				//notify user if nothing to load
				$('#loader').html("No more records!");
				return;
			}
			$('#loader').hide(); //hide loading animation once data is received
			$("#content_paragraphs").append(data); //append data into #results element
		
		}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
			//alert(thrownError); //alert with HTTP error
		})
	}
}

//privacy options paragraph
function privacy_options()
{
	if(document.getElementById("privacy").value=="ano" || document.getElementById("privacy").value=="post_as_this_nick")
	{  
		document.getElementById("privacy_options_box").innerHTML="";
		document.getElementById("privacy_options_box").innerHTML="<input type='hidden' name='name' id='name' /> <input type='hidden' name='email' id='email' />  <input type='hidden' name='pass' id='pass' /> ";
		
	}else if(document.getElementById("privacy").value=="nick_new"){
		document.getElementById("privacy_options_box").innerHTML="Nickname: <input type='text' name='name' id='name' />  <div class='px_padding'> Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='email' id='email' /> </div><div class='px_padding'>Password: &nbsp;<input type='password' name='pass' id='pass' /></div>";
	}else{ //existing nick
		document.getElementById("privacy_options_box").innerHTML="<input type='hidden' name='name' id='name' /> <br /> <div class='px_padding'> Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='email' id='email' /> </div><div class='px_padding'>Password: &nbsp;<input type='password' name='pass' id='pass' /></div>";
	}
}
//privacy options comments
function privacy_comment_options()
{
	if(document.getElementById("privacy_comment").value=="ano" || document.getElementById("privacy_comment").value=="post_as_this_nick")
	{  
		document.getElementById("privacy_comment_options_box").innerHTML="";
		document.getElementById("privacy_comment_options_box").innerHTML="<input type='hidden' name='name_comment' id='name_comment' /> <input type='hidden' name='email_comment' id='email_comment' />  <input type='hidden' name='pass_comment' id='pass_comment' /> ";
		
	}else if(document.getElementById("privacy_comment").value=="nick_new"){
		document.getElementById("privacy_comment_options_box").innerHTML="Nickname: <input type='text' name='name_comment' id='name_comment' />  <div class='px_padding'> Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='email_comment' id='email_comment' /> </div><div class='px_padding'>Password: &nbsp;<input type='password' name='pass_comment' id='pass_comment' /></div>";
	}else{ //existing nick
		document.getElementById("privacy_comment_options_box").innerHTML="<input type='hidden' name='name_comment' id='name_comment' /> <br /> <div class='px_padding'> Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='email_comment' id='email_comment' /> </div><div class='px_padding'>Password: &nbsp;<input type='password' name='pass_comment' id='pass_comment' /></div>";
	}
}
/*
function privacy_comment_options()
{
	if(document.getElementById("privacy_comment").value=="ano")
	{  
		document.getElementById("privacy_comment_options_box").innerHTML="";
		document.getElementById("privacy_comment_options_box").innerHTML="<input type='hidden' name='name_comment' id='name_comment' /> <input type='hidden' name='email_comment' id='email_comment' /> ";
		
	}else{
		document.getElementById("privacy_comment_options_box").innerHTML="Nickname: <input type='text' name='name_comment' id='name_comment' /> <br /> <div class='px_padding'> Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='email_comment' id='email_comment' /> </div>"
	}
}
*/
//load more
function more_detail(para_id)
{
	//alert(para_id);
    var body = $('body');
	var content = $('<div class="content"></div>');
	var cover = $('<div class="cover"></div>');
	var close = $('<a class="close" href="#">x</a>');
	var win = {
		w: $(window).width(),
		h: $(window).height()
	};

		var dataString = 'id=' + para_id;
		//alert(dataString);
		
		body.append(cover);
		$("#loading_para").show();
		$("#loading_para").fadeIn(500).html('<img src="public/images/ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading"><b>Retrieving full writing.Please wait...</b></span>');
		
		$.ajax({
			type: 'POST',
			url: 'ajax_req_res/more_detail_main.php',
			data: dataString,
			success: function(html) {
				$('#loading_para').hide();
				body.append(content);
				content.html('');
				content.append(close);
				content.append(html);
				content.css({
					'left': (win.w / 2) - (content.width() / 2),
					'top': (win.h / 2) - (content.height() / 2)
				});
				close.on('click', function(e) {
					e.preventDefault();
					cover.remove();
					content.remove();
				});
			}
		});

		cover.on('click', function() {
			cover.remove();
			content.remove();
		});
	
  return false; 
}

//post comment
function post_comment(para_id)
{		
		var comment=$("#comment").val();
		var privacy=$("#privacy_comment").val();
		var name=$("#name_comment").val();
		var email=$("#email_comment").val();
		var existing_nick_id=$("#existing_nick_id").val();
		var pass=$("#pass_comment").val();
		//alert(name);
		//var form_data='comment='+ comment +  '&name_comment=' + name + '&email_comment=' + email + '&privacy_comment=' + privacy + '&id=' + para_id;
		var dataString = 'id=' + para_id + '&comment='+ comment +  '&name_comment=' + name + '&email_comment=' + email + '&privacy_comment=' + privacy + '&pass=' + pass + '&existing_nick_id=' + existing_nick_id;
		$("#comment_loading").show();
		$("#comment_posting").show();
		$("#comment_loading").fadeIn(500).html('<img src="public/images/ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading"><b>Retrieving full writing.Please wait...</b></span>');		
		$.ajax({
			type: 'POST',
			url: 'ajax_req_res/post_comments.php',
			data: dataString,
			success: function(html) {
				$('#inputcommentarea').hide();
				$('#comment_loading').hide();
				$('#comment_posting').append(html);
			}
		});
	
  return false; 
}

//post user reaction-like,dislike, report
//post comment
function user_reaction(post_id,emotion_id)
{		
		$('#comment_emotion_box').hide();
		if(emotion_id==1)
		{
			var likes=document.getElementById('comment_like').value;
			likes++;
			document.getElementById('comment_like').value=likes;
			document.getElementById('comment_emotion_confirmation').innerHTML='<b>You liked this post..</b>';
		}
		if(emotion_id==2)
		{
			var likes=document.getElementById('comment_dislike').value;
			likes++;
			document.getElementById('comment_dislike').value=likes;
			document.getElementById('comment_emotion_confirmation').innerHTML='<b>You disliked this post..</b>';
		}
		if(emotion_id==3)
		{
			var likes=document.getElementById('comment_abuse').value;
			likes++;
			document.getElementById('comment_abuse').value=likes;
			document.getElementById('comment_emotion_confirmation').innerHTML='<b>You reported this post as abuse..</b>';
		}
		
		
					
		var dataString = 'id=' + post_id + '&emotion_id='+ emotion_id;
		$.ajax({
			type: 'POST',
			url: 'ajax_req_res/post_comment_emotions.php',
			data: dataString
		});
	
  return; 
}

</script>
<style>
body {
    font: normal 18px Book Antiqua;
	margin:0;
}

#top_most_nav {
	width:100%;	
	padding-top:35px;
}

#content_paragraphs {
	width:100%;
	min-height:1100px;
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

.paragraph_submit {
    color: black;
	background:#f2f2f2;
}
#inputtextarea {
	width: 400px;
	min-height:100px;
	float:left;
	background:#f2f2f2;
	margin:12px;
	padding:10px;
	text-align: justify;
	border-radius: 8px;
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

.cover {
	width: 100%;
	height: 100%;
	position: fixed;
	top: 0;
	left: 0;
	background: rgba(0, 0, 0, 0.4);
	z-index: 5;
	
}

.content {
	width: 80%;
	height:80%;
	padding: 0 10px;
	background: #fff;
	border: 2px solid #f2f2f2;
	border-radius: 10px;
	position: absolute;
	z-index: 10;
	overflow:scroll;
}
.close {
	position: absolute;
	top: 5px;
	right: 10px;
	color: #000;
	text-decoration: none;
	font-weight: bold;
}
.close:hover {
	text-decoration: underline;
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
function myFunction() {
    var x = document.menu_function("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>
</head>
<body>
<div id="whole_page">
<div id="top_menu">
<?php include_once "t_top_menu.php"; ?>
</div>
<div id="top_most_nav">
<span> <font color="black" size="6"><a style='text-decoration:none;color:black' href="./">Vague Text</a></font> </span>  &nbsp;&nbsp;&nbsp;
	<span> <font color="808080" size="4">Express your likes, feelings, beliefs, confessions, vague thoughts.  </font> </span>&nbsp;||&nbsp;&nbsp;
	<span> <font color="808080" size="4">Read similar writings.  </font> </span>&nbsp;||&nbsp;&nbsp;
	<span> <font color="808080" size="4">Stay anonymous.   </font> </span> 
</span>

</div>


<div id="inputtextarea">
<form action="#" method="post">
    Write anything .... <br />
	<textarea rows="8" cols="50" name="paragraph" id="paragraph"></textarea><br /><br />
	Title or Headline <br /> <input type="text" name="title" id="title" size="47" />
	<div class="px_padding">
	<select id="privacy" name="privacy" onchange="privacy_options()">
		<option value="ano">Post anonymously</option>
<?php if($c_nick_id!=''){?>    
		<option value="post_as_this_nick">Post as nick: <?php echo $c_nick; ?></option>
 <?php }?>
		<option value="nick_new">Post with new nickname</option>
		<option value="nick">Post with other existing nickname</option>
	</select>
	<input type='hidden' name='existing_nick_id' id='existing_nick_id' value='<?php echo $c_nick_id; ?>'/> 
	</div>
	<div id="privacy_options_box"><input type='hidden' name='name' id='name' /> <input type='hidden' name='email' id='email' /> <input type='hidden' name='pass' id='pass' /></div>
	<div class="px_padding">
		<input type="submit" class="paragraph_submit"  value="Post" /> <br /><br />
	</div>
</form>
<div id="loading" align="left"  ></div>
<div  id="paragraph_posting">

</div>
</div>




<div id="content_paragraphs">
<div id="loading_para" align="right"></div>
	
</div>
<div id="loader"><img src="public/images/ajax-loader.gif" /></div>
</div>
</body>
</html>