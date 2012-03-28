<?php

/**
 * Implementation of hook_ctools_plugin_api().
 */
function vibio_product_displays_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "context" && $api == "context") {
    return array("version" => 3);
  }
  elseif ($module == "page_manager" && $api == "pages_default") {
    return array("version" => 1);
  }
  elseif ($module == "strongarm" && $api == "strongarm") {
    return array("version" => 1);
  }
}

/**
 * Implementation of hook_flag_default_flags().
 */
function vibio_product_displays_flag_default_flags() {
  $flags = array();
  // Exported flag: "Sink".
  $flags['sink'] = array(
    'content_type' => 'node',
    'title' => 'Sink',
    'global' => '1',
    'types' => array(
      '0' => 'product',
    ),
    'flag_short' => 'sink',
    'flag_long' => 'Click to make this product sink to the bottom of the list.',
    'flag_message' => 'Product sunk.',
    'unflag_short' => 'un-sink',
    'unflag_long' => 'Click to make this product not sink to the bottom anymore.',
    'unflag_message' => 'Product un-sunk.',
    'unflag_denied_text' => '',
    'link_type' => 'toggle',
    'roles' => array(
      'flag' => array(
        '0' => 4,
      ),
      'unflag' => array(
        '0' => 4,
      ),
    ),
    'show_on_page' => 1,
    'show_on_teaser' => 0,
    'show_on_form' => 0,
    'access_author' => '',
    'i18n' => 0,
    'module' => 'vibio_product_displays',
    'locked' => array(
      '0' => 'name',
    ),
    'api_version' => 2,
  );
  return $flags;
}

/**
 * Implementation of hook_rules_defaults().
 */
function vibio_product_displays_rules_defaults() {
  return array(
    'rules' => array(
      'vibio_product_displays_1' => array(
        '#type' => 'rule',
        '#set' => 'event_node_insert',
        '#label' => 'Update have/want count when Have/Want clicked',
        '#active' => 1,
        '#weight' => '0',
        '#categories' => array(
          '0' => 'vibio_product_displays',
          'vibio_product_displays' => 'vibio_product_displays',
        ),
        '#status' => 'default',
        '#conditions' => array(
          '0' => array(
            '#type' => 'condition',
            '#settings' => array(
              'type' => array(
                'vibio_item' => 'vibio_item',
              ),
              '#argument map' => array(
                'node' => 'node',
              ),
            ),
            '#name' => 'rules_condition_content_is_type',
            '#info' => array(
              'label' => 'Created content is Vibio Item',
              'arguments' => array(
                'node' => array(
                  'type' => 'node',
                  'label' => 'Content',
                ),
              ),
              'module' => 'Node',
            ),
            '#weight' => 0,
          ),
        ),
        '#actions' => array(
          '0' => array(
            '#weight' => 0,
            '#info' => array(
              'label' => 'Load content by id',
              'arguments' => array(
                'nid' => array(
                  'type' => 'number',
                  'label' => 'Content ID',
                ),
                'vid' => array(
                  'type' => 'number',
                  'label' => 'Content Revision ID',
                  'description' => 'If you want to load a specific revision, specify it\'s revision id. Else leave it empty to load the current revision.',
                  'required' => FALSE,
                ),
              ),
              'new variables' => array(
                'item_have_want_product' => array(
                  'label' => 'corresponding product',
                  'label callback' => FALSE,
                  'type' => 'node',
                ),
              ),
              'module' => 'Node',
            ),
            '#name' => 'rules_action_load_node',
            '#settings' => array(
              'nid' => '<?php vibio_item_product_nid($node->nid); ?>',
              'vid' => '',
              '#argument map' => array(
                'node_loaded' => 'item_have_want_product',
              ),
              '#eval input' => array(
                'rules_input_evaluator_php' => array(
                  'nid' => array(
                    '0' => 'node',
                  ),
                ),
              ),
            ),
            '#type' => 'action',
          ),
          '1' => array(
            '#type' => 'action',
            '#settings' => array(
              'field_name' => 'field_item_count',
              '#argument map' => array(
                'node' => 'item_have_want_product',
              ),
              'value' => NULL,
              'code' => '',
              'vars' => array(),
            ),
            '#name' => 'content_rules_action_populate_field',
            '#info' => array(
              'label' => 'Populate corresponding product\'s field \'field_item_count\'',
              'arguments' => array(
                'node' => array(
                  'type' => 'node',
                  'label' => 'Content',
                ),
              ),
              'eval input' => array(
                '0' => 'code',
              ),
              'module' => 'CCK',
            ),
            '#weight' => 0,
          ),
        ),
        '#version' => 6003,
      ),
      'vibio_product_displays_2' => array(
        '#type' => 'rule',
        '#set' => 'event_node_delete',
        '#label' => 'Update have/want count when item deleted',
        '#active' => 1,
        '#weight' => '0',
        '#categories' => array(
          '0' => 'vibio_product_displays',
          'vibio_product_displays' => 'vibio_product_displays',
        ),
        '#status' => 'default',
        '#conditions' => array(
          '0' => array(
            '#info' => array(
              'label' => 'Deleted content is Vibio Item',
              'arguments' => array(
                'node' => array(
                  'type' => 'node',
                  'label' => 'Content',
                ),
              ),
              'module' => 'Node',
            ),
            '#name' => 'rules_condition_content_is_type',
            '#settings' => array(
              'type' => array(
                'vibio_item' => 'vibio_item',
              ),
              '#argument map' => array(
                'node' => 'node',
              ),
            ),
            '#type' => 'condition',
            '#weight' => 0,
          ),
        ),
        '#actions' => array(
          '0' => array(
            '#weight' => 0,
            '#type' => 'action',
            '#settings' => array(
              'nid' => '<?php vibio_item_product_nid($node->nid); ?>',
              'vid' => '',
              '#argument map' => array(
                'node_loaded' => 'item_have_want_product',
              ),
              '#eval input' => array(
                'rules_input_evaluator_php' => array(
                  'nid' => array(
                    '0' => 'node',
                  ),
                ),
              ),
            ),
            '#name' => 'rules_action_load_node',
            '#info' => array(
              'label' => 'Load content by id',
              'arguments' => array(
                'nid' => array(
                  'type' => 'number',
                  'label' => 'Content ID',
                ),
                'vid' => array(
                  'type' => 'number',
                  'label' => 'Content Revision ID',
                  'description' => 'If you want to load a specific revision, specify it\'s revision id. Else leave it empty to load the current revision.',
                  'required' => FALSE,
                ),
              ),
              'new variables' => array(
                'item_have_want_product' => array(
                  'label' => 'corresponding product',
                  'label callback' => FALSE,
                  'type' => 'node',
                ),
              ),
              'module' => 'Node',
            ),
          ),
          '1' => array(
            '#info' => array(
              'label' => 'Populate corresponding product\'s field \'field_item_count\'',
              'arguments' => array(
                'node' => array(
                  'type' => 'node',
                  'label' => 'Content',
                ),
              ),
              'eval input' => array(
                '0' => 'code',
              ),
              'module' => 'CCK',
            ),
            '#name' => 'content_rules_action_populate_field',
            '#settings' => array(
              'field_name' => 'field_item_count',
              '#argument map' => array(
                'node' => 'item_have_want_product',
              ),
              'value' => NULL,
              'code' => '',
              'vars' => array(),
            ),
            '#type' => 'action',
            '#weight' => 0,
          ),
        ),
        '#version' => 6003,
      ),
    ),
  );
}

/**
 * Implementation of hook_views_api().
 */
function vibio_product_displays_views_api() {
  return array(
    'api' => '3.0',
  );
}
