<?php

/**
 * Implementation of hook_views_default_views().
 */
function features_collection_views_default_views() {
  $views = array();

  // Exported view: Wantlist
  $view = new view;
  $view->name = 'Wantlist';
  $view->description = 'A User\'s want list';
  $view->tag = 'vibio';
  $view->base_table = 'node';
  $view->human_name = 'A Users want list';
  $view->core = 6;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Defaults */
  $handler = $view->new_display('default', 'Defaults', 'default');
  $handler->display->display_options['title'] = 'Want List';
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '16';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = 0;
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['style_options']['row_class'] = 'views-fluidgrid-item';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Header: Global: Text area */
  $handler->display->display_options['header']['area']['id'] = 'area';
  $handler->display->display_options['header']['area']['table'] = 'views';
  $handler->display->display_options['header']['area']['field'] = 'area';
  $handler->display->display_options['header']['area']['empty'] = FALSE;
  $handler->display->display_options['header']['area']['content'] = '<a name="top" id="top"></a>';
  $handler->display->display_options['header']['area']['format'] = '2';
  $handler->display->display_options['header']['area']['tokenize'] = 0;
  /* Field: Content: Main Image (field_main_image) */
  $handler->display->display_options['fields']['field_main_image_fid']['id'] = 'field_main_image_fid';
  $handler->display->display_options['fields']['field_main_image_fid']['table'] = 'node_data_field_main_image';
  $handler->display->display_options['fields']['field_main_image_fid']['field'] = 'field_main_image_fid';
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['make_link'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['external'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['target'] = '_blank';
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['element_label_type'] = '0';
  $handler->display->display_options['fields']['field_main_image_fid']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['link_to_node'] = 1;
  $handler->display->display_options['fields']['field_main_image_fid']['label_type'] = 'none';
  $handler->display->display_options['fields']['field_main_image_fid']['format'] = 'product_fixed_fluid_grid_default';
  /* Field: Want/Have */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['ui_name'] = 'Want/Have';
  $handler->display->display_options['fields']['nid']['label'] = '';
  $handler->display->display_options['fields']['nid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['external'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['nid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['nid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['nid']['element_label_type'] = '0';
  $handler->display->display_options['fields']['nid']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['nid']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['nid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['nid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['nid']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['nid']['link_to_node'] = 0;
  /* Field: Node: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['title']['alter']['make_link'] = 1;
  $handler->display->display_options['fields']['title']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['title']['alter']['external'] = 0;
  $handler->display->display_options['fields']['title']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['title']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['title']['alter']['target'] = '_blank';
  $handler->display->display_options['fields']['title']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['title']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['title']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['title']['alter']['html'] = 0;
  $handler->display->display_options['fields']['title']['element_type'] = '0';
  $handler->display->display_options['fields']['title']['element_label_type'] = '0';
  $handler->display->display_options['fields']['title']['element_label_colon'] = 0;
  $handler->display->display_options['fields']['title']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['title']['hide_empty'] = 0;
  $handler->display->display_options['fields']['title']['empty_zero'] = 0;
  $handler->display->display_options['fields']['title']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['title']['link_to_node'] = 1;
  /* Sort criterion: Node: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Argument: Node: Author UID */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'node';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['default_action'] = 'default';
  $handler->display->display_options['arguments']['uid']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'user';
  $handler->display->display_options['arguments']['uid']['default_argument_options']['user'] = TRUE;
  $handler->display->display_options['arguments']['uid']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['uid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['uid']['not'] = 0;
  /* Argument: Privacy Settings: Currently requested setting (url arg) >= setting */
  $handler->display->display_options['arguments']['setting_requested']['id'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['table'] = 'privacy_node';
  $handler->display->display_options['arguments']['setting_requested']['field'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['not'] = 0;
  /* Filter: Node: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter: Node: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'vibio_item' => 'vibio_item',
  );
  /* Filter: Content: Possession (field_have_want_like) */
  $handler->display->display_options['filters']['field_have_want_like_value']['id'] = 'field_have_want_like_value';
  $handler->display->display_options['filters']['field_have_want_like_value']['table'] = 'node_data_field_have_want_like';
  $handler->display->display_options['filters']['field_have_want_like_value']['field'] = 'field_have_want_like_value';
  $handler->display->display_options['filters']['field_have_want_like_value']['value']['value'] = '20';
  $handler->display->display_options['filters']['field_have_want_like_value']['allow_null'] = 1;
  $translatables['Wantlist'] = array(
    t('Defaults'),
    t('Want List'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('Text area'),
    t('<a name="top" id="top"></a>'),
    t('_blank'),
    t('All'),
  );

  $views[$view->name] = $view;

  // Exported view: user_collection1
  $view = new view;
  $view->name = 'user_collection1';
  $view->description = 'A single collection that a user has.';
  $view->tag = '';
  $view->base_table = 'node';
  $view->human_name = '';
  $view->core = 0;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Defaults */
  $handler = $view->new_display('default', 'Defaults', 'default');
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'argument';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['total_pages'] = '5';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_label'] = 'Display';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options'] = '10, 20, 40, 60';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = 0;
  $handler->display->display_options['style_plugin'] = 'grid';
  $handler->display->display_options['style_options']['columns'] = '2';
  $handler->display->display_options['style_options']['fill_single_line'] = 1;
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Relationship: Content: Collection (field_collection) */
  $handler->display->display_options['relationships']['field_collection_nid']['id'] = 'field_collection_nid';
  $handler->display->display_options['relationships']['field_collection_nid']['table'] = 'node_data_field_collection';
  $handler->display->display_options['relationships']['field_collection_nid']['field'] = 'field_collection_nid';
  $handler->display->display_options['relationships']['field_collection_nid']['required'] = 1;
  $handler->display->display_options['relationships']['field_collection_nid']['delta'] = '-1';
  /* Field: Node: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['title']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['title']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['title']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['title']['alter']['html'] = 0;
  $handler->display->display_options['fields']['title']['hide_empty'] = 0;
  $handler->display->display_options['fields']['title']['empty_zero'] = 0;
  $handler->display->display_options['fields']['title']['link_to_node'] = 1;
  /* Field: Offer2Buy: Price */
  $handler->display->display_options['fields']['price']['id'] = 'price';
  $handler->display->display_options['fields']['price']['table'] = 'offer2buy';
  $handler->display->display_options['fields']['price']['field'] = 'price';
  $handler->display->display_options['fields']['price']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['price']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['price']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['price']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['price']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['price']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['price']['alter']['html'] = 0;
  $handler->display->display_options['fields']['price']['empty'] = '-';
  $handler->display->display_options['fields']['price']['hide_empty'] = 1;
  $handler->display->display_options['fields']['price']['empty_zero'] = 1;
  $handler->display->display_options['fields']['price']['prefix'] = '$';
  /* Field: Node: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['label'] = 'Last Updated';
  $handler->display->display_options['fields']['changed']['exclude'] = TRUE;
  $handler->display->display_options['fields']['changed']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['changed']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['changed']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['changed']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['changed']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['changed']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['changed']['alter']['html'] = 0;
  $handler->display->display_options['fields']['changed']['hide_empty'] = 0;
  $handler->display->display_options['fields']['changed']['empty_zero'] = 0;
  $handler->display->display_options['fields']['changed']['date_format'] = 'custom';
  $handler->display->display_options['fields']['changed']['custom_date_format'] = 'M j, Y';
  /* Field: Node: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['nid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['nid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['nid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['nid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['nid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['nid']['link_to_node'] = 0;
  /* Field: Product Items: Product Data */
  $handler->display->display_options['fields']['product_data']['id'] = 'product_data';
  $handler->display->display_options['fields']['product_data']['table'] = 'product_items';
  $handler->display->display_options['fields']['product_data']['field'] = 'product_data';
  $handler->display->display_options['fields']['product_data']['label'] = 'Category';
  $handler->display->display_options['fields']['product_data']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['product_data']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['product_data']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['product_data']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['product_data']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['product_data']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['product_data']['alter']['html'] = 0;
  $handler->display->display_options['fields']['product_data']['hide_empty'] = 0;
  $handler->display->display_options['fields']['product_data']['empty_zero'] = 0;
  /* Field: User: Uid */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'users';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  $handler->display->display_options['fields']['uid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['uid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['uid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['uid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['uid']['link_to_user'] = 0;
  /* Field: Content: For Sale? (field_posting_type) */
  $handler->display->display_options['fields']['field_posting_type_value']['id'] = 'field_posting_type_value';
  $handler->display->display_options['fields']['field_posting_type_value']['table'] = 'node_data_field_posting_type';
  $handler->display->display_options['fields']['field_posting_type_value']['field'] = 'field_posting_type_value';
  $handler->display->display_options['fields']['field_posting_type_value']['label'] = 'For Sale?';
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['external'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['html'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['field_posting_type_value']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['field_posting_type_value']['hide_empty'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['empty_zero'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['link_to_node'] = 0;
  /* Argument: Node: Author UID */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'node';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['uid']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['uid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['uid']['not'] = 0;
  /* Argument: Node: Nid */
  $handler->display->display_options['arguments']['nid']['id'] = 'nid';
  $handler->display->display_options['arguments']['nid']['table'] = 'node';
  $handler->display->display_options['arguments']['nid']['field'] = 'nid';
  $handler->display->display_options['arguments']['nid']['relationship'] = 'field_collection_nid';
  $handler->display->display_options['arguments']['nid']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['nid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['nid']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['nid']['validate_type'] = 'node';
  $handler->display->display_options['arguments']['nid']['validate_options']['types'] = array(
    'collection' => 'collection',
  );
  $handler->display->display_options['arguments']['nid']['validate_options']['access'] = 0;
  $handler->display->display_options['arguments']['nid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['nid']['not'] = 0;
  /* Argument: Privacy Settings: Currently requested setting (url arg) >= setting */
  $handler->display->display_options['arguments']['setting_requested']['id'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['table'] = 'privacy_node';
  $handler->display->display_options['arguments']['setting_requested']['field'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['not'] = 0;
  /* Filter: Broken/missing handler */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'privacy_settings';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = 'node';
  $handler->display->display_options['filters']['type']['expose']['operator'] = FALSE;
  /* Filter: Node: Type */
  $handler->display->display_options['filters']['type_1']['id'] = 'type_1';
  $handler->display->display_options['filters']['type_1']['table'] = 'node';
  $handler->display->display_options['filters']['type_1']['field'] = 'type';
  $handler->display->display_options['filters']['type_1']['value'] = array(
    'vibio_item' => 'vibio_item',
  );
  $handler->display->display_options['filters']['type_1']['expose']['operator'] = 'type_1_op';
  $handler->display->display_options['filters']['type_1']['expose']['label'] = 'Node: Type';
  $handler->display->display_options['filters']['type_1']['expose']['use_operator'] = FALSE;
  $handler->display->display_options['filters']['type_1']['expose']['identifier'] = 'type_1';
  $handler->display->display_options['filters']['type_1']['expose']['optional'] = TRUE;
  $handler->display->display_options['filters']['type_1']['expose']['remember'] = FALSE;
  $handler->display->display_options['filters']['type_1']['expose']['single'] = TRUE;
  /* Filter: Node: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = '1';
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  $translatables['user_collection1'] = array(
    t('Defaults'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Display'),
    t('- All -'),
    t('Offset'),
    t('Collection'),
    t('Title'),
    t('Price'),
    t('-'),
    t('.'),
    t(','),
    t('$'),
    t('Last Updated'),
    t('Nid'),
    t('Category'),
    t('Uid'),
    t('For Sale?'),
    t('All'),
    t('Node: Type'),
  );

  $views[$view->name] = $view;

  // Exported view: user_collection_preview1
  $view = new view;
  $view->name = 'user_collection_preview1';
  $view->description = 'preview for a collection';
  $view->tag = '';
  $view->base_table = 'node';
  $view->human_name = '';
  $view->core = 0;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Defaults */
  $handler = $view->new_display('default', 'Defaults', 'default');
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '11';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'grid';
  $handler->display->display_options['style_options']['columns'] = '2';
  $handler->display->display_options['style_options']['fill_single_line'] = 1;
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Relationship: Content: Collection (field_collection) */
  $handler->display->display_options['relationships']['field_collection_nid']['id'] = 'field_collection_nid';
  $handler->display->display_options['relationships']['field_collection_nid']['table'] = 'node_data_field_collection';
  $handler->display->display_options['relationships']['field_collection_nid']['field'] = 'field_collection_nid';
  $handler->display->display_options['relationships']['field_collection_nid']['required'] = 0;
  $handler->display->display_options['relationships']['field_collection_nid']['delta'] = '-1';
  /* Field: Node: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['title']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['title']['alter']['max_length'] = '20';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = 0;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['title']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['title']['alter']['trim'] = 1;
  $handler->display->display_options['fields']['title']['alter']['html'] = 0;
  $handler->display->display_options['fields']['title']['hide_empty'] = 0;
  $handler->display->display_options['fields']['title']['empty_zero'] = 0;
  $handler->display->display_options['fields']['title']['link_to_node'] = 1;
  /* Field: Content: For Sale? (field_posting_type) */
  $handler->display->display_options['fields']['field_posting_type_value']['id'] = 'field_posting_type_value';
  $handler->display->display_options['fields']['field_posting_type_value']['table'] = 'node_data_field_posting_type';
  $handler->display->display_options['fields']['field_posting_type_value']['field'] = 'field_posting_type_value';
  $handler->display->display_options['fields']['field_posting_type_value']['label'] = 'Status';
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['alter']['html'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['hide_empty'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['empty_zero'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['link_to_node'] = 0;
  $handler->display->display_options['fields']['field_posting_type_value']['label_type'] = 'custom';
  /* Field: Offer2Buy: Price */
  $handler->display->display_options['fields']['price']['id'] = 'price';
  $handler->display->display_options['fields']['price']['table'] = 'offer2buy';
  $handler->display->display_options['fields']['price']['field'] = 'price';
  $handler->display->display_options['fields']['price']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['price']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['price']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['price']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['price']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['price']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['price']['alter']['html'] = 0;
  $handler->display->display_options['fields']['price']['empty'] = '-';
  $handler->display->display_options['fields']['price']['hide_empty'] = 1;
  $handler->display->display_options['fields']['price']['empty_zero'] = 1;
  $handler->display->display_options['fields']['price']['prefix'] = '$';
  /* Field: Node: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['label'] = 'Last Updated';
  $handler->display->display_options['fields']['changed']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['changed']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['changed']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['changed']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['changed']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['changed']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['changed']['alter']['html'] = 0;
  $handler->display->display_options['fields']['changed']['hide_empty'] = 0;
  $handler->display->display_options['fields']['changed']['empty_zero'] = 0;
  $handler->display->display_options['fields']['changed']['date_format'] = 'custom';
  $handler->display->display_options['fields']['changed']['custom_date_format'] = 'M j, Y';
  /* Field: Node: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['nid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['nid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['nid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['nid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['nid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['nid']['link_to_node'] = 0;
  /* Field: Product Items: Product Data */
  $handler->display->display_options['fields']['product_data']['id'] = 'product_data';
  $handler->display->display_options['fields']['product_data']['table'] = 'product_items';
  $handler->display->display_options['fields']['product_data']['field'] = 'product_data';
  $handler->display->display_options['fields']['product_data']['label'] = 'Category';
  $handler->display->display_options['fields']['product_data']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['product_data']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['product_data']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['product_data']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['product_data']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['product_data']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['product_data']['alter']['html'] = 0;
  $handler->display->display_options['fields']['product_data']['hide_empty'] = 0;
  $handler->display->display_options['fields']['product_data']['empty_zero'] = 0;
  /* Field: User: Uid */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'users';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  $handler->display->display_options['fields']['uid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['uid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['uid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['uid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['uid']['link_to_user'] = 0;
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['name']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['name']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['name']['alter']['external'] = 0;
  $handler->display->display_options['fields']['name']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['name']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['name']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['name']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['name']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['name']['alter']['html'] = 0;
  $handler->display->display_options['fields']['name']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['name']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['name']['hide_empty'] = 0;
  $handler->display->display_options['fields']['name']['empty_zero'] = 0;
  $handler->display->display_options['fields']['name']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['name']['link_to_user'] = 1;
  $handler->display->display_options['fields']['name']['overwrite_anonymous'] = 0;
  /* Sort criterion: Node: Updated date */
  $handler->display->display_options['sorts']['changed']['id'] = 'changed';
  $handler->display->display_options['sorts']['changed']['table'] = 'node';
  $handler->display->display_options['sorts']['changed']['field'] = 'changed';
  $handler->display->display_options['sorts']['changed']['order'] = 'DESC';
  /* Argument: Node: Author UID */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'node';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['uid']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['uid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['uid']['not'] = 0;
  /* Argument: Node: Nid */
  $handler->display->display_options['arguments']['nid']['id'] = 'nid';
  $handler->display->display_options['arguments']['nid']['table'] = 'node';
  $handler->display->display_options['arguments']['nid']['field'] = 'nid';
  $handler->display->display_options['arguments']['nid']['relationship'] = 'field_collection_nid';
  $handler->display->display_options['arguments']['nid']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['nid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['nid']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['nid']['validate_type'] = 'node';
  $handler->display->display_options['arguments']['nid']['validate_options']['types'] = array(
    'collection' => 'collection',
  );
  $handler->display->display_options['arguments']['nid']['validate_options']['access'] = 0;
  $handler->display->display_options['arguments']['nid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['nid']['not'] = 0;
  /* Argument: Privacy Settings: Currently requested setting (url arg) >= setting */
  $handler->display->display_options['arguments']['setting_requested']['id'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['table'] = 'privacy_node';
  $handler->display->display_options['arguments']['setting_requested']['field'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['not'] = 0;
  /* Filter: Broken/missing handler */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'privacy_settings';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = 'node';
  $handler->display->display_options['filters']['type']['expose']['operator'] = FALSE;
  /* Filter: Node: Type */
  $handler->display->display_options['filters']['type_1']['id'] = 'type_1';
  $handler->display->display_options['filters']['type_1']['table'] = 'node';
  $handler->display->display_options['filters']['type_1']['field'] = 'type';
  $handler->display->display_options['filters']['type_1']['value'] = array(
    'vibio_item' => 'vibio_item',
  );
  $handler->display->display_options['filters']['type_1']['expose']['operator'] = 'type_1_op';
  $handler->display->display_options['filters']['type_1']['expose']['label'] = 'Node: Type';
  $handler->display->display_options['filters']['type_1']['expose']['use_operator'] = FALSE;
  $handler->display->display_options['filters']['type_1']['expose']['identifier'] = 'type_1';
  $handler->display->display_options['filters']['type_1']['expose']['optional'] = TRUE;
  $handler->display->display_options['filters']['type_1']['expose']['remember'] = FALSE;
  $handler->display->display_options['filters']['type_1']['expose']['single'] = TRUE;
  /* Filter: Node: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = '1';
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  $translatables['user_collection_preview1'] = array(
    t('Defaults'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Collection'),
    t('Title'),
    t('Status'),
    t('Price'),
    t('-'),
    t('.'),
    t(','),
    t('$'),
    t('Last Updated'),
    t('Nid'),
    t('Category'),
    t('Uid'),
    t('Name'),
    t('All'),
    t('Node: Type'),
  );

  $views[$view->name] = $view;

  // Exported view: user_collections1
  $view = new view;
  $view->name = 'user_collections1';
  $view->description = '';
  $view->tag = '';
  $view->base_table = 'node';
  $view->human_name = '';
  $view->core = 6;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Defaults */
  $handler = $view->new_display('default', 'Defaults', 'default');
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['hide_empty'] = 0;
  $handler->display->display_options['row_options']['default_field_elements'] = 1;
  /* Empty text: Global: Text area */
  $handler->display->display_options['empty']['area']['id'] = 'area';
  $handler->display->display_options['empty']['area']['table'] = 'views';
  $handler->display->display_options['empty']['area']['field'] = 'area';
  $handler->display->display_options['empty']['area']['empty'] = FALSE;
  $handler->display->display_options['empty']['area']['content'] = '<?php
global $user;
if (arg(0) == \'user\' && is_numeric(arg(1)) && arg(1) == $user->uid && arg(2) == \'collections\') {
    return "<div id=\'no-collections\'><a class=\'add-collect\' href=\'/collections/new\'>Create new collection</a><img src=\'/themes/vibio/images/no_collections_you.png\' alt=\'You have no collections\'></div>";
} else {
    return "<div id=\'no-collections\'><img src=\'/themes/vibio/images/no_collections_other.png\' alt=\'This user has no collections\'></div>";
}
?>';
  $handler->display->display_options['empty']['area']['format'] = '3';
  $handler->display->display_options['empty']['area']['tokenize'] = 0;
  /* Field: Node: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'node_revisions';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['body']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['body']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['body']['alter']['external'] = 0;
  $handler->display->display_options['fields']['body']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['body']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['body']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['body']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['body']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['body']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['body']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['body']['alter']['html'] = 0;
  $handler->display->display_options['fields']['body']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['body']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['body']['hide_empty'] = 0;
  $handler->display->display_options['fields']['body']['empty_zero'] = 0;
  $handler->display->display_options['fields']['body']['hide_alter_empty'] = 0;
  /* Field: Node: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['external'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['nid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['nid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['nid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['nid']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['nid']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['nid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['nid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['nid']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['nid']['link_to_node'] = 0;
  /* Field: Node: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['title']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['title']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['title']['alter']['external'] = 0;
  $handler->display->display_options['fields']['title']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['title']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['title']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['title']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['title']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['title']['alter']['html'] = 0;
  $handler->display->display_options['fields']['title']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['title']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['title']['hide_empty'] = 0;
  $handler->display->display_options['fields']['title']['empty_zero'] = 0;
  $handler->display->display_options['fields']['title']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['title']['link_to_node'] = 1;
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['name']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['name']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['name']['alter']['external'] = 0;
  $handler->display->display_options['fields']['name']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['name']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['name']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['name']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['name']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['name']['alter']['html'] = 0;
  $handler->display->display_options['fields']['name']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['name']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['name']['hide_empty'] = 0;
  $handler->display->display_options['fields']['name']['empty_zero'] = 0;
  $handler->display->display_options['fields']['name']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['name']['link_to_user'] = 1;
  $handler->display->display_options['fields']['name']['overwrite_anonymous'] = 0;
  /* Field: User: Uid */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'users';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  $handler->display->display_options['fields']['uid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['external'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['uid']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['uid']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['uid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['uid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['uid']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['uid']['link_to_user'] = 1;
  /* Sort criterion: Node: Title */
  $handler->display->display_options['sorts']['title']['id'] = 'title';
  $handler->display->display_options['sorts']['title']['table'] = 'node';
  $handler->display->display_options['sorts']['title']['field'] = 'title';
  /* Argument: Node: Author UID */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'node';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['uid']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['uid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['uid']['not'] = 0;
  /* Argument: Privacy Settings: Currently requested setting (url arg) >= setting */
  $handler->display->display_options['arguments']['setting_requested']['id'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['table'] = 'privacy_node';
  $handler->display->display_options['arguments']['setting_requested']['field'] = 'setting_requested';
  $handler->display->display_options['arguments']['setting_requested']['style_plugin'] = 'default_summary';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['setting_requested']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['setting_requested']['not'] = 0;
  /* Filter: Node: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'collection' => 'collection',
  );
  $translatables['user_collections1'] = array(
    t('Defaults'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('Text area'),
    t('<?php
global $user;
if (arg(0) == \'user\' && is_numeric(arg(1)) && arg(1) == $user->uid && arg(2) == \'collections\') {
    return "<div id=\'no-collections\'><a class=\'add-collect\' href=\'/collections/new\'>Create new collection</a><img src=\'/themes/vibio/images/no_collections_you.png\' alt=\'You have no collections\'></div>";
} else {
    return "<div id=\'no-collections\'><img src=\'/themes/vibio/images/no_collections_other.png\' alt=\'This user has no collections\'></div>";
}
?>'),
    t('Body'),
    t('Nid'),
    t('Title'),
    t('Name'),
    t('Uid'),
    t('All'),
  );

  $views[$view->name] = $view;

  return $views;
}
