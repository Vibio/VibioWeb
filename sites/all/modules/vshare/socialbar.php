<?php

/*
 *	This section of code uses the whole $node-> encoding
 *  There's lots of work to still do here... but the basic concept is integrated into our pages now.
 */
if($_GET['D_comment']==1) {
	echo "<pre>";
	var_dump($node); exit;
}
?>
<!-- from /sites/all/themes/vibio/socialbar.php -->
<div class="socialbar" style="height: 28px;">
<div style="float: left;">
<?php
	if(!isset($node)) {
	} else {
		if($node->nid == 0)
			$node->nid = 1;
		if($node->nid == basename($_ENV['URL']))
			;
		echo '	<a class="socialbar" href="/node/'. $node->nid .'" title="click to read comments">';
		echo '		<img src="/sites/all/themes/vibio/images/socialbar/comment.gif">';
		if($node->comment_count) {
			echo $node->comment_count . " Comment"; 
			if($node->comment_count!=1) echo "s";
			echo "</a>";
		} else {
			echo "No Comments";
		}
		echo "</a>";
	}
?>
&nbsp;<a id="socialbar-add" href="comment/reply/<?php echo $node->nid; ?>#comment-form"><img src="/sites/all/themes/vibio/images/socialbar/add.gif" title="click to add a comment"></a>
		<img src="/sites/all/themes/vibio/images/socialbar/separator.gif">
	<a href="mailto:&body=http%3A%2F%2Fvibio.com%2Fnode%2F<?php echo $node->nid; ?>&t=Saw%20this%20on%20Vibio" title="email to a friend">
      <img src="/sites/all/themes/vibio/images/socialbar/mail.gif">Share&nbsp;</a>
		<img src="/sites/all/themes/vibio/images/socialbar/separator.gif">
	<a target="_blank" href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fvibio.com%2Fnode%2F<?php echo $node->nid; ?>&t=Saw%20this%20on%20Vibio" title="Share on Facebook">
      <img src="/sites/all/themes/vibio/images/socialbar/facebook.gif">Facebook&nbsp;</a>
		<img src="/sites/all/themes/vibio/images/socialbar/separator.gif">
	<a target="_blank" href="http://twitter.com/home/?status=Saw%20this%20on%20Vibio:%0D http%3A//vibio.com/node/<?php echo $node->nid; ?>" title="Tweet this">
      <img src="/sites/all/themes/vibio/images/socialbar/twitter.gif">Twitter&nbsp;</a>
		<img src="/sites/all/themes/vibio/images/socialbar/separator.gif">
</div>
<div style="float: left; width: 100px; height: 20px; overflow: hidden; margin: 4px;">
<?php
  print fivestar_widget_form($node);
?>
</div>
</div>
