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
            '#name' => 'rules_action_mail',
            '#settings' => array(
              'to' => '<?php module_load_include("inc", "product"); print _product_get_owner_mail($node->nid); ?>',
              'from' => '',
              'subject' => 'Your Item Was Featured on Vibio',
              'message' => 'Hello [node:author-name],

This is an automatic reminder from the site Vibio. One of your items from your collection has been chosen to be featured by Vibio.

To see  your featured item, follow this link:

<a href="<?php print $base_url; ?>">Vibio Feature Items</a>

Don\'t worry, if you\'ve made your item private, no one knows that you own it. We featured the item because other users publicly own it.

Congrats!


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
            '#type' => 'action',
          ),
        ),
        '#version' => 6003,
      ),
      'notifications_2' => array(
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
      'notifications_3' => array(
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
            '#weight' => 0,
          ),
          '1' => array(
            '#weight' => 0,
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
            '#name' => 'content_rules_field_has_value',
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
            '#type' => 'condition',
          ),
        ),
        '#actions' => array(
          '0' => array(
            '#weight' => 0,
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
            '#name' => 'rules_action_mail',
            '#settings' => array(
              'to' => '<?php module_load_include("inc", "product"); print _product_forsale_owner_mail(vibio_item_product_nid($node->nid)); ?>',
              'from' => '',
              'subject' => '[user:name] Wants an Item You\'re Selling',
              'message' => 'Hi,

This is an automatic reminder from the site Vibio. [user:user-name-url] has put your [node:title] in their want list.   

To connect with this user, follow this link:
[user:user-profile-url]

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
                ),
              ),
            ),
            '#type' => 'action',
          ),
        ),
        '#version' => 6003,
      ),
    ),
  );
}
