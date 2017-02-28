<?php
include_once '../database/config.php';
include_once '../function/common.php';
$c_nick_id='';
$c_nick='';
$link=db_connect();
$nick_id='';
if(isset($_COOKIE['nick_id_c']) && isset($_COOKIE['nick_c']))
{
	$c_nick_id=$_COOKIE['nick_id_c'];
	$c_nick=$_COOKIE['nick_c'];
	$nick_id=$c_nick_id;
}
//echo $_GET['id'];
//echo "<br />";
//echo decrypt('SCk4DgLEKx9VmFU6vyJVZB4o6ujKW+I+WAMm7Z+rP3g=');exit;

if(isset($_GET['id']))
{  
	$nick_id=decrypt(mysqli_real_escape_string($link,$_GET['id']));
}	

//echo $nick_id;exit;
?>
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
	border: 3px solid #000;
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
<script type="text/javascript">
// loading more contents based on page scrolling..
var last_id = 0; //track user scroll as page number, right now page number is 1
var loading  = false; //prevents multiple loads
var nick_id=<?php echo $nick_id; ?>;
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
		$.post( '../ajax_req_res/menu/profile_load_more.php', {'last_id': last_id,'nick_id': nick_id}, function(data){
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

//load more
function more_detail(para_id)
{
	//alert(para_id);
    var body = $('body');
	var content = $('<div class="content"></div>');
	var cover = $('<div class="cover"></div>');
	var close = $('<a class="close" href="#">x</a>');
	var from_profile='yes';
	var win = {
		w: $(window).width(),
		h: $(window).height()
	};

		var dataString = 'id=' + para_id + '&from_profile=' + from_profile;
		//alert(dataString);
		
		body.append(cover);
		$("#loading_para").show();
		$("#loading_para").fadeIn(500).html('<img src="../public/images/ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading"><b>Retrieving full writing.Please wait...</b></span>');
		
		$.ajax({
			type: 'POST',
			url: '../ajax_req_res/more_detail_main.php',
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
		//$("#comment_posting").show();
		$("#comment_loading").fadeIn(500).html('<img src="../public/images/ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading"><b>Retrieving full writing.Please wait...</b></span>');		
		$.ajax({
			type: 'POST',
			url: '../ajax_req_res/post_comments.php',
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
<div id="content_paragraphs">

	
</div>
<div id="loader" style='display:none'><img src="../public/images/ajax-loader.gif" />
</div>
