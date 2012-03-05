<?php

/**
 * Implementation of hook_strongarm().
 */
function vibio_product_displays_strongarm() {
  $export = array();

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'site_frontpage';
  $strongarm->value = 'home';
  $export['site_frontpage'] = $strongarm;

  $strongarm = new stdClass;
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'vibio_product_displays_exclude_categories';
  $strongarm->value = array(
    41 => 41,
    42 => 42,
    56 => 56,
    62 => 62,
    67 => 0,
    65 => 0,
    32 => 0,
    64 => 0,
    59 => 0,
    46 => 0,
    30 => 0,
    39 => 0,
    66 => 0,
    36 => 0,
    34 => 0,
    31 => 0,
    55 => 0,
    29 => 0,
    52 => 0,
    60 => 0,
    54 => 0,
    33 => 0,
    58 => 0,
    51 => 0,
    43 => 0,
    49 => 0,
    40 => 0,
    61 => 0,
    38 => 0,
    63 => 0,
    35 => 0,
    57 => 0,
    53 => 0,
    37 => 0,
    47 => 0,
    44 => 0,
    50 => 0,
    45 => 0,
    48 => 0,
  );
  $export['vibio_product_displays_exclude_categories'] = $strongarm;

  return $export;
}
