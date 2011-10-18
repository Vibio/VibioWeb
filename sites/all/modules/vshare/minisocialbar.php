<?php

/*
 *	This section of code uses the whole $node-> encoding
 *  There's lots of work to still do here... but the basic concept is integrated into our pages now.
 */
if(!isset($node)) return;
?>
<!-- from /sites/all/themes/vibio/socialbar.php -->
<div class="minisocialbar socialbar">
<div style="float: left;">
<?php
	echo '    <a class="socialbar" href="/node/'. $node->nid .'" title="click to read comments">';
	echo $node->comment_count,'<img src="/sites/all/themes/vibio/images/socialbar/comment.gif"></a>';
?>
&nbsp;<a id="socialbar-add" href="/comment/reply/<?php echo $node->nid; ?>#comment-form"><img src="/sites/all/themes/vibio/images/socialbar/add.gif" title="click to add a comment"></a>
		<img src="/sites/all/themes/vibio/images/socialbar/separator.gif">
	<a href="mailto:&body=http%3A%2F%2FVibio.com%2Fnode%2F<?php echo $node->nid; ?> &t=Saw%20this%20on%20Vibio" title="email to a friend">
      <img src="/sites/all/themes/vibio/images/socialbar/mail.gif"></a>
		<img src="/sites/all/themes/vibio/images/socialbar/separator.gif">
	<a target="_blank" href="http://www.facebook.com/sharer.php?u=http%3A%2F%2FVibio.com%2Fnode%2F<?php echo $node->nid; ?>&t=Saw%20this%20product%20on%20Vibio" title="Share on Facebook">
      <img src="/sites/all/themes/vibio/images/socialbar/facebook.gif"></a>
		<img src="/sites/all/themes/vibio/images/socialbar/separator.gif">
	<a target="_blank" href="http://twitter.com/home/?status=Saw%20this%20product%20on%20Vibio:%0D http%3A//Vibio.com/node/<?php echo $node->nid; ?>" title="Tweet this">
      <img src="/sites/all/themes/vibio/images/socialbar/twitter.gif"></a>
		<img src="/sites/all/themes/vibio/images/socialbar/separator.gif">
</div>
<div style="float: left; width: 100px; height: 20px; overflow: hidden; margin: 4px;">
<?php
  print fivestar_widget_form($node);
?>
</div>
</div>
