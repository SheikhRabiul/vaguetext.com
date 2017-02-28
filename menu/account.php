<?php
if(isset($_GET['ac']) && $_GET['ac']=='pr')
{
?>
<div id="forgot_password_v">
<form action="#" method="post">
    <b style='color:#4d4d4d'> Password Reset: Final step </b> <br /> <hr />
	Email: <br /> <input type="text" name="fp_email_v"  id="fp_email_v" size="49" /> <br />
	<div class="px_padding">
		New Password: <br /><input type="password" name="pass1"  id="pass1"  value="" />
	</div>
	<div class="px_padding">
		Retype New Password: <br /><input type="password" name="pass2"  id="pass2"  value="" /> 
	</div>
	<div class="px_padding">
		<input type="hidden" name="v1"  id="v1"  value="<?php echo $ac_fp_v1;?>" />
		<input type="hidden" name="v2"  id="v2"  value="<?php echo $ac_fp_v2;?>" />
		<input type="submit" class="forgot_password_submit_v"  value="Send" /> <br /><br /> 
	</div>
</form>
<div id="fp_loading" align="left"  ></div>
<div  id="forget_password_posting_v">

</div>
</div>

<?php }else{ ?>
<div id="forgot_password">
<form action="#" method="post">
    <b style='color:#4d4d4d'> Password Reset: </b> <br /> <hr />
	Email: <br /> <input type="text" name="fp_email"  id="fp_email" size="49" /> <br />
	<div class="px_padding">
		<input type="submit" class="forgot_password_submit"  value="Send" /> <br /><br />Please check your inbox and spam folder for password changing information
		after clicking the send button above.Please avoid clicking the button multiple time to avoid getting multiple emails.
	</div>
</form>
<div id="fp_loading" align="left"  ></div>
<div  id="forget_password_posting">

</div>
</div>
<?php } ?>