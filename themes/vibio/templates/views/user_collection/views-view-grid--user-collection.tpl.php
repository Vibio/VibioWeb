<?php
if ($view->args[2] == PRIVACY_ONLYME) {	
	$collections_link_text = t("View your collections");
} else {
	$u = user_load($view->args[0]);
	$collections_link_text = t("View !user's collections", array("!user" => $u->name));
}

$display_args = array(
	"!start"	=> $view->pager['items_per_page']*$view->pager['current_page'] + 1,
	"!end"		=> min($view->total_rows, $view->pager['items_per_page']*($view->pager['current_page'])+1),
	"!total"	=> $view->total_rows,
);
echo "<!-- sites/default/themes/vibio/templates/views/user_collection/views-view-grid--user-collection.tpl.php --> \n";
//echo l($collections_link_text, "user/{$view->args[0]}/inventory")."<br />";
echo t("Viewing !start - !end of !total", $display_args);
?>

<table class="views-view-grid user-collection-preview">
	<?php
	foreach ($rows as $i => $cols) {
		$row_class = "row-".($i + 1);
		if ($i == 0)
			$row_class .= " row-first";
		if (count($rows) == $i + 1)
			$row_class .= " row-last";

		echo "<tr class='$row_class'>";
		foreach ($cols as $j => $item) {
			$result_index = count($cols)*$i + $j;
			$col_class = "col-".($j + 1);
			$td_output = isset($view->result[$result_index]) ? theme("collection_list_item_preview", $view->result[$result_index]) : $item;

			echo "
				<td class='$col_class'>
					$td_output
				</td>
			";
		}
		echo "</tr>";
	}
	?>
</table>
