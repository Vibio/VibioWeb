<?php


/**
 * @file
 * Node page callbacks for the visitors module.
 */

require_once dirname(__FILE__) .'/forms/date_filter.php';

/**
 * Implementation of hook_node().
 */
function visitors_node() {
  if ($node = node_load(arg(1))) {
    $date_format    = variable_get('date_format_short_custom', 'Y-m-d H:i:s');
    $items_per_page = variable_get('visitors_lines_per_page', 10);

    $header = array(
      array('data' => t('#')),
      array('data' => t('ID'), 'field' => 'visitors_id', 'sort' => 'desc'),
      array('data' => t('Date'), 'field' => 'visitors_date_time'),
      array('data' => t('Referer'), 'field' => 'visitors_referer'),
      array('data' => t('User'), 'field' => 'u.name'),
      array('data' => t('Operations'))
    );

    $sql = sprintf("SELECT v.*, u.name, u.uid
                    FROM {visitors} v
                    LEFT JOIN {users} u
                    ON u.uid = v.visitors_uid
                    WHERE v.visitors_path LIKE 'node/%d%%'
                    AND %s".
                    tablesort_sql($header),
                    $node->nid,
                    visitors_date_filter_sql_condition()
                  );

    $results = pager_query($sql, $items_per_page, 0, NULL);

    $rows = array();
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    $i = 0 + ($page  * $items_per_page);

    while ( $data = db_fetch_object($results) ) {
      $user = user_load(array('uid' => $data->visitors_uid));
      $user_page = theme('username', $data);
      $rows[] = array(
        ++$i,
        $data->visitors_id,
        format_date($data->visitors_date_time, 'custom', $date_format),
        l($data->visitors_referer, $data->visitors_referer),
        $user_page,
        l(t('details'), 'visitors/hits/'. $data->visitors_id)
      );
    }

    $output  = visitors_date_filter();
    $output .= theme('table', $header, $rows);
    $output .= theme('pager', NULL, $items_per_page, 0);
    drupal_set_title(check_plain($node->title));

    return $output;
  }
}
