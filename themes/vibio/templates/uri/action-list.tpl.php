<?php
echo "
	<table>
		<tr>
			<td>
				<img class='uri_edit_busy_indicator' src='/themes/vibio/images/ajax-loader.gif' />
			</td>
";
foreach ($list as $link)
{
	echo "<td>{$link}</td>";
}
echo "</tr></table>";
?>
