<?php

include_once (dirname (__FILE__) . "/admin/AdminContext.php");

/**
 * @file
 *
 * This file contains the hook implementations for rendering the admin
 * pages, as well as the AHAH implementation, for the module.
 */

function jwplayermodule_upgrade_js() {
  variable_set(LONGTAIL_KEY . "version", $_POST["ahah_version"]);
  print drupal_json(array("status" => TRUE, "data" => ""));
}

function jwplayermodule_default_js() {
  variable_set(LONGTAIL_KEY . "default", $_POST[LONGTAIL_KEY . "default"]);
  print drupal_json(array("status" => TRUE, "data" => ""));
}

/**
 * Constructs the administration form for customizing Players.
 * This monstrosity of a method needs to be refactored, but will likely be
 * replaced entirely in the next version.
 * @param null $form_state
 * @return array
 */
function jwplayermodule_admin($form_state = NULL) {
  $module_path = drupal_get_path("module", "jwplayermodule");
  $css_path = $module_path . "/css/jwplayermodule.css";
  drupal_add_css($css_path);

  $admin_context = new AdminContext();
  $form = array();
  $admin_context->processState($form, $form_state);

  install_message();

  return $form;
}

/**
 * Implementation of validate function for configuration form submit.
 * @param $form
 * @param $form_state
 */
function jwplayermodule_admin_validate($form, $form_state) {
  if ($form_state["clicked_button"]["#id"] == "edit-submit-1" || $form_state["clicked_button"]["#id"] == "edit-submit-2") {
    if ($form_state["values"]["Name"] == "") {
      form_set_error("", t("You must enter a name for the Player before saving."));
    }
  }
  elseif ($form_state["clicked_button"]["#id"] == "edit-delete") {
    $configs = LongTailFramework::getConfigs();
    if ($configs[$form_state["values"]["Configs"]] == "New Player") {
      form_set_error("", t("You must select a configuration (other than \"New Player\") to delete."));
    }
  }
}

/**
 * Implementation of submit function for the configuration form.
 * Note that specific buttons are processed due to the AHAH functionality
 * triggering a form submit that shouldn't be processed.
 * @param $form
 * @param $form_state
 */
function jwplayermodule_admin_submit($form, &$form_state) {
  foreach ($form_state["values"] as $key => $value) {
    if (strstr($key, LONGTAIL_KEY)) {
      $form_state["storage"][$key] = $value;
    }
  }
}

function jwplayermodule_license($form_state = NULL) {
  $form = array();

  install_message();

  $form["License"] = array(
    "#type" => "fieldset",
    "#title" => "Licensing Details",
    "#description" => t(JW_PLAYER_LICENSE_DESC),
  );
  return $form;
}

function jwplayermodule_upgrade($form_state = NULL) {
  $form = array();
  $atts = array(
    "file" => "http://content.longtailvideo.com/videos/bunny.flv",
    "image" => "http://content.longtailvideo.com/videos/bunny.jpg",
    "id" => "jwplayer-1"
  );

  install_message();

  jwplayermodule_add_file_check();

  $form['#attributes'] = array('enctype' => 'multipart/form-data');
  $form["Upgrade"] = array();
  if ($form_state["storage"]["install"]) {
    $form["Upgrade"]["Version"] = array(
      "#type" => "fieldset",
    );
    if ($form_state["storage"]["install"] == "Upload") {
      $message = "Successfully installed your player, ";
    } else {
      $message = "Successfully downloaded and installed the latest player version, ";
    }
    $form["Upgrade"]["Version"]["output"] = array(
      "#type" => "item",
      "#id" => "version_message",
      "#value" => $message,
    );
    $form["Upgrade"]["Version"]["ahah_version"] = array(
      "#type" => "hidden",
      "#prefix" => "<div id=\"version-wrapper\" style='display: none;'>",
      "#ahah" => array(
        "path" => "admin/settings/jwplayermodule/upgrade/js",
        "wrapper" => "version-wrapper",
        "event" => "change",
      ),
      "#suffix" => "</div>",
    );
    $form["Upgrade"]["Player"] = array(
      "#type" => "fieldset",
      "#title" => "Player Preview",
    );
    $form["Upgrade"]["Player"]["preview"] = array(
      "#type" => "item",
      "#value" => theme("jwplayermodule_render_player", "", $atts),
    );
    drupal_add_js(drupal_get_path("module", "jwplayermodule") . "/jwplayermodule_upgrade.js", "module", "footer");
  } else {
    $form["Upgrade"]["Upload"] = array(
      "#type" => "fieldset",
      "#title" => "Manually Upgrade",
      "#description" => t(JW_PLAYER_UPLOAD_DESC),
    );
    $form["Upgrade"]["Upload"]["zip"] = array(
      "#type" => "file",
      "#title" => "Install JW Player",
    );
    $form["Upgrade"]["Upload"]["submit"] = array(
      "#type" => "submit",
      "#name" => "upload",
      "#value" => t("Upload"),
    );
    $form["Upgrade"]["Download"] = array(
      "#type" => "fieldset",
      "#title" => "Automatically Upgrade",
      "#description" => t(JW_PLAYER_DOWNLOAD_DESC),
    );
    $form["Upgrade"]["Download"]["submit"] = array(
      "#type" => "submit",
      "#name" => "download",
      "#value" => "Install Latest JW Player",
    );
  }

  return $form;
}

function install_message() {
  if (!file_exists(LongTailFramework::getPlayerPath())) {
    drupal_set_message("To complete installation of the JW Player Module for Drupal, please click install. <a href='" . base_path() . "admin/settings/jwplayermodule/upgrade'>Install Latest JW Player</a>", "warning", FALSE);
  }
}

function jwplayermodule_upgrade_submit($form, &$form_state) {
  if ($form_state["clicked_button"]["#id"] == "edit-submit") {
    $form_state["storage"]["install"] = "Upload";
    $validators = array(
      'file_validate_extensions' => array("zip"),
    );
    if ($file = file_save_upload("zip", $validators, JWPLAYER_FILES_DIR . "/player", FILE_EXISTS_REPLACE)) {
      install_player_from_package($file->filepath);
    }
  } elseif ($form_state["clicked_button"]["#id"] == "edit-submit-1") {
    $form_state["storage"]["install"] = "Download";
    if ($request = drupal_http_request("http://www.longtailvideo.com/wp/jwplayer.zip")) {
      if ($file = file_save_data($request->data, JWPLAYER_FILES_DIR . "/player/mediaplayer.zip", FILE_EXISTS_REPLACE)) {
        install_player_from_package($file);
      }
    }
  }
}
