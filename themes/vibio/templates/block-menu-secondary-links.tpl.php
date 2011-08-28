Currently secondary menus not used, this used to display on the footer.
<?php
print "Make it useful.";
$menu_html = "";
foreach (menu_tree_page_data("secondary-links") as $menu_item)
{
	$link = $menu_item['link'];
	$menu_html .= l($link['link_title'], $link['link_path']);
}
?>

<div id="<?php print $block_html_id; ?>" class="<?php print $classes; ?>">
  <?php if ($title): ?>
    <h2 class="title"><?php print $title; ?></h2>
  <?php endif; ?>

  <div class="content">
    <?php print $menu_html; ?>
  </div>

  <?php print $edit_links; ?>
</div>
