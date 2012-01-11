<?php
/* Print your friends=contacts on the Contacts page */
if ($relationships) 
{
	$table = "<table class='user-relationships-listing-table'>";
	foreach ($relationships as $relationship) 
	{
		$edit_access = ($user->uid == $account->uid && user_access('maintain own relationships')) || user_access('administer users');
		$this_user_str  = $account->uid == $relationship->requestee_id ? 'requester' : 'requestee';
		$this_user      = $relationship->{$this_user_str};
		$username = theme("username", $this_user);
		$remove_link = $edit_access ? theme('user_relationships_remove_link', $account->uid, $relationship->rid) : false;
		$busy_indicator = module_exists("uri") ? "<img class='uri_edit_busy_indicator' src='/themes/vibio/images/ajax-loader.gif' />" : "";

		$row = "
			<td class='ur_friend_info'>
				$username
				<div class='relationship_extra'>
					{$relationship->extra_for_display}
				</div>
			</td>
		";

		if (variable_get("user_relationships_show_user_pictures", false))
		{
			$row = "<td style='width:60px'>".theme("user_picture", $this_user)."</td>".$row;
		}

		if ($remove_link)
		{
			$row .= "<td style='width:100px' class='ur_friend_remove'>$remove_link</td>";
		}

		$row = "<tr>$row</tr>";
		$table .= $row;
	}

	$table .= "</table>";
	$pager = theme("pager", null, $relationships_per_page);
	$edit_form = module_exists("uri") ? "<div id='uri_elaborations_edit'>".drupal_get_form("uri_edit_elaboration_form")."</div>" : "";

	echo $table.$pager.$edit_form;
}
else 
{
  echo t('No relationships found');
}
?>
