<?php
echo "
	<tr>
		<td align='center'>
			<a href='$url'>
				<img class='search_result_image' src='{$info_split['image']}' />
			</a>
		</td>
		<td valign='middle' style='padding-left: 20px;'>
			<a href='$url'>$title</a><br />
			$search_links
		</td>
	</tr>
";
?>