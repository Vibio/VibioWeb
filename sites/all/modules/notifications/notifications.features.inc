<?php

/**
 * Implementation of hook_rules_defaults().
 */
function notifications_rules_defaults() {
  return array(
    'rules' => array(
      'notifications_1' => array(
        '#type' => 'rule',
        '#set' => 'event_flag_flagged_feature',
        '#label' => 'When item is featured',
        '#active' => 1,
        '#weight' => '0',
        '#categories' => array(
          '0' => 'Notifications',
          'notifications' => 'notifications',
        ),
        '#status' => 'default',
        '#conditions' => array(),
        '#actions' => array(
          '0' => array(
            '#weight' => 0,
            '#type' => 'action',
            '#settings' => array(
              'to' => '<?php module_load_include("inc", "product"); print _product_get_owner_mail($node->nid); ?>',
              'from' => '',
              'subject' => 'Your Item Was Featured on Vibio',
              'message' => 'Hi [node:author-name],

We thought your item "[node:title-raw]" was so cool we decided to feature it on our front page. It should be up there for a couple days. Please let us know if you\'d like it taken down. Otherwise, kudos for having such awesome style!

Best,

Diana Bull
Vibio Community Manager',
              '#eval input' => array(
                'token_rules_input_evaluator' => array(
                  'message' => array(
                    '0' => 'node',
                  ),
                  'to' => array(
                    '0' => 'node',
                  ),
                ),
                'rules_input_evaluator_php' => array(
                  'to' => array(
                    '0' => 'node',
                  ),
                ),
              ),
            ),
            '#name' => 'rules_action_mail',
            '#info' => array(
              'label' => 'Send a mail to an arbitrary mail address',
              'module' => 'System',
              'eval input' => array(
                '0' => 'subject',
                '1' => 'message',
                '2' => 'from',
                '3' => 'to',
              ),
            ),
          ),
        ),
        '#version' => 6003,
      ),
      'notifications_2' => array(
        '#type' => 'rule',
        '#set' => 'event_node_insert',
        '#label' => 'When product is wanted',
        '#active' => 1,
        '#weight' => '0',
        '#categories' => array(
          '0' => 'Notifications',
          'notifications' => 'notifications',
        ),
        '#status' => 'default',
        '#conditions' => array(
          '0' => array(
            '#weight' => 0,
            '#info' => array(
              'label' => 'It\'s an item',
              'label callback' => FALSE,
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
          ),
          '1' => array(
            '#type' => 'condition',
            '#settings' => array(
              'field_name' => 'field_have_want_like',
              '#argument map' => array(
                'node' => 'node',
              ),
              'value' => array(
                '0' => array(
                  'value' => '20',
                ),
              ),
              'code' => '',
              'vars' => array(),
            ),
            '#name' => 'content_rules_field_has_value',
            '#info' => array(
              'label' => 'The item is wanted',
              'label callback' => FALSE,
              'arguments' => array(
                'node' => array(
                  'type' => 'node',
                  'label' => 'Content',
                ),
              ),
              'eval input' => array(
                '0' => 'code',
                '1' => 'value|0|value',
              ),
              'module' => 'CCK',
            ),
            '#weight' => 0,
          ),
        ),
        '#actions' => array(
          '0' => array(
            '#type' => 'action',
            '#settings' => array(
              'to' => '<?php module_load_include("inc", "product"); print _product_forsale_owner_mail(vibio_item_product_nid($node->nid)); ?>',
              'from' => '',
              'subject' => '[user:name] Wants an Item You\'re Selling',
              'message' => '',
              '#eval input' => array(
                'token_rules_input_evaluator' => array(
                  'subject' => array(
                    '0' => 'user',
                  ),
                ),
                'rules_input_evaluator_php' => array(
                  'to' => array(
                    '0' => 'node',
                  ),
                ),
              ),
            ),
            '#name' => 'rules_action_mail',
            '#info' => array(
              'label' => 'Send a mail to an arbitrary mail address',
              'module' => 'System',
              'eval input' => array(
                '0' => 'subject',
                '1' => 'message',
                '2' => 'from',
                '3' => 'to',
              ),
            ),
            '#weight' => 0,
          ),
        ),
        '#version' => 6003,
      ),
    ),
  );
}
