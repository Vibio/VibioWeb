<?php

/**
 * Implementation of hook_node_info().
 */
function cck_and_menus_from_v1_1_node_info() {
  $items = array(
    'offer' => array(
      'name' => t('Offer'),
      'module' => 'features',
      'description' => t('The Offer cck connects other pieces to create an offer with negotiations between buyer and seller.  It is mostly created by computed fields from the negotiations that create it.  Will need to consider efficiency later on.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'has_body' => '0',
      'body_label' => '',
      'min_word_count' => '0',
      'help' => '',
    ),
    'offer_neg_buyer' => array(
      'name' => t('Offer Neg Buyer'),
      'module' => 'features',
      'description' => t('A timestamped offer to buy an item, part of an Offer (by function and nodereference)'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'has_body' => '1',
      'body_label' => t('Body'),
      'min_word_count' => '0',
      'help' => '',
    ),
    'offer_neg_seller' => array(
      'name' => t('Offer Neg Seller'),
      'module' => 'features',
      'description' => t('Messages from seller to buyer'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'has_body' => '1',
      'body_label' => t('Body'),
      'min_word_count' => '0',
      'help' => '',
    ),
  );
  return $items;
}
