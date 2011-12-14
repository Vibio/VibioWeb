<?php
/* quick reverse-engineer:  suspect class=fb_login clicks are dealt with
 *  by themes/vibio/js/facebook.js
 * If we switch to a normal facebook module, um...
 */


global $user;

if (!$user -> uid) {
  //Generates a fbconnect link
  if(module_exists('fboauth')){
    $link_attributes = fboauth_action_link_properties('connect');
    //Puts a fb button inside the link.
    $link = l("<img class='fb_login_button' src='/themes/vibio/images/facebook/login-button.png' />", $link_attributes['href'], array('query' => $link_attributes['query'], 'html' => TRUE));
  }
  echo "
		<span class='fb-or'>OR</span>
		<div class='fb_login'>
      " . $link . "
    </div>
	";
}
?>
