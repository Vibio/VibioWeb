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
              'message' => 'Hello [node:author-name],

This is an automatic reminder from the site Vibio. Your item [node:title-raw] has been chosen to be featured by Vibio.

To see  your featured item, follow this link:

<a href="<?php global $base_url; print $base_url; ?>">Vibio Featured Items</a>

Don\'t worry, if you\'ve made your item private, no one knows that you own it. We featured the item because other users publicly own it.

Congrats on your great taste!

If you don\'t want to receive these emails again, please email diana@vibio.com.
',
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
                  'message' => array(),
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
      'notifications_3' => array(
        '#type' => 'rule',
        '#set' => 'event_node_update',
        '#label' => 'When product is wanted',
        '#active' => 1,
        '#weight' => '0',
        '#categories' => array(
          '0' => 'Notifications',
          '1' => 'notifications',
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
            '#weight' => 0,
            '#type' => 'action',
            '#settings' => array(
              'to' => '<?php module_load_include("inc", "product"); print _product_forsale_owner_mail(vibio_item_product_nid($node->nid)); ?>',
              'from' => '',
              'subject' => '[user:user] Wants an Item You\'re Selling',
              'message' => 'Hi,

This is an automatic reminder from the site Vibio. [user:user] has put your item "[node:title]" in their want list.   

To connect with this user, follow this link:
<?php global $base_url; print $base_url; ?>/user/[user:uid]

If this item is private, don\'t worry: no one knows that you own it. We\'re just sending you a reminder.

Congrats!


If you don\'t want to receive these emails again, please contact diana@vibio.com.',
              '#eval input' => array(
                'token_rules_input_evaluator' => array(
                  'subject' => array(
                    '0' => 'user',
                  ),
                  'message' => array(
                    '0' => 'node',
                    '1' => 'user',
                  ),
                ),
                'rules_input_evaluator_php' => array(
                  'to' => array(
                    '0' => 'node',
                  ),
                  'message' => array(),
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
    ),
  );
}
