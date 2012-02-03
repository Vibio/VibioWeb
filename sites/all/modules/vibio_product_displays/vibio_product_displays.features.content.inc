<?php

/**
 * Implementation of hook_content_default_fields().
 */
function vibio_product_displays_content_default_fields() {
  $fields = array();

  // Exported field: field_item_count
  $fields['product-field_item_count'] = array(
    'field_name' => 'field_item_count',
    'type_name' => 'product',
    'display_settings' => array(
      'weight' => '7',
      'parent' => '',
      'label' => array(
        'format' => 'above',
      ),
      'teaser' => array(
        'format' => 'hidden',
        'exclude' => 1,
      ),
      'full' => array(
        'format' => 'hidden',
        'exclude' => 1,
      ),
      '4' => array(
        'format' => 'default',
        'exclude' => 0,
      ),
      '2' => array(
        'format' => 'default',
        'exclude' => 0,
      ),
      '3' => array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
    'widget_active' => '1',
    'type' => 'computed',
    'required' => '0',
    'multiple' => '0',
    'module' => 'computed_field',
    'active' => '1',
    'code' => '$node_field[0][\'value\'] = "";',
    'display_format' => '$display = $node_field_item[\'value\'];',
    'store' => 1,
    'data_type' => 'int',
    'data_length' => '11',
    'data_not_NULL' => 0,
    'data_default' => '0',
    'data_sortable' => 1,
    'widget' => array(
      'default_value' => NULL,
      'default_value_php' => NULL,
      'label' => 'Have and want count',
      'weight' => '7',
      'description' => '',
      'type' => 'computed',
      'module' => 'computed_field',
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Have and want count');

  return $fields;
}
