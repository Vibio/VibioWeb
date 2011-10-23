<?php

/*
 *	This section of code uses the whole $node-> encoding
 *  There's lots of work to still do here... but the basic concept is integrated into our pages now.
 */

function vshare_small($thing) { return vshare_big($thing); }

function vibio_addthis_button($variables) {
return '
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4ea1ec0253978f88"></script>
<!-- AddThis Button END -->
';
}
function Xvibio_addthis_button($variables) {
  $build_mode = $variables['build_mode'];
  $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
  if (variable_get('addthis_dropdown_disabled', '0')) {
    return ( sprintf('
      <a href="http://www.addthis.com/bookmark.php"
        onclick="addthis_url   = location.href; addthis_title = document.title; return addthis_click(this);">
      <img src="%s" width="%d" height="%d" %s /></a>
      ',
      $https ? addslashes(variable_get('addthis_image_secure', 'https://secure.addthis.com/button1-share.gif')) : 
addslashes(variable_get('addthis_image', 'http://s9.addthis.com/button1-share.gif')),
      addslashes(variable_get('addthis_image_width', '125')),
      addslashes(variable_get('addthis_image_height', '16')),
      addslashes(variable_get('addthis_image_attributes', 'alt=""'))
    ));
  }
  else {//customized code to display big add this buttons via text decoration
    $options=explode(',',variable_get('addthis_options','expanded'));
    foreach($options as &$service){
        $service = trim($service);
        $service = '<a class="addthis_button_' . $service . '">
<img src="/'. path_to_theme() . '/images/addthis/' . $service . '.png" alt="' . $service . '"/></a>';
  }
  return (sprintf('
      <!-- AddThis Button BEGIN -->
      <div class="addthis_toolbox">
      <!-- h3 class="title">Share this!</h3 -->
      <div class="custom_images">
      %s
      </div>
      </div>
      <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?pub=%s"></script>
      <!-- AddThis Button END -->',
      implode("\n",$options),
      variable_get('addthis_username', '')
      ));
   } // end custom code
}

/*
function Vibio_addthis_button($node, $teaser) {
  if (variable_get('addthis_dropdown_disabled', '0')) {
    return ( sprintf('
      <a href="http://www.addthis.com/bookmark.php"
        onclick="addthis_url   = location.href; addthis_title = document.title; return addthis_click(this);">
      <img src="%s" width="%d" height="%d" %s /></a>
      ',
      $_SERVER['HTTPS'] == 'on' ? addslashes(variable_get('addthis_image_secure', 'https://secure.addthis.com/button1-share.gif')) : addslashes(variable_get('addthis_image', 'http://s9.addthis.com/button1-share.gif')),
      addslashes(variable_get('addthis_image_width', '125')),
      addslashes(variable_get('addthis_image_height', '16')),
      addslashes(variable_get('addthis_image_attributes', 'alt=""'))
    ));
  }
  else { //customized code to display big add this buttons via text decoration
    $options=explode(',',variable_get('addthis_options','twitter,facebook,myspace,digg,linkedin,delicious,email,expanded'));
    foreach($options as &$service){
        $service = '<a class="addthis_button_' .$service . '"><img src="'.base_path().'sites/default/themes/opus/images/socialme/' . $service . '.png" width="60" height="60" alt="' . $serivice . '"/></a>';   
    }
    return (sprintf('
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox">
<h3 class="title">Share this!</h3>
<div class="custom_images">
%s
</div>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?pub=opusmarketing"></script>
<!-- AddThis Button END -->
    ',
    implode("\n",$options)
    ));
  } // end custom code
}
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
/*
	$result .= "
		<div class='medsocialbar socialbar'>
		  <div style='clear: right; float: left;'>

			<!-- a class='automodal' href='mailto:&body=Thought%20of%20you%20when%20I%20saw%20this%20on%20Vibio!%0dhttp%3A%2F%2FVibio.com%2F$url%2F$item&t=Saw%20this%20on%20Vibio' title='email to a friend' -->
			  <img class='vshare' src='/sites/all/themes/vibio/images/vshare/mail.gif'></a>
			<a class='automodal' href='http://www.facebook.com/sharer.php?u=http%3A%2F%2FVibio.com%2F$url%2F$item&t=Saw%20this%20$item%20on%20Vibio' title='Share on Facebook'>
			  <img class='vshare' src='/themes/vibio/images/facebook/share.png'></a>
			<a class='automodal' href='http://twitter.com/intent/tweet?source-weblinet&text=Saw%20this%20$item%20on%20Vibio:%0D http%3A//Vibio.com/$url/$item' title='Tweet this'>
			  <img class='vshare' src='/sites/all/modules/orig_core_modules/tweet/twitter.png'></a>
			<div class='fb-like' data-href='http://dev.vibio.com/$url/$item' data-send='true' data-layout='button_count' data-width='400' data-show-faces='false' data-font='verdana'></div>
		  </div>
		</div>
	";
*/

	$result = vibio_addthis_button($variables);

	//  print fivestar_widget_form($thing);
	// <fb:like href='http://dev.vibio.com/$url/$item' send='false' layout='button_count' width='90' show_faces='false' font='verdana'></fb:like>
	return $result;
}
