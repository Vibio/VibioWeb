<?php
/* $title can include their email, if you're an admin [?]
   uri_actions is for friending, and ...???  */
echo "
	<tr>
		<td class='search_user_picture'>$search_user_picture</td>
		<td class='search_user_info' align='left'>
			<a href='$url'>$title</a>
		</td>
		<td>{$uri_actions['actions']}</td>
	</tr>
";
?>
