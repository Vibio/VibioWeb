<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language -> language;?>" xml:fb="http://ogp.me/ns/fb#" lang="<?php print $language -> language;?>" dir="<?php print $language -> dir;?>">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title><?php print $head_title;?></title>
		<?php print $head;?>
		<meta name="description" content="Vibio is a social commerce tool that lets you buy, sell, and share items within your social graph" />
		<meta name="keywords" content="social commerce, inventory, buy, sell, share, social graph, friends, trade, collections" />
		<?php print $styles;?>
		<link rel="stylesheet" href="/themes/vibio/css/jquery-ui.tabs.css" />
		<link rel="stylesheet" href="/themes/vibio/css/vibio_dialog.css" />
		<link rel="stylesheet" href="/themes/vibio/prettyphoto/css/prettyPhoto.css" />
		<link href='http://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>
		<?php print $scripts;?>
		<script type="text/javascript" src="/themes/vibio/js/jquery-ui-1.8.2.custom.min.js"></script>
		<script type="text/javascript" src="/themes/vibio/js/vibio_dialog.js"></script>
		<script type="text/javascript" src="/themes/vibio/js/utility.js"></script>
		<script type="text/javascript" src="/themes/vibio/prettyphoto/js/jquery.prettyPhoto.js"></script>
		<script type='text/javascript' src='/themes/vibio/js/jquery.livequery.min.js'></script>
	</head>
	<body class="<?php print $classes;?>">
		<div id="fb-root"></div>
		<div id="header-bgd">
			<div id="header">
				<div id="logo">
					<a href="<?php print $front_page;?>" title="<?php print t('Home');?>" rel="home"> <img src='/themes/vibio/images/logo_ribbon.png' /> </a>
				</div>
				<?php
				echo "<div id='header_quick_actions'>$profile_ext_header</div>";
				echo "<div id='search-box'>$search_box</div>";
				echo $header;
				?>
				<div class="section clearfix"></div>
			</div>
		</div>
		<?php //checks to see if this is the splash page and displays the following. Perhaps it should be moved elsewhere?
		global $user;
                if (!$user->uid ){
                    if(strpos($_SERVER["REQUEST_URI"], 'home') != FALSE || $is_front) {
                        echo '<div id="splash-fb-bgd">
                                <div id="splash-fb"><p>Use Vibio For Free</p><p id="splash-fb-btn">';
                        if (module_exists('fboauth')) {
                            $link_attributes = fboauth_action_link_properties('connect');
                            //Puts a fb button inside the link.
                            $link = l('<img src="/themes/vibio/images/btn_splash_fb.png" id="facebook-large" class="fb_login"/>', $link_attributes['href'], array('query' => $link_attributes['query'], 'html' => TRUE));
                            $linklarge = l('<img src="/themes/vibio/images/front_big_facebook.png" id="facebook-large" class="fb_login"/>', $link_attributes['href'], array('query' => $link_attributes['query'], 'html' => TRUE));
                            echo $link;
                        }
                        echo "</p><p id='splash-signin'><span id='or'>OR</span><a href='/user/register'>Sign Up Manually</a></p></div></div>";
                        echo "<div id='splash-top'><div id='splash-text'><p id='splash-t1' class='quicksand'>CREATE COLLECTIONS<br>THAT ARE UNIQUE TO<br>YOUR INTERESTS</p><p id='splash-t2'>Vibio is a site for people who are interested in sharing, trading and expressing themselves through the things they want and have.</p><p id='splash-t3'>Sign Up for Free</p><p id='splash-t4'>$linklarge</p></div><div id='splash-video'><iframe src='http://player.vimeo.com/video/33426188?title=0&amp;byline=0&amp;portrait=0' width='585' height='329' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe><div id='splash-frame'><img src='/themes/vibio/images/splash_vid_frame.png' alt='Vibio Video'/></div></div></div><div class='section clearfix'></div>";
                        echo "<script type='text/javascript'>
                            $('#splash-frame').hover(function() {
                                $(this).fadeOut(900);
                            }); </script>";
                    }
                }
		?>
		<div id="page-wrapper">
			<div id="page">
				<div id="main-wrapper">
					<div id="main" class="clearfix<?php
					if ($primary_links || $navigation) { print ' with-navigation';
					}
					?>">
						<div id="content" class="column">
							<?php echo $messages;?>
							<div class="section rounded_container">
								<div id="content-area">
									<div class="rounded_content">
										<div id="js_messages_container"></div>
										<?php
echo $content_top;
global $skiptitle;
echo ($title && !$skiptitle) ? "<h1 id='page_title' class='title'>$title</h1>" : "";
echo $tabs ? "<div class='tabs'>$tabs</div>" : "";
echo $content;
echo $help;
echo $content_bottom;
echo $feed_icons ? "<div class='feed-icons'>$feed_icons</div>" : "";
										?>
									</div>
								</div>
							</div>
						</div>
						<!-- /.section, /#content -->
						<?php if ($primary_links || $navigation):
						?>
						<div id="navigation">
							<div class="section clearfix">
								<?php if (isset($secondary_links)):
								?>
								<div id="secondary">
									<?php //print theme('links', $secondary_links);?>
									<?php //vibio_secondary_menu_ad_hoc($secondary_links); /* in template.php */?>
								</div>
								<?php endif;?>
								<?php
								//print_r($secondary_links);
								/*print theme(array(/*'links__system_secondary_menu'x/ 'vibio_secondary_menu_theme', 'links'), $secondary_links,
								 array(
								 'id' => 'secondary-menu',
								 'class' => 'links clearfix',
								 ),
								 array(
								 'text' => t('Second level of current primary menu choice'),
								 'level' => 'h2',
								 'class' => 'element-invisible',
								 ));
								 */

								/* switch to blocks print theme(array('links__system_main_menu', 'links'), $primary_links,
								 array(
								 'id' => 'main-menu',
								 'class' => 'links clearfix',
								 ),
								 array(
								 'text' => t('Main menu'),
								 'level' => 'h2',
								 'class' => 'element-invisible',
								 ));
								 ?>
								 <?php /* stephen, quick
								 //test: $secondary_links = $primary_links;
								 print theme(array('links__system_secondary_menu', 'links'), $secondary_links,
								 array(
								 'id' => 'secondary-menu',
								 'class' => 'links clearfix',
								 ),
								 array(
								 'text' => t('Secondary menu'),
								 'level' => 'h2',
								 'class' => 'element-invisible',
								 ));
								 */
								?>

								<?php print $navigation;?>
							</div>
						</div>
						<!-- /.section, /#navigation -->
						<?php endif;?>

						<?php print $sidebar_first;?>

						<?php //not set: print $sidebar_second;?>
					</div>
				</div>
				<!-- /#main, /#main-wrapper -->
			</div>
		</div>
		<!-- /#page, /#page-wrapper -->
		<?php if ($footer || $footer_message):
		?>
		<div id="footer">
			<div class="section">
				<?php if ($footer_message):
				?>
				<div id="footer-message">
					<?php print $footer_message;?>
				</div>
				<?php endif;?>

				<?php print $footer;?>
			</div>
		</div>
		<!-- /.section, /#footer -->
		<?php endif;?>

		<?php print $page_closure;?>

		<?php print $closure;?>
	</body>
</html>
