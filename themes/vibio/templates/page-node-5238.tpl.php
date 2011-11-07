<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language -> language;?>" xml:fb="http://ogp.me/ns/fb#" lang="<?php print $language -> language;?>" dir="<?php print $language -> dir;?>">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<title><?php print $head_title;?></title>
		<meta name="description" content="404 Page not found - Vibio is a social commerce tool that lets you buy, sell, and share items within your social graph" />
		<meta name="keywords" content="social commerce, inventory, buy, sell, share, social graph, friends, trade, collections" />
		<style type="text/css">
			html, body {
				height: 100%;
				width: 100%;
				padding: 0;
				margin: 0;
			}
			#full-screen-background-image {
				z-index: -999;
				min-height: 100%;
				min-width: 1024px;
				width: 100%;
				height: auto;
				position: fixed;
				top: 0;
				left: 0;
			}
			#wrapper {
				position: relative;
				width: 1200px;
				min-height: 190px;
				margin: 0 auto;
			}
			a {
				border: none;
				text-decoration:none
			}
			img{
				border:none
			}
			p{
				margin:0
			}
		</style>
	</head>
	<body>
		<img alt="full screen background image" src="/themes/vibio/images/404_page.jpg" id="full-screen-background-image" />
		<div id="wrapper">
			<p>
				<a href="/"><img src="/themes/vibio/images/take_me_home.png"/></a>
			</p>
		</div>
	</body>
</html>
