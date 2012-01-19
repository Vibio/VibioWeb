<?php

/**
 * Implementation of hook_rules_defaults().
 */
function privacy_enforcement_rules_defaults() {
  return array(
    'rules' => array(
      'privacy_enforcement_1' => array(
        '#type' => 'rule',
        '#set' => 'event_init',
        '#label' => 'Disallow access to /node',
        '#active' => 1,
        '#weight' => '0',
        '#categories' => array(
          '0' => 'vibio',
          'privacy_enforcement' => 'privacy_enforcement',
        ),
        '#status' => 'default',
        '#conditions' => array(
          '0' => array(
            '#weight' => 0,
            '#type' => 'condition',
            '#settings' => array(
              'code' => 'return (!user_access(\'administer nodes\')) && (substr(request_uri(), 1) == \'node\');',
              'vars' => array(),
            ),
            '#name' => 'rules_condition_custom_php',
            '#info' => array(
              'label' => 'Execute custom PHP code',
              'module' => 'PHP',
              'eval input' => array(
                '0' => 'code',
              ),
            ),
          ),
        ),
        '#actions' => array(
          '0' => array(
            '#weight' => 0,
            '#info' => array(
              'label' => 'Page redirect',
              'module' => 'System',
              'eval input' => array(
                '0' => 'path',
                '1' => 'query',
                '2' => 'fragment',
              ),
            ),
            '#name' => 'rules_action_drupal_goto',
            '#settings' => array(
              'path' => '<?php echo variable_get(\'site_frontpage\', \'home\'); ?>',
              'query' => '',
              'fragment' => '',
              'force' => 1,
              'immediate' => 1,
              '#eval input' => array(
                'rules_input_evaluator_php' => array(
                  'path' => array(),
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
