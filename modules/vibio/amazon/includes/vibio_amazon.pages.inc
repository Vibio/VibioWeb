<?php

/**
 * @file
 * Page callbacks and supporting routines for the vibio_amazon module.
 */
 
/**
 * Page callback for the ASIN-to-product conversion and redirect page.
 */
function vibio_amazon_page_product_from_asin() {
  $redirect_path = '<front>';
  
  if (!empty($_GET['asin'])) {
    $asin = check_plain($_GET['asin']);
    
    // Look for an existing local product first.
    if ($local_nid = vibio_amazon_local_product($asin)) {
      drupal_goto('node/' . $local_nid);
    }
    else {
      // If the local product doesn't exist, create it.
      if ($nid = vibio_amazon_create_product_from_asin($asin)) {
        $redirect_path = 'node/' . $nid;
      }
      else {
        watchdog('vibio_amazon', 'Unable to locate local Amazon data for ASIN: @ASIN', array('@ASIN' => $_GET['asin']), WATCHDOG_ERROR);
      }      
    }    
  }
  else {
    watchdog('vibio_amazon', 'ASIN to Product page reached without valid ASIN.', NULL, WATCHDOG_ERROR);
  }
  
  drupal_goto($redirect_path);
}

/**
 * Creates a local Drupal product node from Amazon data.
 *
 * @param string $asin
 *   The ASIN of the amazon data to convert to a local node.
 *
 * @return int
 *   The node ID of the newly-created node.
 */
function vibio_amazon_create_product_from_asin($asin, $js = FALSE) {
  $nid = 0;
  
  $items = amazon_item_lookup_from_db(array($asin));
  if (!empty($items[$asin])) {
    $amazon_data = $items[$asin];
    
    // Check for an existing local node first.
    $sql = "SELECT nid FROM {amazon_item_node} WHERE asin = '%s'";
    $nid = db_result(db_query($sql, $amazon_data['asin']));
    
    if (!$nid) {
      // This block was taken almost verbatim from a previous
      // implementation of hook_amazon_item_insert().
      // We might consider refactoring this to a node_save()
      // in an upcoming iteration.
      module_load_include("inc", "node", "node.pages");
      $vocabulary_id = variable_get("vibio_amazon_category_id", 1);
      $tid = _vibio_amazon_get_vibio_term_from_asin($amazon_data['asin'], $vocabulary_id);
      $product_title = strlen($amazon_data['title']) > 255 ? substr($amazon_data['title'], 0, 255) : $amazon_data['title'];

      $publisher = user_load(1);
      $node = array(
        'type' => 'product',
        'uid' => $publisher->uid,
        'title' => $product_title,
        'name' => $publisher->name,
        'op' => t('Save'),
        'field_amazon_asin' => array(
          array(
            'asin'  => $amazon_data['asin'],
          ),
        ),
        'taxonomy'  => array(
          $vocabulary_id => $tid,
        ),
      );
      $image_data = array();
      $node = (object)$node;

      _vibio_amazon_add_images($node, $amazon_data);
      node_submit($node);
      node_save($node);
      drupal_get_messages(); // Suppress any messages that would be shown to the user.

      // Save the relationship between the amazon item and the Drupal node.
      $nid = $node->nid;
      $sql = "INSERT INTO {amazon_item_node} (nid, asin, module) VALUES (%d,'%s','%s') ON DUPLICATE KEY UPDATE";
      db_query($sql, $nid, $amazon_data['asin'], 'amazon');
    }
  }

  //If this is a javascript request from the have/want buttons...
  if($js){
    //...return JSON
    drupal_json(array('nid' => $nid));
    exit();
  }else{
    //otherwise operate as a normal function
    return $nid;
  }

}
