<?php
if (!$node->field_main_image[0]['filepath'] && module_exists("product"))
{
	module_load_include("inc", "product");
	$picture = _product_get_image($node->nid);
}
else
{
	$picture = file_create_url($node->field_main_image[0]['filepath']);
}

$t_params = array(
	"!item"		=> $node->title,
);

$href = url("node/{$node->nid}", array("absolute" => true,));
$message = $node->field_posting_type[0]['value'] == VIBIO_ITEM_TYPE_OWN ?
	t("wants to share \"!item\" with you on Vibio!", $t_params) :
	t("is selling \"!item\" on Vibio and would like to share it with you!", $t_params);
$share_params = json_encode(array(
	"message"	=> $message,
	"attachment"=> array(
		"href"			=> $href,
		"caption"		=> $node->title,
		"media"			=> array(array(
			"type"	=> "image",
			"src"	=> $picture,
			"href"	=> $href,
		)),
	),
));

echo "
	<div class='fb_share_container'>
		<span class='fb_share_params'>$share_params</span>
		<a class='fb_share'>
			<img src='/sites/all/themes/vibio/images/facebook/share.png' />
		</a>
	</div>
";
?>