<ul class="topnav" id="myTopnav">
  <li><a <?php if($active==1){ echo "class='active'";} ?> href="../">Home</a></li>
  <li><a <?php if($active==2){ echo "class='active'";} ?> href="../menu/?section=<?php echo urlencode('Profile');?>">Profile</a></li>
  <li><a <?php if($active==3){ echo "class='active'";} ?> href="../menu/?section=<?php echo urlencode('Account');?>">Account</a></li>
  <li><a <?php if($active==4){ echo "class='active'";} ?> href="../menu/?section=<?php echo urlencode('Contact');?>">Contact</a></li>
  <li><a <?php if($active==5){ echo "class='active'";} ?> href="../menu/?section=<?php echo urlencode('About');?>">About</a></li>
  <li class="icon">
	<a href="javascript:void(0);" style="font-size:15px;" onclick="menu_function()">?</a>
  </li>
</ul>