<?php

/*
 *	This section of code uses the whole $node-> encoding
 *  There's lots of work to still do here... but the basic concept is integrated into our pages now.
 */
function vshare_small($thing)
{
	if(!isset($thing)) { echo "no item"; return; }


return "<!-- from /sites/all/modules/vshare/vshare_small.php -->
	<div class='minisocialbar socialbar'>
	<div style='float: right;'>

<div class='fb-like' data-href='http://dev.vibio.com/node/" . $thing->nid . "' data-send='false' data-layout='button_count' data-width='450' data-show-faces='false' data-font='verdana'></div>

		<a href='mailto:&body=http%3A%2F%2FVibio.com%2Fnode%2F" . $thing->nid . " &t=Saw%20this%20on%20Vibio' title='email to a friend'>
	      <img src='/sites/all/themes/vibio/images/vshare/mail.gif'></a>
		<a target='_blank' href='http://www.facebook.com/sharer.php?u=http%3A%2F%2FVibio.com%2Fnode%2F" . $thing->nid . "&t=Saw%20this%20product%20on%20Vibio' title='Share on Facebook'>
	      <img src='/sites/all/themes/vibio/images/vshare/facebook.gif'></a>
		<a target='_blank' href='http://twitter.com/home/?status=Saw%20this%20product%20on%20Vibio:%0D http%3A//Vibio.com/node/" . $thing->nid . "' title='Tweet this'>
	      <img src='/sites/all/themes/vibio/images/vshare/twitter.gif'></a>
	</div>
	<div style='float: left; width: 100px; height: 20px; overflow: hidden; margin: 4px;'>
	</div>
	</div>
";

	//  print fivestar_widget_form($thing);
}
