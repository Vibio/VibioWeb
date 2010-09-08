<?php
echo "<table>";
foreach ($list as $link)
{
	echo "
		<tr>
			<td>{$link}</td>
			<td>
				<img class='uri_edit_busy_indicator' src='/sites/all/themes/vibio/images/ajax-loader.gif' />
			</td>
		</tr>";
}
echo "</table>";
?>