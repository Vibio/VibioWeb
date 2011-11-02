<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

include_once("AdminState.php");
include_once("WizardState.php");
include_once("FlashVarState.php");
include_once("PlayerState.php");
include_once("BasicState.php");
include_once("AdvancedState.php");
include_once("LTASState.php");
include_once("PluginState.php");

/**
 * @file AdminContext class file.  Manages wizard state. 
 */
class AdminContext {

  /**
   * Constructor
   */
  public function AdminContext() {}

  /**
   * Given the current wizard state, determines the next state.
   * @param $form
   * @param $form_state
   */
  public function processState(&$form, $form_state) {
    $state = $form_state["storage"][LONGTAIL_KEY . "state"];
    if (isset($_POST["breadcrumb"]) && !empty($_POST["breadcrumb"])) {
      $state = $_POST["breadcrumb"];
    }
    switch ($state) {
      case BasicState::getID() :
        $state = new BasicState($form_state["storage"][LONGTAIL_KEY . "config"]);
        break;
      case AdvancedState::getID() :
        $state = new AdvancedState($form_state["storage"][LONGTAIL_KEY . "config"]);
        break;
      case LTASState::getID() :
        $state = new LTASState($form_state["storage"][LONGTAIL_KEY . "config"]);
        break;
      case PluginState::getID() :
        $state = new PluginState($form_state["storage"][LONGTAIL_KEY . "config"]);
        break;
      default :
        $state = new PlayerState($form_state["storage"][LONGTAIL_KEY . "config"]);
        break;
    }
    $this->processPost($state, $form, $form_state);
  }

  /**
   * Processes the POST data from the previous state.
   * @param AdminState $st The next state to be populated with the POST data
   * from the previous state.
   * @param $form
   * @param $form_state
   *
   */
  private function processPost($st, &$form, $form_state) {
    $state = $st;
    if (isset($_POST["Next"])) {
      if ($_POST["Next"] == "Delete") {
        LongTailFramework::setConfig($form_state["storage"][LONGTAIL_KEY . "config"]);
        LongTailFramework::deleteConfig();
        $configs = LongTailFramework::getConfigs();
        if ($configs && count($configs) >= 2 && $_POST[LONGTAIL_KEY . "config"] == variable_get($form_state["storage"][LONGTAIL_KEY . "default"], "")) {
          variable_set(LONGTAIL_KEY . "default", $configs[1]);
        } else if (!$configs || count($configs) == 1) {
          variable_set(LONGTAIL_KEY . "default", "Out-of-the-Box");
        }
        $state = new PlayerState($form_state["storage"][LONGTAIL_KEY . "config"]);
        $del_player = $form_state["storage"][LONGTAIL_KEY . "config"];
        drupal_set_message("The '$del_player' Player was successfully deleted.");
        $state->render($form, $form_state);
      } else {
        if ($_POST["Next"] == "Create Custom Player") {
          $form_state["storage"][LONGTAIL_KEY . "new_player"] = "Custom Player";
        }
        $state->getNextState()->render($form, $form_state);
      }
    } else if (isset($_POST["Previous"])) {
      $state->getPreviousState()->render($form, $form_state);
    } else if (isset($_POST["Cancel"])) {
      $state->getCancelState()->render($form, $form_state);
    } else if (isset($_POST["Save"])) {
      $config = $form_state["storage"][LONGTAIL_KEY . "config"];
      $save_player = $form_state["storage"][LONGTAIL_KEY . "new_player"] ? $form_state["storage"][LONGTAIL_KEY . "new_player"] : $config;
      LongTailFramework::setConfig($config);
      $save_values = $this->processSubmit($form_state);
      $success = LongTailFramework::saveConfig($this->convertToXML($save_values), check_plain($save_player));
      $configs = LongTailFramework::getConfigs();
      if ($configs && count($configs) == 2) {
        variable_set(LONGTAIL_KEY . "default", $config ? $config : $form_state["storage"][LONGTAIL_KEY . "new_player"]);
        variable_set(LONGTAIL_KEY . "ootb", false);
      }
      if ($success) {
        drupal_set_message(t("The '$save_player' Player was successfully saved."));
      } else {
        drupal_set_message(t("The '$save_player' failed to save.  Please make sure the " . JWPLAYER_FILES_DIR . "/configs/ directory exists and is writable."), "error");
      }
      $state->getSaveState()->render($form, $form_state);
    } else {
      if (isset($form_state["storage"][LONGTAIL_KEY . "default"])) {
        variable_set(LONGTAIL_KEY . "default", $form_state["storage"][LONGTAIL_KEY . "default"]);
      }
      LongTailFramework::setConfig($form_state["storage"][LONGTAIL_KEY . "config"]);
      $state->render($form, $form_state);
    }
  }

