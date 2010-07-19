<?php
$activity = t("Activity");
$friends = t("Friends");
$stuff = t("Vibio Items");

$activity_feed = views_embed_view("user_heartbeat_activity", "block_1", $uid);
$tabs = module_invoke_all("user_social_info", $uid);

$additional_tabs = "";
$additional_divs = "";
$script = "
	<script type='text/javascript'>
		var tab_args = {};
";
foreach ($tabs as $id => $data)
{
	if ($data['custom_ajax'])
	{
		$url = "#{$id}";
		$link_attr = "class='tablink_custom_ajax' id='tablink_{$id}'";
		$additional_divs .= "<div id='{$id}'></div>";
	}
	else
	{
		$url = $data['url'];
		$link_attr = "";
	}
	
	$additional_tabs .= "
		<li>
			<a href='{$url}' {$link_attr}>{$data['title']}</a>
		</li>
	";
	
	if ($data['args'])
	{
		$script .= "tab_args.$id = {$data['args']}";
	}
}
$script .= "</script>";

echo "
	$script
	<div id='social_tabs'>
		<ul>
			<li>
				<a href='#user_activity'>$activity</a>
			</li>
			<li>
				<a href='/vibio-ajax/get-friends/$uid'>$friends</a>
			</li>
			<li>
				<a href='/vibio-ajax/get-inventory/$uid'>$stuff</a>
			</li>
			$additional_tabs
		</ul>
		<div id='user_activity'>
			$activity_feed
		</div>
		$additional_divs
	</div>
";
?>