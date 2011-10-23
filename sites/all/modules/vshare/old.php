<?php

/*
 *	This section of code uses the whole $node-> encoding
 *  There's lots of work to still do here... but the basic concept is integrated into our pages now.
 */

function vshare_small($thing)
{

	if(!isset($thing)) { echo "no item"; return; }

	if($_GET['debugVshare']=='showThing') {
		echo "<pre>"; var_dump($thing); exit;
	}

	if(isset($thing->nid)) {	// thing is a node
		$text = "item";
		$url = "node";
		$item = $thing->nid;
	}
	else if(isset($thing->cid)) {	// thing is a collection
		$text = "collection";
		$url = "collections";
		$item = $thing->cid;
	}
	else
		return "unknown type: $thing[0] ";

	$result = "<!-- from /sites/all/modules/vshare/vshare_small.php -->
		<div class='minisocialbar socialbar'>
		<div style='clear: right; float: left;'>

			<a class='automodal' href='mailto:&body=Thought%20of%20you%20when%20I%20saw%20this%20on%20Vibio!%0dhttp%3A%2F%2FVibio.com%2F$url%2F" . $item . " &t=Saw%20this%20on%20Vibio' title='email to a friend'>
			  <img src='/sites/all/themes/vibio/images/vshare/mail.gif'></a>
			<a class='automodal' href='http://www.facebook.com/sharer.php?u=http%3A%2F%2FVibio.com%2F$url%2F" . $item . "&t=Saw%20this%20$text%20on%20Vibio' title='Share on Facebook'>
			  <img  src='/themes/vibio/images/facebook/share.png'></a>
			<a class='automodal' href='http://twitter.com/home/?status=Saw%20this%20$text%20on%20Vibio:%0D http%3A//Vibio.com/$url/" . $item . "' title='Tweet this'>
			  <img src='/sites/all/modules/orig_core_modules/tweet/twitter.png'></a>

<iframe src='//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fdev.vibio.com%2F$url%2F" . $item . "&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=verdana&amp;height=21' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:90px; height:21px;' allowTransparency='true'></iframe>

		</div>
		</div>
	";

	//  print fivestar_widget_form($node);
	// <fb:like href='http://dev.vibio.com/$url/" . $node->nid ."' send='false' layout='button_count' width='90' show_faces='false' font='verdana'></fb:like>
	return $result;
}
