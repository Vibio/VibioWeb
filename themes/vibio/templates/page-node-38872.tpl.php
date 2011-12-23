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
					<h1>Do watches get your gears going?</h1>
					<h2>Broadcast your collection on Vibio!</h2>
					<p>
						Vibio is a site for people who are interested in sharing, trading and expressing themselves through the things they want and have.
					</p>
					<p id="login">
						<a href="/login" id="login-btn">Sign up for free</a>
					</p>
				</div>
			</div>
			<?php
			echo $content;
			?>
			<div id="featured-1">
				<p id="text">
					Watches Featured on
				</p>
				<ul id="row-1">
					<li>
						<img src="/themes/vibio/images/landing/watch-01.jpg" alt="Some Watch"/>
					</li>
					<li>
						<img src="/themes/vibio/images/landing/watch-01.jpg" alt="Some Watch"/>
					</li>
					<li>
						<img src="/themes/vibio/images/landing/watch-01.jpg" alt="Some Watch"/>
					</li>
				</ul>
				<ul id="row-2">
					<li>
						<img src="/themes/vibio/images/landing/watch-01.jpg" alt="Some Watch"/>
					</li>
					<li>
						<img src="/themes/vibio/images/landing/watch-01.jpg" alt="Some Watch"/>
					</li>
					<li>
						<img src="/themes/vibio/images/landing/watch-01.jpg" alt="Some Watch"/>
					</li>
				</ul>
			</div>
			<div id="featured-2">
				<h1>Find one of a kind items</h1>
				<p>
					Discover unique and vintage items by checking out what collectors have or want. If you find something that would be perfect for your collection, make an offer to the seller and be ready to negotiate a price.
				</p>
			</div>
			<div id="featured-3">
				<h1>Trade and Sell your treasures</h1>
				<p>
					Vibio makes it easy to list, negotiate and sell your items.
				</p>
			</div>
			<?php echo $content_bottom;?>
			<?php echo $footer;?>
		</div>
	</body>
</html>
