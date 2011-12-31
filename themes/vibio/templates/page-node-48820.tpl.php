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
		<?php
		echo $header;
		?>
		<div id="record">
			<div id="watches-text">
				<h1>DO VINYLS “DO IT” FOR YOU?</h1>
				<h2>Broadcast it to the world on Vibio!</h2>
				<p>
					Vibio is a site for people who are interested in sharing, trading and expressing themselves through the things they want and have.
				</p>
				<p id="login">
					Sign up for free
				</p>
				<p id="facebook">
					<?php
					if (module_exists('fboauth')) {
						$link_attributes = fboauth_action_link_properties('connect');
						//Puts a fb button inside the link.
						$link = l('<img src="/themes/vibio/images/btn_fb_login.png" id="facebook-large" class="fb_login"/>', $link_attributes['href'], array('query' => $link_attributes['query'], 'html' => TRUE));
					}
					?>
					<?php print $link;?>
				</p>
				<p id="manual">
					OR <a href="/user/register" target="_blank" >Sign Up Manually</a>
				</p>
			</div>
		</div>
		<?php
		echo $content;
		?><div id="wrapper">
		<div id="seperator-1"></div>
		<div id="featured-1">
		<p id="text">
		Turntables and LP's featured on Vibio
		</p>
		<ul id="row-1">
		<li>
		<a href="/collections/315" target="_blank"><img src="/themes/vibio/images/landing/images/record_01.png" alt="Some Record"/></a>
		</li>
		<li>
		<a href="/collections/315" target="_blank"><img src="/themes/vibio/images/landing/images/record_02.png" alt="Some Record"/></a>
		</li>
		<li>
		<a href="/collections/315" target="_blank"><img src="/themes/vibio/images/landing/images/record_03.png" alt="Some record"/></a>
		</li>
		</ul>
		<ul id="row-2">
		<li>
		<a href="/collections/35" target="_blank"><img src="/themes/vibio/images/landing/images/record_04.png" alt="Some Record"/></a>
		</li>
		<li>
		<a href="/collections/35" target="_blank"><img src="/themes/vibio/images/landing/images/record_05.png" alt="Some Record"/></a>
		</li>
		<li>
		<a href="/collections/35" target="_blank"><img src="/themes/vibio/images/landing/images/record_06.png" alt="Some Record"/></a>
		</li>
		</ul>
		</div>
		<div id="featured-2">
		<h1>FIND ONE OF A KIND ITEMS</h1>
		<p>
		Discover unique and vintage items by checking out what collectors have or want. If you find something that would be perfect for your collection, make an offer to the seller and be ready to negotiate a price.
		</p>
		</div>
		<div id="featured-3">
		<h1>TRADE AND SELL YOUR TREASURES</h1>
		<p>
		Vibio makes it easy to list, negotiate and sell your items.
		</p>
		</div>
		<?php echo $content_bottom;?></div>
		<?php echo $footer;?>

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