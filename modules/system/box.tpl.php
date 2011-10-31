<?php

/**
 * @file box.tpl.php
 *
 * Theme implementation to display a box.
 *
 * Available variables:
 * - $title: Box title.
 * - $content: Box content.
 *
 * @see template_preprocess()
 */
?>
<?php if ($title): ?>
  <h1 id="box_page_title"><?php print $title ?></h1>
<?php endif; ?>
<div class="box">
  <div class="content"><?php print $content ?></div>
</div>
