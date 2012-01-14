<?php

/**
 * Implementation of hook_node_info().
 */
function features_collection_node_info() {
  $items = array(
    'collection' => array(
      'name' => t('Collection'),
      'module' => 'features',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'has_body' => '1',
      'body_label' => t('Description'),
      'min_word_count' => '0',
      'help' => '',
    ),
  );
  return $items;
}

/**
 * Implementation of hook_views_api().
 */
function features_collection_views_api() {
  return array(
    'api' => '3.0',
  );
}
