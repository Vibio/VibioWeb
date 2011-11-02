<?php

define("JW_SETUP_DESC",
  "The JW Player&trade; is used to deliver video content through your Drupal website.  " .
  "For more information please visit <a href=http://www.longtailvideo.com/" . JW_PLAYER_GA_VARS . " target=_blank>LongTail Video</a>."
);

define("JW_SETUP_EDIT_PLAYERS", 
  "<strong>Optional:</strong> This section allows you to create custom players. It is possible to configure flashvars, skins and plugins."
);

define("JW_LICENSED",
  "To obtain a licensed player, please purchase a license from LongTail Video."
);

/**
 * Responsible for the display of the Player management page.
 * @file Class definition of PlayerState.
 * @see AdminState
 */
class PlayerState extends AdminState {

  /**
   * @see AdminState::__construct()
   * @param $player
   * @param string $post_values
   * @return \PlayerState
   */
  public function __construct($player, $post_values = "") {
    parent::__construct($player, $post_values);
  }

  /**
   * @see AdminState::getID()
   * @return string
   */
  public static function getID() {
    return "player";
  }

  /**
   * @see AdminState::getNextState()
   * @return \BasicState
   */
  public function getNextState() {
    LongTailFramework::setConfig($this->current_player);
    return new BasicState($this->current_player, $this->current_post_values);
  }

  /**
   * @see AdminState::getPreviousState()
   */
  public function getPreviousState() {
    echo "This shouldn't be called";
  }

  /**
   * @see AdminState::getCancelState()
   */
  public function getCancelState() {
    echo "This shouldn't be called";
  }

  /**
   * @see AdminState::getSaveState()
   */
  public function getSaveState() {
    echo "This shouldn't be called";
  }

  /**
   * @see AdminState::render()
   * @param $form
   * @param $form_state
   */
  public function render(&$form, $form_state) {
    drupal_add_js(drupal_get_path("module", "jwplayermodule") . "/jwplayermodule_admin.php", "module", "footer");
    $form["Version"] = array(
      "#description" => JW_SETUP_DESC,
      "#type" => "fieldset",
      "#title" => "JW Player Version",
    );
    $form["Version"]["Current"] = array(
      "#type" => "item",
    );
    $version = variable_get(LONGTAIL_KEY . "version", "");
    if (isset($version) && !empty($version)) {
      $value = "<p><strong>Current Player</strong>: JW Player " . $version . "</p>";
      $upgrade = "";
      if (!strstr($version, "Licensed")) {
        $value .= "<p>" . JW_LICENSED . "</p>";
        $upgrade = "Click Here to Upgrade";
      }
    } else {
      $value = "<p><strong>Current Player: Version Unknown</strong></p>";
      $upgrade = "Click Here to Reinstall";
    }
    $form["Version"]["Current"]["#value"] = $value;
    $form["Version"]["Upgrade"] = array(
      "#type" => "item",
      "#name" => "Update_Player",
      "#value" => "<a href='" . base_path() . "admin/settings/jwplayermodule/upgrade'>$upgrade</a>",
    );
    $form["Manage"] = array(
      "#description" => JW_SETUP_EDIT_PLAYERS,
      "#type" => "fieldset",
      "#title" => "Manage Players",
    );
    $form["Manage"]["Players"] = array();
    $this->renderDefaultPlayer($form);
    $this->renderPlayers($form);
    $form["Manage"]["Create"] = array(
      "#type" => "submit",
      "#name" => "Next",
      "#value" => t("Create Custom Player"),
    );
    $form["Manage"]["Hidden"][LONGTAIL_KEY . "new_player"] = array(
      "#type" => "hidden",
      "#id" => LONGTAIL_KEY . "new_player",
      "#name" => LONGTAIL_KEY . "new_player",
    );
    $form["Manage"]["Hidden"][LONGTAIL_KEY . "config"] = array(
      "#type" => "hidden",
      "#id" => LONGTAIL_KEY . "player",
      "#name" => LONGTAIL_KEY . "config",
    );
    $form["Manage"]["Hidden"][LONGTAIL_KEY . "state"] = array(
      "#type" => "hidden",
      "#value" => PlayerState::getID(),
      "#name" => LONGTAIL_KEY . "state",
    );
  }

