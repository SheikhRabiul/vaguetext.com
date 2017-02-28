<?php
sleep(1); // to test ajax loading is showing, this sleep function will be removed in future. 
include('../database/config.php');
$link=db_connect();
$post_meta_id = 0;
$emotion_id=0;
if (isset($_POST['id'])) 
{
	$post_meta_id = intval($_POST['id']);
	$emotion_id = intval($_POST['emotion_id']);


	$sql="";
	if($emotion_id==1)
	{
		$sql="update `post_metas` set `likes`=`likes`+? where id='$post_meta_id'";
	}
	if($emotion_id==2)
	{
		$sql="update `post_metas` set `dislikes`=`dislikes`+? where id='$post_meta_id'";
	}
	if($emotion_id==3)
	{
		$sql="update `post_metas` set `abusive`=`abusive`+? where id='$post_meta_id'";
	}
	
	if($stmt=$link->prepare($sql))
	{	
		$id1=1;
		$stmt->bind_param("i",$id1); //bind variable to prepared stmt
		$stmt->execute(); // execute query
		if($stmt->affected_rows>0)
		{	
			//echo "okk";
		}
	}	
}
db_close($link);

?>
