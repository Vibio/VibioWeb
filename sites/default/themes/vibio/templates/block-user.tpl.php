<div id="<?php print $block_html_id; ?>" class="<?php print $classes; ?>">
  <?php
  if ($title && $block->region != "sidebar_first")
  {
	echo "<h2 class='title'>$title</h2>";
  }
  ?>

  <div class="content">
    <?php print $content; ?>
  </div>

  <?php print $edit_links; ?>
</div>