  private function renderDefaultPlayer(&$form) {
    $form["Manage"]["Players"]["Out-of-the-Box"] = array();
    $form["Manage"]["Players"]["Out-of-the-Box"][LONGTAIL_KEY . "default"] = array(
      "#type" => "radio",
      "#name" => LONGTAIL_KEY . "default",
      "#return_value" => "Out-of-the-Box",
      "#default_value" => variable_get(LONGTAIL_KEY . "default", "Out-of-the-Box"),
      "#ahah" => array(
        "path" => "admin/settings/jwplayermodule/default/js",
        "wrapper" => "version-wrapper",
        "event" => "change",
      ),
    );
    $form["Manage"]["Players"]["Out-of-the-Box"]["name"] = array(
      "#type" => "item",
      "#value" => "Out-of-the-Box",
    );
    $form["Manage"]["Players"]["Out-of-the-Box"]["controlbar"] = array(
      "#type" => "item",
      "#value" => "bottom",
    );
    $form["Manage"]["Players"]["Out-of-the-Box"]["skin"] = array(
      "#type" => "item",
      "#value" => "default",
    );
    $form["Manage"]["Players"]["Out-of-the-Box"]["dock"] = array(
      "#type" => "item",
      "#value" => "false",
    );
    $form["Manage"]["Players"]["Out-of-the-Box"]["autostart"] = array(
      "#type" => "item",
      "#value" => "false",
    );
    $form["Manage"]["Players"]["Out-of-the-Box"]["height"] = array(
      "#type" => "item",
      "#value" => "300",
    );
    $form["Manage"]["Players"]["Out-of-the-Box"]["width"] = array(
      "#type" => "item",
      "#value" => "400",
    );
    $form["Manage"]["Players"]["Out-of-the-Box"]["actions"] = array();
    $form["Manage"]["Players"]["Out-of-the-Box"]["actions"]["copy"] = array(
      "#type" => "submit",
      "#id" => LONGTAIL_KEY . "player_Out-of-the-Box",
      "#value" => "Copy",
      "#name" => "Next",
      "#attributes" => array("onclick" => "copyHandler(this);"),
    );
  }

  private function renderPlayers(&$form) {
    $players = LongTailFramework::getConfigs();
    $count = 1;
    foreach ($players as $player) {
      if ($player != "New Player") {
        LongTailFramework::setConfig($player);
        $details = LongTailFramework::getPlayerFlashVars(LongTailFramework::BASIC);
        $form["Manage"]["Players"][$player] = array();
        $form["Manage"]["Players"][$player][LONGTAIL_KEY . "default"] = array(
          "#type" => "radio",
          "#name" => LONGTAIL_KEY . "default",
          "#return_value" => $player,
          "#default_value" => variable_get(LONGTAIL_KEY . "default", "Out-of-the-Box"),
          "#ahah" => array(
            "path" => "admin/settings/jwplayermodule/default/js",
            "wrapper" => "version-wrapper",
            "event" => "change",
          ),
        );
        $form["Manage"]["Players"][$player]["name"] = array(
          "#type" => "item",
          "#value" => $player,
        );
        foreach (array_keys($details) as $detail) {
          foreach($details[$detail] as $fvar)
          $form["Manage"]["Players"][$player][$fvar->getName()] = array(
            "#type" => "item",
            "#value" => $fvar->getDefaultValue() ? $fvar->getDefaultValue() : "default",
          );
        }
        $form["Manage"]["Players"][$player]["actions"] = array();
        $form["Manage"]["Players"][$player]["actions"]["copy"] = array(
          "#type" => "submit",
          "#id" => LONGTAIL_KEY . "player_$player",
          "#value" => "Copy",
          "#name" => "Next",
          "#attributes" => array("onclick" => "copyHandler(this)"),
        );
        $form["Manage"]["Players"][$player]["actions"]["edit"] = array(
          "#type" => "submit",
          "#id" => LONGTAIL_KEY . "player_$player",
          "#value" => "Edit",
          "#name" => "Next",
          "#attributes" => array("onclick" => "selectionHandler(this)"),
        );
        $form["Manage"]["Players"][$player]["actions"]["delete"] = array(
          "#type" => "submit",
          "#id" => LONGTAIL_KEY . "player_$player",
          "#value" => "Delete",
          "#name" => "Next",
          "#attributes" => array("onclick" => "deleteHandler(this)"),
        );
      }
      $count++;
    }
  }

}

?>