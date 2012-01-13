<?php

/**
 * Implementation of hook_views_default_views().
 */
function vibio_product_displays_views_default_views() {
  $views = array();

  // Exported view: all_products_for_sale
  $view = new view;
  $view->name = 'all_products_for_sale';
  $view->description = 'All Products for Sale';
  $view->tag = 'vibio';
  $view->base_table = 'node';
  $view->human_name = 'All Products for Sale';
  $view->core = 6;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Defaults */
  $handler = $view->new_display('default', 'Defaults', 'default');
  $handler->display->display_options['title'] = 'All Products for Sale';
  $handler->display->display_options['access']['type'] = 'role';
  $handler->display->display_options['access']['role'] = array(
    2 => '2',
  );
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
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['external'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['alter']['trim_whitespace'] = 0;
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
  $handler->display->display_options['fields']['field_main_image_fid']['link_to_node'] = 0;
  $handler->display->display_options['fields']['field_main_image_fid']['label_type'] = 'none';
  $handler->display->display_options['fields']['field_main_image_fid']['format'] = 'product_fixed_fluid_grid_linked';
  /* Field: Node ID */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['ui_name'] = 'Node ID';
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
  $handler->display->display_options['fields']['title']['element_type'] = '0';
  $handler->display->display_options['fields']['title']['element_label_type'] = '0';
  $handler->display->display_options['fields']['title']['element_label_colon'] = 0;
  $handler->display->display_options['fields']['title']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['title']['hide_empty'] = 0;
  $handler->display->display_options['fields']['title']['empty_zero'] = 0;
  $handler->display->display_options['fields']['title']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['title']['link_to_node'] = 1;
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
  /* Filter: Content: For Sale? (field_posting_type) - Allowed values */
  $handler->display->display_options['filters']['field_posting_type_value_many_to_one']['id'] = 'field_posting_type_value_many_to_one';
  $handler->display->display_options['filters']['field_posting_type_value_many_to_one']['table'] = 'node_data_field_posting_type';
  $handler->display->display_options['filters']['field_posting_type_value_many_to_one']['field'] = 'field_posting_type_value_many_to_one';
  $handler->display->display_options['filters']['field_posting_type_value_many_to_one']['value'] = array(
    2 => '2',
  );
  $handler->display->display_options['filters']['field_posting_type_value_many_to_one']['reduce_duplicates'] = 0;

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '16';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = 0;
  $handler->display->display_options['path'] = 'all_products_for_sale';
  $handler->display->display_options['menu']['title'] = 'All Products for Sale';
  $handler->display->display_options['menu']['weight'] = '0';
  $translatables['all_products_for_sale'] = array(
    t('Defaults'),
    t('All Products for Sale'),
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
    t('Page'),
  );

  $views[$view->name] = $view;

  return $views;
}