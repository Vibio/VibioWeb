<?php

/**
 * Implementation of hook_user_default_roles().
 */
function cck_and_menus_from_v1_1_user_default_roles() {
  $roles = array();

  // Exported role: Editors
  $roles['Editors'] = array(
    'name' => 'Editors',
  );

  return $roles;
}
