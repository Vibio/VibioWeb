<?php

/**
 * Implementation of hook_strongarm().
 */
function cck_and_menus_from_v1_1_strongarm() {
  $export = array();

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_anonymous_offer';
  $strongarm->value = 0;
  $export['comment_anonymous_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_anonymous_offer_neg_buyer';
  $strongarm->value = 0;
  $export['comment_anonymous_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_anonymous_offer_neg_seller';
  $strongarm->value = 0;
  $export['comment_anonymous_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_controls_offer';
  $strongarm->value = '3';
  $export['comment_controls_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_controls_offer_neg_buyer';
  $strongarm->value = '3';
  $export['comment_controls_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_controls_offer_neg_seller';
  $strongarm->value = '3';
  $export['comment_controls_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_mode_offer';
  $strongarm->value = '4';
  $export['comment_default_mode_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_mode_offer_neg_buyer';
  $strongarm->value = '4';
  $export['comment_default_mode_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_mode_offer_neg_seller';
  $strongarm->value = '4';
  $export['comment_default_mode_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_order_offer';
  $strongarm->value = '1';
  $export['comment_default_order_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_order_offer_neg_buyer';
  $strongarm->value = '1';
  $export['comment_default_order_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_order_offer_neg_seller';
  $strongarm->value = '1';
  $export['comment_default_order_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_per_page_offer';
  $strongarm->value = '50';
  $export['comment_default_per_page_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_per_page_offer_neg_buyer';
  $strongarm->value = '50';
  $export['comment_default_per_page_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_default_per_page_offer_neg_seller';
  $strongarm->value = '50';
  $export['comment_default_per_page_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_form_location_offer';
  $strongarm->value = '0';
  $export['comment_form_location_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_form_location_offer_neg_buyer';
  $strongarm->value = '0';
  $export['comment_form_location_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_form_location_offer_neg_seller';
  $strongarm->value = '0';
  $export['comment_form_location_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_offer';
  $strongarm->value = '2';
  $export['comment_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_offer_neg_buyer';
  $strongarm->value = '2';
  $export['comment_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_offer_neg_seller';
  $strongarm->value = '2';
  $export['comment_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_preview_offer';
  $strongarm->value = '1';
  $export['comment_preview_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_preview_offer_neg_buyer';
  $strongarm->value = '1';
  $export['comment_preview_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_preview_offer_neg_seller';
  $strongarm->value = '1';
  $export['comment_preview_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_subject_field_offer';
  $strongarm->value = '1';
  $export['comment_subject_field_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_subject_field_offer_neg_buyer';
  $strongarm->value = '1';
  $export['comment_subject_field_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'comment_subject_field_offer_neg_seller';
  $strongarm->value = '1';
  $export['comment_subject_field_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'content_extra_weights_offer';
  $strongarm->value = array(
    'title' => '-5',
    'revision_information' => '3',
    'author' => '2',
    'options' => '4',
    'comment_settings' => '7',
    'menu' => '-2',
    'path' => '6',
    'attachments' => '5',
  );
  $export['content_extra_weights_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'language_content_type_offer';
  $strongarm->value = '0';
  $export['language_content_type_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'language_content_type_offer_neg_buyer';
  $strongarm->value = '0';
  $export['language_content_type_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'language_content_type_offer_neg_seller';
  $strongarm->value = '0';
  $export['language_content_type_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_offer';
  $strongarm->value = array(
    0 => 'status',
    1 => 'promote',
  );
  $export['node_options_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_offer_neg_buyer';
  $strongarm->value = array(
    0 => 'status',
    1 => 'promote',
  );
  $export['node_options_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_offer_neg_seller';
  $strongarm->value = array(
    0 => 'status',
    1 => 'promote',
  );
  $export['node_options_offer_neg_seller'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'upload_offer';
  $strongarm->value = '1';
  $export['upload_offer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'upload_offer_neg_buyer';
  $strongarm->value = '0';
  $export['upload_offer_neg_buyer'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'upload_offer_neg_seller';
  $strongarm->value = '1';
  $export['upload_offer_neg_seller'] = $strongarm;

  return $export;
}
