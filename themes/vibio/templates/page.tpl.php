<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">

<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <meta name="description" content="Vibio is a social commerce tool that lets you buy, sell, and share items within your social graph" />
  <meta name="keywords" content="social commerce, inventory, buy, sell, share, social graph, friends, trade, collections" />

  <?php print $styles; ?>
  <link rel="stylesheet" href="/themes/vibio/css/jquery-ui.tabs.css" />
  <link rel="stylesheet" href="/themes/vibio/css/vibio_dialog.css" />
  <link rel="stylesheet" href="/themes/vibio/prettyphoto/css/prettyPhoto.css" />
  
  <!--[if IE]>
  <link rel="stylesheet" href="/themes/vibio/css/ie.css" />
  <![endif]-->
  
  <?php print $scripts; ?>
  <script type="text/javascript" src="/themes/vibio/js/jquery-ui-1.8.2.custom.min.js"></script>
  <script type="text/javascript" src="/themes/vibio/js/vibio_dialog.js"></script>
  <script type="text/javascript" src="/themes/vibio/js/utility.js"></script>
  <script type="text/javascript" src="/themes/vibio/prettyphoto/js/jquery.prettyPhoto.js"></script>
</head>
<body class="<?php print $classes; ?>">
<div id="page-wrapper">
	<div id="page">
		<div id="header">
			<div class="section clearfix">
				<div id="name-and-slogan">
					<a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home">
						<img src='/themes/vibio/vibio-logo.png' />
					</a>
				</div>
				
				<?php
				echo "<div id='header_quick_actions'>";
				if ($search_box)
				{
					echo "<div id='search-box'>{$search_box}</div>";
				}
				echo $profile_ext_header;
				echo "</div>";
				
				echo $header;
				?>
			</div>
		</div>

	<div id="main-wrapper"><div id="main" class="clearfix<?php if ($primary_links || $navigation) { print ' with-navigation'; } ?>">

	  <div id="content" class="column reflection"><div class="section rounded_container">
		<div id="content-area">
		  <div class="rounded_content">
			<div id="js_messages_container"></div>
			<?php
			  echo $messages;
			  echo $title ? "<h1 id='page_title' class='title'>$title</h1>" : "";
			  echo $tabs ? "<div class='tabs'>$tabs</div>" : "";
			  echo $help;
			  echo $content_top;
			  echo $content;
			  echo $content_bottom;
			  echo $feed_icons ? "<div class='feed-icons'>$feed_icons</div>" : "";
			?>
		  </div>
		</div>
	  </div></div> <!-- /.section, /#content -->

	  <?php if ($primary_links || $navigation): ?>
		<div id="navigation"><div class="section clearfix">

		  <?php print theme(array('links__system_main_menu', 'links'), $primary_links,
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

		  <?php print $navigation; ?>

		</div></div> <!-- /.section, /#navigation -->
	  <?php endif; ?>

	  <?php print $sidebar_first; ?>

	  <?php print $sidebar_second; ?>

	</div></div> <!-- /#main, /#main-wrapper -->

	<?php if ($footer || $footer_message || $secondary_links): ?>
	  <div id="footer"><div class="section">

		<?php print theme(array('links__system_secondary_menu', 'links'), $secondary_links,
		  array(
			'id' => 'secondary-menu',
			'class' => 'links clearfix',
		  ),
		  array(
			'text' => t('Secondary menu'),
			'level' => 'h2',
			'class' => 'element-invisible',
		  ));
		?>

		<?php if ($footer_message): ?>
		  <div id="footer-message"><?php print $footer_message; ?></div>
		<?php endif; ?>

		<?php print $footer; ?>

	  </div></div> <!-- /.section, /#footer -->
	<?php endif; ?>

  </div></div> <!-- /#page, /#page-wrapper -->

  <?php print $page_closure; ?>

  <?php print $closure; ?>

</body>
</html>