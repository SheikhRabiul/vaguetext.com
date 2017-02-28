<?php
sleep(1); // to test ajax loading is showing, this sleep function will be removed in future. 
include('../database/config.php');
include('../function/common.php');
$link=db_connect();
// get request params
$last_id=0;
if (isset($_POST['last_id'])) {
	$last_id = $_POST['last_id'];
}

$limit = 10; // default value

$end=$last_id-1;
$start=$last_id-$limit;
$last_row_fetched=0;
if($last_id!=0)
{	
	if($start<=0 && $end<=0) { echo "<div class='content_paragraphs_individual'>";echo "<h3> No more writing found...</h3>";echo "</div>"; exit;}
}

$sql="SELECT `id`, `post_brief`, `title`, `nickname`, `email`, `nickname_sys`, `nick_id`, `created`,`likes`, `dislikes`, `abusive` FROM post_metas where id between ? and ? ORDER BY id desc";
//$result=mysql_query($sql) or die(mysql_error());
//$sql="SELECT `id`, `post_brief`, `title`, `nickname`, `email`, `nickname_sys`, `nick_id`, `created`,`likes`, `dislikes`, `abusive` FROM post_metas limit 20";
if($last_id==0)
{
	$sql="SELECT `id`, `post_brief`, `title`, `nickname`, `email`, `nickname_sys`, `nick_id`, `created`,`likes`, `dislikes`, `abusive` FROM post_metas ORDER BY id desc limit 20";
}
//echo $sql."start:'$start',end:'$end'";
if($stmt=$link->prepare($sql))
{	
	if($last_id!=0)
	$stmt->bind_param("ii",$start,$end); //bind variable to prepared stmt
	$stmt->execute(); // execute query
	$result=$stmt->store_result(); // Store the whole result in buffer(to get properties)
	$num_rows=$stmt->num_rows;
	//bind result to variables
	$stmt->bind_result($id,$post_brief, $title, $nickname,$email, $nickname_sys, $nick_id, $created,$likes, $dislikes, $abusive);
		if($num_rows>0)
		{	
			while ($stmt->fetch())
			{	
				$last_row_fetched=$id;
				echo "<div class='content_paragraphs_individual'>";
					if($title!='')
					{	
						echo "<b style='color:#4d4d4d'>".stripslashes($title)."</b>"."<hr>";
					}
					echo "<div style='max-height:88px;overflow:hidden;display: block'>";
					$post_brief=str_replace(array("\\r\\n", "\\r", "\\n"), "<br />", $post_brief);
					echo stripslashes($post_brief); 
					echo "</div>";
					
					echo "<div>";
						echo "<div style='clear:both'>";
						echo "<span class='span_right'><font size='2'><a href='#' OnClick='more_detail($last_row_fetched);' > More detail.. </a></font><span>";
						echo "</div>";
					echo "</div>";
					echo "<div style='clear:both'>";
					if($nickname!='')
					{	$enc_nick_id=urlencode(encrypt($nick_id));
						echo "<span class='span_left'>"."--"."<a href='menu/?section=profile&id=$enc_nick_id'>".stripslashes($nickname)."</a>&nbsp;</span>";
						echo "<span>"."<font size='3'><i> &nbsp;".$likes." likes, ".$dislikes." dislikes</i></font>"."</span>";
						echo "<span class='span_right'><font size='2'><i>".date("M d, Y", strtotime($created))."</i></font></span>";
					}else{
						echo "<span class='span_left'><font size='3'><i>"."-- "."anonymous"."&nbsp;</i></font></span>";
						echo "<span><font size='3'><i>"." &nbsp;&nbsp;&nbsp;".$likes." likes, ".$dislikes." dislikes"."</i></font></span>";
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
if ($last_row_fetched != 0) {
	//echo "last:$last_row_fetched";
	echo '<script type="text/javascript">var last_id = '.$last_row_fetched.';</script>';
}

// sleep for 1 second to see loader, it must be deleted in prodection

?>



