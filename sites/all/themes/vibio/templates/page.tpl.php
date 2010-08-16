<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">

<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  
  <?php print $styles; ?>
  <link rel="stylesheet" href="/sites/all/themes/vibio/css/jquery-ui.tabs.css" />
  <link rel="stylesheet" href="/sites/all/themes/vibio/css/vibio_dialog.css" />
  
  <?php print $scripts; ?>
  <script type="text/javascript" src="/sites/all/themes/vibio/js/jquery-ui-1.8.2.custom.min.js"></script>
  <script type="text/javascript" src="/sites/all/themes/vibio/js/vibio_dialog.js"></script>
  <script type="text/javascript" src="/sites/all/themes/vibio/js/utility.js"></script>
  <script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-18021217-3']);
	_gaq.push(['_setDomainName', '.vibio.com']);
	_gaq.push(['_trackPageview']);
  
	(function() {
	  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
  </script>
</head>
<body class="<?php print $classes; ?>">
<div id="page-wrapper">
	<div id="page">
		<div id="header">
			<div class="section clearfix">
				<div id="name-and-slogan">
					<a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home">
						<img src='/sites/all/themes/vibio/vibio.png' />
					</a>
					<?php
					if ($site_slogan)
					{
						echo "<div id='site-slogan'>{$site_slogan}</div>";
					}
					?>
				</div>
				
				<?php
				if ($search_box)
				{
					echo "<div id='search-box'>{$search_box}</div>";
				}
				echo $header;
				?>
			</div>
		</div>

	<div id="main-wrapper"><div id="main" class="clearfix<?php if ($primary_links || $navigation) { print ' with-navigation'; } ?>">

	  <div id="content" class="column"><div class="section">

		<?php if ($mission): ?>
		  <div id="mission"><?php print $mission; ?></div>
		<?php endif; ?>

		<?php print $highlight; ?>

		<?php print $breadcrumb; ?>
		<?php if ($title): ?>
		  <h1 class="title"><?php print $title; ?></h1>
		<?php endif; ?>
		<?php print $messages; ?>
		<?php if ($tabs): ?>
		  <div class="tabs"><?php print $tabs; ?></div>
		<?php endif; ?>
		<?php print $help; ?>

		<?php print $content_top; ?>

		<div id="content-area">
		  <?php print $content; ?>
		</div>

		<?php print $content_bottom; ?>

		<?php if ($feed_icons): ?>
		  <div class="feed-icons"><?php print $feed_icons; ?></div>
		<?php endif; ?>

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
