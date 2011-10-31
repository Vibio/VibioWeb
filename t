modules/node/node.pages.inc:    '#submit' => array('node_form_submit'),
modules/node/node.pages.inc:  $node = node_form_submit_build_node($form, $form_state);
modules/node/node.pages.inc:function node_form_submit($form, &$form_state) {
modules/node/node.pages.inc:  $node = node_form_submit_build_node($form, $form_state);
modules/node/node.pages.inc:function node_form_submit_build_node($form, &$form_state) {
modules/upload/upload.module:function upload_node_form_submit(&$form, &$form_state) {
modules/upload/upload.module:      $form['#submit'][] = 'upload_node_form_submit';
modules/upload/upload.module:      '#submit' => array('node_form_submit_build_node'),
modules/upload/upload.module:  upload_node_form_submit($cached_form, $form_state);
modules/vibio/vibio_item/product_catalog/product.module:					//		- node_form_submit($form, &$form_state)
modules/vibio/vibio_item/product_catalog/product.module:				//$form['#submit'] = array( _add_item_to_my_new_product_form_submit, _privacy_form_submit, menu_node_form_submit );
Binary file modules/vibio/vibio_item/product_catalog/.product.module.swp matches
modules/vibio/overrides/overrides.module: $node = node_form_submit_build_node($form, $form_state);
modules/vibio/overrides/overrides.module: $node = node_form_submit_build_node($form, $form_state);
modules/vibio/overrides/temp:>  $node = node_form_submit_build_node($form, $form_state);
modules/vibio/overrides/temp:>  $node = node_form_submit_build_node($form, $form_state);
modules/poll/poll.module:  node_form_submit_build_node($form, $form_state);
modules/poll/poll.module:function poll_node_form_submit(&$form, &$form_state) {
modules/menu/menu.module:    $form['#submit'][] = 'menu_node_form_submit';
modules/menu/menu.module:function menu_node_form_submit($form, &$form_state) {
modules/book/book.module:        '#submit' => array('node_form_submit_build_node'),
sites/default/modules/vibio_offer/vibio_offer.module:			// $form['buttons']['submit']['#submit'][0] = 'MODULENAME_node_form_submit';
sites/default/modules/friend_finder/vibio_offer.module:			// $form['buttons']['submit']['#submit'][0] = 'MODULENAME_node_form_submit';
sites/all/modules/auto_nodetitle/auto_nodetitle.module:      $form['#submit'][] = 'auto_nodetitle_node_form_submit';
sites/all/modules/auto_nodetitle/auto_nodetitle.module:      $form['#submit'][] = 'auto_nodetitle_node_form_submit';
sites/all/modules/auto_nodetitle/auto_nodetitle.module:function auto_nodetitle_node_form_submit($form, &$form_state) {
sites/all/modules/auto_nodetitle/autonodetitle_with_hard_coded_offer_php_for_testing:      $form['#submit'][] = 'auto_nodetitle_node_form_submit';
sites/all/modules/auto_nodetitle/autonodetitle_with_hard_coded_offer_php_for_testing:      $form['#submit'][] = 'auto_nodetitle_node_form_submit';
sites/all/modules/auto_nodetitle/autonodetitle_with_hard_coded_offer_php_for_testing:function auto_nodetitle_node_form_submit($form, &$form_state) {
sites/all/modules/orig_core_modules/cck/includes/content.node_form.inc:  node_form_submit_build_node($form, $form_state);
sites/all/modules/orig_core_modules/video/video.module:    case 'node_form_submit':
sites/all/modules/orig_core_modules/video/types/videoftp/videoftp_widget.inc:    '#submit' => array('node_form_submit_build_node'),
sites/all/modules/orig_core_modules/video/types/videoftp/videoftp_widget.inc:    '#submit' => array('node_form_submit_build_node'),
sites/all/modules/filefield/filefield_widget.inc:    '#submit' => array('node_form_submit_build_node'),
sites/all/modules/filefield/filefield_widget.inc:    '#submit' => array('node_form_submit_build_node'),
