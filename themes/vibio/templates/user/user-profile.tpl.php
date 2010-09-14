<div class="profile">
  <?php
  echo $profile['user_picture'];
  ?>
</div>

<?php
echo "profile completeness: {$profile['profile_progress']}%";
echo $profile['uri_actions']['actions'];
?>

<div id="user_dos">
	<?php echo "your relation to this user: <xmp>".print_r($profile['dos'], true)."</xmp>"; ?>
</div>

<div id="user_social_info">
	<?php echo $profile['social_info']; ?>
</div>