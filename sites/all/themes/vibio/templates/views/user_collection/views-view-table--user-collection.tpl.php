<?php
global $user;

$extra_columns = array();
if ($user->uid == $view->args[0])
{
	module_load_include("inc", "fb");
	$share[] = "fb_share";
}
?>

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
	  if (!empty($share))
	  {
		$share_text = t("Share");
		echo "<th>$share_text</th>";
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
		if (!empty($share))
		{
			echo "<td>";
			foreach  ($share as $theme)
			{
				echo theme($theme, $view->result[$count]->nid);
			}
			echo "</td>";
		}
		?>
	  </tr>
	<?php endforeach; ?>
  </tbody>
</table>