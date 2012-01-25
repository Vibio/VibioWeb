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
			<p id="header-left">
				YOUR THINGS TELL A STORY...
			</p>
			<p id="header-right">
				Already a member? <a href="/user/login" target="_blank">Login</a>
			</p>
		</div>
		<div id="node-content">
			<?php echo $content;?>
		</div>
		<div id="main">
			<div id="content-top-wrap">
				<div id="content-top">
					<div id="main-mid-left">
						<p id="create">
							CREATE A COLLECTION SPACE THATS UNIQUE TO YOUR INTERESTS
						</p>
						<p id="create-b">
							It’s easy to create your profile, make enviable collections and share your unique items for people to drool over.
						</p>
					</div>
					<div id="main-mid-right">
						<iframe src="http://player.vimeo.com/video/33426188?title=0&amp;byline=0&amp;portrait=0" width="558" height="314" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
						<p id="video-cover"><img src="/themes/vibio/images/landing/landing_60.png" alt="Vibio Video"/>
						</p>
					</div>
				</div>
			</div>
			<div id="content-mid">
				<div id="featured-1">
					<p id="text">
						Classic Books featured on Vibio
					</p>
					<ul id="row-1">
						<li class="s-1">
							&nbsp;
						</li>
						<li class="s-2">
							&nbsp;
						</li>
						<li class="s-3">
							&nbsp;
						</li>
					</ul>
					<ul id="row-2">
						<li class="s-4">
							&nbsp;
						</li>
						<li class="s-5">
							&nbsp;
						</li>
						<li class="s-6">
							&nbsp;
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
						Vibio makes it easy to list your proud possessions, negotiate to your liking and sell your unique items the way you want. This process is made easy by collectors for collectors.
					</p>
				</div>
			</div>
		</div>
		<div class="clears"></div>
		<div class="content-bot">
			<div id="main-bot-left">
				<p id="facebook-top">
					USE VIBIO FOR FREE
				</p>
				<p id="facebook-mid">
					<?php
					if (module_exists('fboauth')) {
						$link_attributes = fboauth_action_link_properties('connect');
						//Puts a fb button inside the link.
						$link = l('<img src="/themes/vibio/images/btn_fb_login.png" id="facebook-large" class="fb_login"/>', $link_attributes['href'], array('query' => $link_attributes['query'], 'html' => TRUE));
					}
					?>
					<?php print $link;?>
				</p>
				<p id="facebook-bot">
					OR <a href="/user/login" target="_blank">Sign Up Manually</a>
				</p>
			</div>
			<div id="main-bot-mid">
				<h2>CONNECT WITH US</h2>
				<ul>
					<li>
						<a id="fb-site" href="http://www.facebook.com/Vibioinc" target="_blank">KEEP TABS ON FACEBOOK</a>
					</li>
					<li>
						<a id="tw-site" href="http://twitter.com/vibio" target="_blank">FOLLOW US ON TWITTER</a>
					</li>
					<li>
						<a id="mu-site" href="/" target="_blank">CHECKOUT OUR BAY AREA MEETUP</a>
					</li>
				</ul>
			</div>
			<div id="main-bot-right">
				<h2>LATEST BLOG POSTS</h2>
			</div>
		</div>
		</div> <div class="clears"></div>
		<div class="footer">
			<p>
				Copyright &copy; 2012. Vibio&trade;
			</p>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#video-cover").hover(function() {
					$(this).fadeOut(900);
				});
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