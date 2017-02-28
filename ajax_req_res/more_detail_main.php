<?php
sleep(1); // to test ajax loading is showing, this sleep function will be removed in future. 
$c_nick_id='';
$c_nick='';
if(isset($_COOKIE['nick_id_c']) && isset($_COOKIE['nick_c']))
{
	$c_nick_id=$_COOKIE['nick_id_c'];
	$c_nick=$_COOKIE['nick_c'];
}

?>
<style>
.content_comment{
	width: 400px;
	min-height:20px;
	float:left;
	background:#f2f2f2;
	margin:12px;
	padding:10px;
	text-align: justify;
	border-radius: 8px;
}
#comment_posting
{
	width: 400px;
	float:left;
	background:#f2f2f2;
	margin:12px;
	padding:10px;
	text-align: justify;
	border-radius: 8px;
}
.span_right_comment{
 float:right;
 font-style: italic;
}

.span_left_comment{
 float:left;
 font-style: italic;
}
#inputcommentarea {
	width: 400px;
	min-height:100px;
	float:left;
	background:#f2f2f2;
	margin:12px;
	padding:10px;
	text-align: justify;
	border-radius: 8px;
	border: 1px solid #AAB7B8;
	
}
.comment_submit{
	 color: black;
	background:#f2f2f2;
}
</style>
<?php
include('../database/config.php');
$link=db_connect();
//sleep(1);
// get request params
$id = 0;
if (isset($_POST['id'])) {
	$id = intval($_POST['id']);
}





$sql="SELECT a.`id`, a.`title`, a.`nickname`, a.`email`, a.`nickname_sys`, a.`nick_id`, `created`, a.`likes`, a.`dislikes`, a.`abusive`,b.`post` FROM  `post_metas` a,`posts` b where a.`id` =? and  a.`id` = b.`post_meta_id`";

if($stmt=$link->prepare($sql))
{	
$stmt->bind_param("i",$id); //bind variable to prepared stmt
$stmt->execute(); // execute query
$result=$stmt->store_result(); // Store the whole result in buffer(to get properties)
$num_rows=$stmt->num_rows;
//bind result to variables
$stmt->bind_result($id, $title, $nickname,$email, $nickname_sys, $nick_id, $created,$likes, $dislikes, $abusive, $post);


	 while ($stmt->fetch()) {	
			echo "<div style='background:#f2f2f2;margin:12px;padding:10px;text-align: justify;border-radius: 8px;float:left;width:400px;border:1px solid #7F8C8D;'>";
				if($title!='')
				{	
					echo "<b style='color:#4d4d4d'>".stripslashes($title)."</b>"."<hr>";
				}
				echo "<div style='min-height:100px;'>";
				$post=str_replace(array("\\r\\n", "\\r", "\\n"), "<br />", $post);
				echo stripslashes($post); 
				echo "</div>";
				echo "<div style='clear:both'>";
				if($nickname=='') $nickname='anonymous';
					echo "<span class='span_left_comment'>"."-- ".stripslashes($nickname)."&nbsp;</span>";
					echo "<span class='span_left_comment'>"." &nbsp;&nbsp;&nbsp;";
						
						$comment_like_size=strlen($likes);
						$comment_dislike_size=strlen($dislikes);
						$comment_abusive_size=strlen($abusive);
						echo "<input id='comment_like' size='$comment_like_size' type='text' value='$likes' disabled/>"." likes, ";
						echo "<input id='comment_dislike' size='$comment_dislike_size' type='text' value='$dislikes' disabled/>"." dislikes, ";
						echo "<input id='comment_abuse' size='$comment_abusive_size' type='text' value='$abusive' disabled/>"." abuse reports, ";
						
					echo "</span>";
					//echo "<span id='comment_emotion_confirmation'> </span>";
					echo "<span class='span_right_comment'>"."&nbsp;&nbsp;&nbsp;&nbsp;".date("M d, Y", strtotime($created))."</span>";
			
				echo "</div>";
				echo "<div style='clear:both' id='comment_emotion_confirmation'>"; echo "</div>";
				echo "<div style='clear:both' id='comment_emotion_box'>";
				
				if (isset($_POST['from_profile']) && $_POST['from_profile']=='yes')
				{
					echo "<span class='span_left_comment'> <a href='#' onClick='user_reaction($id,1);'><img src='../public/images/like.png' height='30' width='48' onmouseover=\"this.src='../public/images/like-hover.png';\" onmouseout=\"this.src='../public/images/like.png';\"  alt='Like'/></a></span>";
					echo "<span class='span_left_comment'><a href='#' onClick='user_reaction($id,2);'><img src='../public/images/dislike.png' height='30' width='66' onmouseover=\"this.src='../public/images/dislike-hover.png';\" onmouseout=\"this.src='../public/images/dislike.png';\"  alt='Dislike'/></span></a>";
					echo "<span class='span_right_comment'><a href='#' onClick='user_reaction($id,3);'><img src='../public/images/abuse.png' height='30' width='110' onmouseover=\"this.src='../public/images/abuse-hover.png';\" onmouseout=\"this.src='../public/images/abuse.png';\" alt='Abuse' /></span></a>";
			
				}else{
					echo "<span class='span_left_comment'> <a href='#' onClick='user_reaction($id,1);'><img src='public/images/like.png' height='30' width='48' onmouseover=\"this.src='public/images/like-hover.png';\" onmouseout=\"this.src='public/images/like.png';\"  alt='Like'/></a></span>";
					echo "<span class='span_left_comment'><a href='#' onClick='user_reaction($id,2);'><img src='public/images/dislike.png' height='30' width='66' onmouseover=\"this.src='public/images/dislike-hover.png';\" onmouseout=\"this.src='public/images/dislike.png';\"  alt='Dislike'/></span></a>";
					echo "<span class='span_right_comment'><a href='#' onClick='user_reaction($id,3);'><img src='public/images/abuse.png' height='30' width='110' onmouseover=\"this.src='public/images/abuse-hover.png';\" onmouseout=\"this.src='public/images/abuse.png';\" alt='Abuse' /></span></a>";
				}
				echo "</div>";
				
			echo "</div>";
	}
	$stmt->free_result(); // free result
	$stmt->close(); // close statement
}


