<?php

/**
 * Implementation of hook_context_default_contexts().
 */
function vibio_product_displays_context_default_contexts() {
  $export = array();

  $context = new stdClass;
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'homepage';
  $context->description = 'Show the category filter on the home page.';
  $context->tag = 'views';
  $context->conditions = array(
    'path' => array(
      'values' => array(
        'home' => 'home',
        'home/*' => 'home/*',
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'vibio_product_displays-filter' => array(
          'module' => 'vibio_product_displays',
          'delta' => 'filter',
          'region' => 'content_top',
          'weight' => 0,
        ),
      ),
    ),
  );
  $context->condition_mode = 0;

  // Translatables
  // Included for use with string extractors like potx.
  t('Show the category filter on the home page.');
  t('views');
  $export['homepage'] = $context;

  return $export;
}
