<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language -> language;?>" xml:fb="http://ogp.me/ns/fb#" lang="<?php print $language -> language;?>" dir="<?php print $language -> dir;?>">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="shortcut icon" href="/sites/all/themes/vibio/favicon.ico" type="image/x-icon" />
		<title><?php print $head_title;?></title>
		<meta name="description" content="Vibio is a social commerce tool that lets you buy, sell, and share items within your social graph" />
		<meta name="keywords" content="social commerce, inventory, buy, sell, share, social graph, friends, trade, collections" />
		<link rel="stylesheet" href="/themes/vibio/css/landing.css" />
		<link href='http://fonts.googleapis.com/css?family=Quicksand:300,400' rel='stylesheet' type='text/css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	</head>
	<body class="<?php print $classes;?>">
		<div class="region-header">
			<p id="head-image"><img src="/themes/vibio/images/landing/logo.png" alt="Vibio" />
			</p>
			<p id="head-left">
				Every <i>thing</i> tells a story...
			</p>
			<p id="head-right">
				Already a user? <a href="/user/login" target="_blank">Login</a>
			</p>
		</div>
		<?php
		echo $content;
		?>
		<div id="wrapper"><div class="region-content-bottom"><div id="content-bottom-left">
			<p id="quote">
				“ Vibio is amazing. It combines the best of Facebook and eBay to make it easy to buy and sell collections....”
				<br />
				<span style="font-size:18px">- Amy, CA</span>
			</p>
			<p id="facebook-bottom">
				<?php
				if (module_exists('fboauth')) {
					$link_attributes = fboauth_action_link_properties('connect');
					//Puts a fb button inside the link.
					$link = l('<img src="/themes/vibio/images/btn_fb_login.png" id="facebook-large" class="fb_login"/>', $link_attributes['href'], array('query' => $link_attributes['query'], 'html' => TRUE));
				}
				?>
				<?php print $link;?>
			</p>
		</div>
		<div id="content-bottom-right">
			<iframe src="http://player.vimeo.com/video/33426188?title=0&amp;byline=0&amp;portrait=0" width="469" height="264" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
		</div></div></div><div class="clears"></div>
		<div class="region-footer">
			<p>Copyright &copy; 2012. Vibio&trade;</p>
		<script type="text/javascript">
			$(document).ready(function() {

				$('#nav ul li:eq(0)').click(function() {
					$('li').removeClass('active');
					$(this).addClass('active');
					$('#photo-3, #photo-2').hide();
					$('#photo-1').fadeIn();
				});
				$('#nav ul li:eq(1)').click(function() {
					$('li').removeClass('active');
					$(this).addClass('active');
					$('#photo-1, #photo-3').hide();
					$('#photo-2').fadeIn();
				});
				$('#nav ul li:eq(2)').click(function() {
					$('li').removeClass('active');
					$(this).addClass('active');
					$('#photo-1, #photo-2').hide();
					$('#photo-3').fadeIn();
				});
			});
			$(window).load(function() {
				$('#photo-2, #photo-3').hide();
			});

		</script>
	</body>
</html>