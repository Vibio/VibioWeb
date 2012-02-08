<?php

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
