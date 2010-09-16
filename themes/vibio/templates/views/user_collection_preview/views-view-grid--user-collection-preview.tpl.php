<?php
$total_items = collection_get_num_items($view->args[1]);
$unshown_items = $total_items - count($view->result);

if ($unshown_items)
{
	$empty_spot = false;
	$more_items = t("And !count more", array("!count" => $unshown_items))."<br />";
	$more_link = l(t("View Complete List"), "collections/{$view->args[1]}");
	$extra_td = "
		<span class='unshown_item_count'>
			$more_items
			$more_link
		</span>
	";
	foreach ($rows as $i => $cols)
	{
		foreach ($cols as $j => $item)
		{
			if (empty($item))
			{
				$empty_spot = true;
				$rows[$i][$j] = $extra_td;
				break;
			}
		}
	}

	if (!$empty_spot)
	{
		$next_row_i = count($rows);
		$next_row = array();

		for ($i = 0; $i < $view->display['default']->display_options['style_options']['columns']; ++$i)
		{
			$next_row[] = $i == 0 ? $extra_td : "";
		}

		$rows[] = $next_row;
	}
}
?>

<table class="views-view-grid user-collection-preview">
	<?php
	foreach ($rows as $i => $cols)
	{
		$row_class = "row-".($i + 1);
		if ($i == 0)
		{
			$row_class .= " row-first";
		}
		if (count($rows) == $i + 1)
		{
			$row_class .= " row-last";
		}

		echo "<tr class='$row_class'>";
		foreach ($cols as $j => $item)
		{
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