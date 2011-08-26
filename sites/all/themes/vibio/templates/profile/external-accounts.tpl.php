<?php
if (!empty($user_accounts))
{
	$accounts = "<ul class='user_external_account_list'>";
	foreach ($user_accounts as $account)
	{
		$display_as = is_array($account) && $account['display'] ? $account['display'] : $account;
		$remove_id = is_array($account) && $account['id'] ? $account['id'] : $account;
		
		$accounts .= "<li><span class='account_id'>$display_as</span>";
		if ($admin_external_account)
		{
			$options = array(
				"attributes"	=> array(
					"class"	=> $partner_data['remove']['class'],
				),
			);
			$link = l(t("remove"), "{$partner_data['id']}/remove-account/{$remove_id}", $options);
			$accounts .= " $link";
		}
		$accounts .= "</li>";
	}
	$accounts .= "</ul>";
}
else
	$accounts = t("none");

$add_account = $partner_data['add']['multiple'] ? true : empty($user_accounts);

if ($admin_external_account && $add_account)
{
	$options = array(
		"attributes"	=> array(
			"class"	=> $partner_data['add']['class'],
		),
	);
	$add_account = l(t("Add an account!"), "{$partner_data['id']}/add-account", $options);
}
else
	$add_account = "";

echo "
	<tr>
		<td class='external_accounts_image'>
			<img src='/themes/vibio/images/thirdparty/{$partner_data['image']}' />
		</td>
		<td class='external_accounts_account_list'>
			$accounts
			$add_account
		</td>
	</tr>
";
?>