  /**
   * Processes the final submission of the wizard to be saved as a player
   * configuration.
   * @param $form_state
   * @return Array The array of configuration options to be saved.
   */
  private function processSubmit($form_state) {
    $data = LongTailFramework::getConfigValues();
    $plugins = array();
    foreach ($form_state["storage"] as $name => $value) {
      if (strstr($name, LONGTAIL_KEY . "player_")) {
        $val = check_plain($value);
        $new_val = $val;
        $new_name = str_replace(LONGTAIL_KEY . "player_", "", $name);
        if ($new_name == "skin") {
          if ($new_val != "[Default]") {
            $skins = LongTailFramework::getSkins();
            $new_val = LongTailFramework::getSkinURL() . $val . "." . $skins[$val];
            $data[$new_name] = $new_val;
          }
        } else if ($new_name == "flashvars") {
          $this->parseFlashvarString($new_val, $data);
        } else if (!empty($new_val)) {
          $data[$new_name] = $new_val;
        } else {
          unset($data[$new_name]);
        }
      } else if(strstr($name, LONGTAIL_KEY . "plugin_") && strstr($name, "_enable")) {
        $plugins[str_replace("_enable", "", str_replace(LONGTAIL_KEY . "plugin_", "", $name))] = check_plain($value);
      //Process the plugin flashvars.
      } else if(strstr($name, LONGTAIL_KEY . "plugin_") && !empty($value)) {
        $plugin_key = preg_replace("/_/", ".", str_replace(LONGTAIL_KEY . "plugin_", "", $name), 1);
        $found = false;
        foreach(array_keys($plugins) as $repo) {
          $key = explode(".", $plugin_key);
          if (strstr($repo, $key[0]) && $plugins[$repo]) {
            $data[$plugin_key] = check_plain($value);
            $found = true;
            break;
          }
        }
        if (!$found) {
          unset($data[$plugin_key]);
        }
      }
    }
    $active_plugins = array();
    //Build final list of plugins to be used for this Player.
    foreach($plugins as $name => $enabled) {
      if ($enabled) {
        $active_plugins[] = $name;
      }
    }
    $plugin_string = implode(",", $active_plugins);
    if (!empty($plugins)) {
      $data["plugins"] = $plugin_string;
    }
    if ($data["plugins"] == "" || empty($data["plugins"])) {
      unset($data["plugins"]);
    }
    return $data;
  }

  private function parseFlashvarString($fv_str, &$data) {
    $regex = "~([a-zA-Z0-9.]+)=([a-zA-Z0-9:\-./]+)~";
    preg_match_all($regex, $fv_str, $matches);
    for ($i = 0; $i < count($matches[0]); $i++) {
      $data[trim($matches[1][$i])] = trim($matches[2][$i]);
    }
  }

  /**
   * Converts an one dimensional array into an XML string representation.
   * @param Array $target The one dimensional array to be converted to an XML
   * string.
   * @return An xml string representation of $target.
   */
  private function convertToXML($target) {
    $output = "";
    foreach($target as $name => $value) {
      $output .= "<" . $name . ">" . $value . "</" . $name . ">\n";
    }
    return $output;
  }

}

?>
