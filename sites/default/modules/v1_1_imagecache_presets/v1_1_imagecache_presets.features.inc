<?php

/**
 * Implementation of hook_imagecache_default_presets().
 */
function v1_1_imagecache_presets_imagecache_default_presets() {
  $items = array(
    'full_profile_pic' => array(
      'presetname' => 'full_profile_pic',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale_and_crop',
          'data' => array(
            'width' => '120',
            'height' => '120',
          ),
        ),
      ),
    ),
    'item_for_offers' => array(
      'presetname' => 'item_for_offers',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '132px',
            'height' => '',
            'upscale' => 0,
          ),
        ),
        '1' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '',
            'height' => '132px',
            'upscale' => 0,
          ),
        ),
      ),
    ),
    'little_profile_pic' => array(
      'presetname' => 'little_profile_pic',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale_and_crop',
          'data' => array(
            'width' => '40',
            'height' => '40',
          ),
        ),
      ),
    ),
    'product_fixed_width' => array(
      'presetname' => 'product_fixed_width',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '160px',
            'height' => '',
            'upscale' => 0,
          ),
        ),
      ),
    ),
    'product_fixed_width_teaser' => array(
      'presetname' => 'product_fixed_width_teaser',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '80px',
            'height' => '',
            'upscale' => 1,
          ),
        ),
      ),
    ),
  );
  return $items;
}
