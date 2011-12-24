<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language -> language;?>" xml:fb="http://ogp.me/ns/fb#" lang="<?php print $language -> language;?>" dir="<?php print $language -> dir;?>">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title><?php print $head_title;?></title>
		<meta name="description" content="Vibio is a social commerce tool that lets you buy, sell, and share items within your social graph" />
		<meta name="keywords" content="social commerce, inventory, buy, sell, share, social graph, friends, trade, collections" />
		<link rel="stylesheet" href="/themes/vibio/css/landing.css" />
		<link href='http://fonts.googleapis.com/css?family=Quicksand:300,400' rel='stylesheet' type='text/css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	</head>
	<body class="<?php print $classes;?>">
		<div id="wrapper">
			<?php
			echo $header;
			?>
			<div id="watches">
				<div id="watches-text">
					<h1>DO WATCHES GET YOUR GEARS GOING?</h1>
					<h2>Broadcast your collection on Vibio!</h2>
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
						OR <a href="/user/register">Sign Up Manually</a>
					</p>
				</div>
			</div>
			<?php
			echo $content;
			?>
			<div id="featured-1">
				<p id="text">
					Watches Featured on Vibio
				</p>
				<ul id="row-1">
					<li>
						<a href="/collections/315" target="_blank"><img src="/themes/vibio/images/landing/images/watch_1.png" alt="Some Watch"/></a>
					</li>
					<li>
						<a href="/collections/315" target="_blank"><img src="/themes/vibio/images/landing/images/watch_2.png" alt="Michael Kors Quartz Brown Gem Dial Tortoiseshell Band - Women's Watch"/></a>
					</li>
					<li>
						<a href="/collections/315" target="_blank"><img src="/themes/vibio/images/landing/images/watch_3.png" alt="Some Watch"/></a>
					</li>
				</ul>
				<ul id="row-2">
					<li>
						<a href="/collections/35" target="_blank"><img src="/themes/vibio/images/landing/images/watch_4.png" alt="Omega Men's 2221.80.00 Seamaster 300M Quartz 'James Bond' Blue Dial Watch "/></a>
					</li>
					<li>
						<a href="/collections/35" target="_blank"><img src="/themes/vibio/images/landing/images/watch_5.png" alt="Marc Ecko E20058G1 The Brig Gents Watch"/></a>
					</li>
					<li>
						<a href="/collections/35" target="_blank"><img src="/themes/vibio/images/landing/images/watch_6.png" alt="TAG Heuer Men's CW9110.FC6177 Monaco 69 Ana-Digi Chronograph Watch"/></a>
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
			<?php echo $content_bottom;?>
			<?php echo $footer;?>
		</div>
	</body>
</html>