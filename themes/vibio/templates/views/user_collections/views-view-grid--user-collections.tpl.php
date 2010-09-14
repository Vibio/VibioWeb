<?php
if ($view->args[1] == PRIVACY_ONLYME)
{
	echo l(t("Create New Collection"), "collections/new")."<br />";
}

echo l(t("View All Items"), "collections/{$view->args[0]}/view-all");
?>

<table class="views-view-grid user-collections">
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
			$output = theme("collection_list_item", $view->result[$result_index], $view->args[1]);
			
			echo "
				<td class='$col_class'>
					$output
				</td>
			";
		}
		echo "</tr>";
	}
	?>
</table>