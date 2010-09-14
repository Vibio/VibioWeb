<?php
global $user;

$extra_columns = array();
if ($user->uid == $view->args[0])
{
	if (module_exists("fb"))
	{
		module_load_include("inc", "fb");
		$share[] = "fb_share";
		$fb_share = theme("fb_share", $view->args[1], "collection");
	}
	
	$collections_link_text = t("View your collections");
}
else
{
	$u = user_load($view->args[0]);
	$collections_link_text = t("View !user's collections", array("!user" => $u->name));
}

$display_args = array(
	"!start"	=> $view->pager['items_per_page']*$view->pager['current_page'] + 1,
	"!end"		=> min($view->total_rows, $view->pager['items_per_page']*($view->pager['current_page']+1)),
	"!total"	=> $view->total_rows,
);

echo $fb_share;
echo l($collections_link_text, "user/{$view->args[0]}/inventory")."<br />";
echo t("Viewing !start - !end of !total", $display_args);
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
				if ($theme == "fb_share")
				{
					$out = theme("fb_share", $view->result[$count]->nid, "node");
				}
				echo $out;
			}
			echo "</td>";
		}
		?>
	  </tr>
	<?php endforeach; ?>
  </tbody>
</table>