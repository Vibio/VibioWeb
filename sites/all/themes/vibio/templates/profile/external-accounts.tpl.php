<?php
if (!empty($user_accounts))
{
	$accounts = "<ul class='user_external_account_list'>";
	foreach ($user_accounts as $account)
	{
		$accounts .= "<li><span class='account_id'>$account</span>";
		if ($is_admin)
		{
			$options = array(
				"attributes"	=> array(
					"class"	=> $partner_data['remove']['class'],
				),
			);
			$link = l(t("remove"), "{$partner_data['id']}/remove-account/{$account}", $options);
			$accounts .= "($link)";
		}
		$accounts .= "</li>";
	}
	$accounts .= "</ul>";
}

if ($is_admin)
{
	$options = array(
		"attributes"	=> array(
			"class"	=> $partner_data['add']['class'],
		),
	);
	$add_account = l(t("Add an account!"), "{$partner_data['id']}/add-account", $options);
}

echo "
	<h2>{$partner_data['title']}</h2>
	$accounts
	$add_account
";
?>