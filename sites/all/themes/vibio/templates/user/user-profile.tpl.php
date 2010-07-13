<div class="profile">
  <?php print $user_profile; ?>
</div>

<div id="user_ebay_items"></div>

<div id="user_dos">
	<?php echo "your relation to this user: <xmp>".print_r($profile['dos'], true)."</xmp>"; ?>
</div>

<div id="user_social_info">
	<?php echo $profile['social_info']; ?>
</div>