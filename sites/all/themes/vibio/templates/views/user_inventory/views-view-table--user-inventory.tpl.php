<?php
global $user;
echo drupal_get_form("vibio_item_user_inventory_search", $view->args[0]);

$extra_columns = array();
if ($user->uid == $view->args[0])
{
	$extra_columns = array(
		"fb_share",
	);
}
?>

<div id='user_inventory'>
	<table class="<?php print $class; ?>">
	  <?php if (!empty($title)) : ?>
		<caption><?php print $title; ?></caption>
	  <?php endif; ?>
	  <thead>
		<tr>
		  <?php foreach ($header as $field => $label): ?>
			<th class="views-field views-field-<?php print $fields[$field]; ?>">
			  <?php print $label; ?>
			</th>
		  <?php endforeach; ?>
		  <?php
		  foreach ($extra_columns as $c)
		  {
			echo "<th></th>";
		  }
		  ?>
		</tr>
	  </thead>
	  <tbody>
		<?php foreach ($rows as $count => $row): ?>
		  <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
			<?php foreach ($row as $field => $content): ?>
			  <td class="views-field views-field-<?php print $fields[$field]; ?>">
				<?php print $content; ?>
			  </td>
			<?php endforeach; ?>
			<?php
			foreach ($extra_columns as $theme)
			{
				echo "<td>".theme($theme, $view->result[$count]->nid)."</td>";
			}
			?>
		  </tr>
		<?php endforeach; ?>
	  </tbody>
	</table>
</div>