<?php

/*
 *	This section of code uses the whole $node-> encoding
 *  There's lots of work to still do here... but the basic concept is integrated into our pages now.
 */
/* The following code has to be at the top of this Facebook implementation if the "Send" function is to work.
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js,fjs=d.getElementsByTagName(s)[0];
		  if(d.getElementById(id)){return;}
		  js=d.createElement(s);js.id=id;
		  js.src="//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js,fjs);
		}(document,'script','facebook-jssdk'));</script>
*/


function vshare_big($thing)
{
	//require_once getcwd() . '/sites/all/modules/vshare/vshare_small.php';
	//return vshare_small($thing);

	static $vshareFacebookJs = true;

	if($_GET['debugVshare']=='showThing') {
		echo "<pre>"; var_dump($thing); exit;
	}

	if(!isset($thing)) { return "no item"; }

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

	$result = "<!-- from /sites/all/modules/vshare/vshare_big.php -->\n";
	if(!$vshareFacebookJs) {
		$result .= "<script>(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return;}js=d.createElement(s);js.id=id;js.src='//connect.facebook.net/en_US/all.js#xfbml=1';fjs.parentNode.insertBefore(js,fjs);}(document,'script','facebook-jssdk'))\n</script>\n";
		$vshareFacebookJs = true;
	}

	$result .= "
		<div class='medsocialbar socialbar'>
		  <div style='clear: right; float: left;'>
			<a class='automodal' href='mailto:&body=Thought%20of%20you%20when%20I%20saw%20this%20on%20Vibio!%0dhttp%3A%2F%2FVibio.com%2F$url%2F$item&t=Saw%20this%20on%20Vibio' title='email to a friend'>
			  <img class='vshare' src='/sites/all/themes/vibio/images/vshare/mail.gif'></a>
			<a class='automodal' href='http://www.facebook.com/sharer.php?u=http%3A%2F%2FVibio.com%2F$url%2F$item&t=Saw%20this%20$item%20on%20Vibio' title='Share on Facebook'>
			  <img class='vshare' src='/themes/vibio/images/facebook/share.png'></a>
			<a class='automodal' href='http://twitter.com/home/?status=Saw%20this%20$item%20on%20Vibio:%0D http%3A//Vibio.com/$url/$item' title='Tweet this'>
			  <img class='vshare' src='/sites/all/modules/orig_core_modules/tweet/twitter.png'></a>
			<div class='fb-like' data-href='http://dev.vibio.com/$url/$item' data-send='true' data-layout='button_count' data-width='400' data-show-faces='false' data-font='verdana'></div>
		  </div>
		</div>
	";

	//  print fivestar_widget_form($thing);
	// <fb:like href='http://dev.vibio.com/$url/$item' send='false' layout='button_count' width='90' show_faces='false' font='verdana'></fb:like>
	return $result;
}