?>
<div id="inputcommentarea">
    Epress your thinking about the post without any hesitation... <br />
	<textarea rows="2" cols="40" name="comment" id="comment"></textarea>
	<div class="px_padding">
	<select id="privacy_comment" name="privacy_comment" onchange="privacy_comment_options()">
		<option value="ano">Post anonymously</option>
		<?php if($c_nick_id!=''){?>    
		<option value="post_as_this_nick">Post as nick: <?php echo $c_nick; ?></option>
		<?php }?>
		<option value="nick_new">Post with completely new nickname</option>
		<option value="nick">Post with a previously created nickname</option>	
	</select>
	<input type='hidden' name='existing_nick_id' id='existing_nick_id' value='<?php echo $c_nick_id; ?>'/> 
	</div>
	<div id="privacy_comment_options_box"><input type='hidden' name='name_comment' id='name_comment' /> <input type='hidden' name='email_comment' id='email_comment' /> <input type='hidden' name='pass_comment' id='pass_comment' /></div>
	<div class="px_padding">
		<input type="submit" class="comment_submit"  value="Post"  onClick='post_comment(<?php echo $id;?>);'  /> <br />
	</div>
</div>
<div id="comment_loading" align="left"  ></div>
<div  id="comment_posting" style='display:none'>

</div>

<?php
$sql="SELECT `id`,`comment`, `nickname`, `nickname_sys`, `email`, `nick_id`, `created`,`likes`, `dislikes`, `abusive` FROM `comments` where `post_meta_id`= ? order by id desc limit 100";
	if($stmt=$link->prepare($sql))
	{	
		$stmt->bind_param("i",$id); //bind variable to prepared stmt
		$stmt->execute(); // execute query
		$result=$stmt->store_result(); // Store the whole result in buffer(to get properties)
		$num_rows=$stmt->num_rows;
		
		//bind result to variables
		if($num_rows>0)
		{
		$stmt->bind_result($comment_id, $comment, $nickname,$nickname_sys,$email,  $nick_id, $created,$likes, $dislikes, $abusive);
			while ($stmt->fetch()) {	
				echo "<div class='content_comment'>";
						$comment=str_replace(array("\\r\\n", "\\r", "\\n"), "<br />", $comment);
						echo stripslashes($comment); 
						echo "<div style='clear:both'>";
						if($nickname!='')
						{	echo "<span class='span_left'><font size='2'>"."--".stripslashes($nickname)."</font>&nbsp;</span>";
							echo "<span><font size='3'><i>"." &nbsp;".$likes." likes, ".$dislikes." dislikes &nbsp;"."</i></font></span>";
							echo "<span class='span_right'><font size='2'><i>".date("M d, Y", strtotime($created))."</i></font></span>";
						}else{
							echo "<span class='span_left'><font size='3'><i>"."-- "."anonymous"."&nbsp;</i></font></span>";
							echo "<span><font size='3'><i>"." &nbsp;&nbsp;&nbsp;".$likes." likes, ".$dislikes." dislikes &nbsp;"."</i></font></span>";
							echo "<span class='span_right'><font size='2'><i>".date("M d, Y", strtotime($created))."</i></font></span>";
						}
						echo "</div>";
				echo "</div>";
			}
		}	
		$stmt->free_result(); // free result
		$stmt->close(); // close statement
	}

db_close($link);
?>
