<?php
global $user;

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
	"!username"	=> $user->name, //since this will only show if the current user owns this item, we can save ourselves a user_load and do this.
	"!item"		=> $node->title,
);

$message = $node->field_posting_type[0]['value'] == VIBIO_ITEM_TYPE_OWN ?
	t("wants to share \"!item\" with you on Vibio!", $t_params) :
	t("is selling \"!item\" on Vibio and would like to share it with you!", $t_params);
$share_params = json_encode(array(
	"message"	=> $message,
	"picture"	=> $picture,
	"link"		=> url("node/{$node->nid}", array("absolute" => true,)),
	"caption"	=> $node->title,
));

echo "
	<div class='fb_share_container'>
		<span class='fb_share_params'>$share_params</span>
		<a href='#' class='fb_share'>Share on Facebook!</a>
	</div>
";
?>