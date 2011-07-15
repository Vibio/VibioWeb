modules/heartbeat/modules/heartbeat_comments/heartbeat_comments.module:  $avatar = theme('user_picture', $comment);
modules/user_relationships/user_relationships_ui/templates/user_relationships.tpl.php:		if (variable_get("user_relationships_show_user_pictures", false))
modules/user_relationships/user_relationships_ui/templates/user_relationships.tpl.php:			$row = "<td>".theme("user_picture", $this_user)."</td>".$row;
modules/vibiomodules/vibio_item/product_catalog/product.inc:				"picture"	=> theme("user_picture", (object) $row),
modules/vibio/vibio_item/product_catalog/product.inc:				"picture"	=> theme("user_picture", (object) $row),
modules/views/modules/user/views_handler_field_user_picture.inc:// $Id: views_handler_field_user_picture.inc,v 1.1.4.3 2010/01/06 20:55:13 merlinofchaos Exp $
modules/views/modules/user/views_handler_field_user_picture.inc:class views_handler_field_user_picture extends views_handler_field {
modules/views/modules/user/views_handler_field_user_picture.inc:    return theme('user_picture', $account);
themes/vibio/templates/search/search-result-user.tpl.php:		<td class='search_user_picture'>$search_user_picture</td>
themes/vibio/templates/uri/dashboard-notification.tpl.php:$requester_image = theme("user_picture", $relationship->requester);
themes/vibio/templates/user/user-picture.tpl.php: * @see template_preprocess_user_picture()
themes/vibio/templates/user/user-profile.tpl.php:	$profile['user_picture'] = "
themes/vibio/templates/user/user-profile.tpl.php:		<?php echo $profile['user_picture']; ?>
