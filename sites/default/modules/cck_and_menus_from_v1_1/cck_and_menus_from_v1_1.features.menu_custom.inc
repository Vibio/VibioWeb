<?php

/**
 * Implementation of hook_menu_default_menu_custom().
 */
function cck_and_menus_from_v1_1_menu_default_menu_custom() {
  $menus = array();

  // Exported menu: menu-about
  $menus['menu-about'] = array(
    'menu_name' => 'menu-about',
    'title' => 'About',
    'description' => '(was the Secondary menu in v1.0 with just a few links, seen in the footer only?)',
  );
  // Exported menu: primary-links
  $menus['primary-links'] = array(
    'menu_name' => 'primary-links',
    'title' => 'Primary links',
    'description' => 'Primary links are often used at the theme layer to show the major sections of a site. A typical representation for primary links would be tabs along the top.',
  );
  // Translatables
  // Included for use with string extractors like potx.
  t('(was the Secondary menu in v1.0 with just a few links, seen in the footer only?)');
  t('About');
  t('Primary links');
  t('Primary links are often used at the theme layer to show the major sections of a site. A typical representation for primary links would be tabs along the top.');


  return $menus;
}
