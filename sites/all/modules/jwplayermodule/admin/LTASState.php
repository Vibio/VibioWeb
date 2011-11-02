<?php

define("JW_PLAYER_LTAS_DESC",
  "The LongTail AdSolution (LTAS) is a service which allows you to monetize your content through pre-roll, post-roll and overlay ads from premium video advertisers.  " .
  "To use this service you must have an account.  For more information visit <a href=http://www.longtailvideo.com/adsolution" . JW_PLAYER_GA_VARS . " target=_blank>http://www.longtailvideo.com/adsolution</a>." .
  "<br/><br/><strong>To sign up for this service, <a href=https://dashboard.longtailvideo.com/signup.aspx" . JW_PLAYER_GA_VARS . " target=_blank>click here to create an account</a>.</strong>"
);

/**
 * Responsible for the display of LTAS configuration.
 * @file Class definition of LTASState
 * @see AdminState
 */
class LTASState extends WizardState {

  /**
   * @see AdminState::__construct()
   * @param $player
   * @param string $post_values
   * @return \LTASState
   */
  public function __construct($player, $post_values = "") {
    parent::__construct($player, $post_values);
  }

  /**
   * @see AdminState::getID()
   * @return string
   */
  public static function getID() {
    return "ltas";
  }

  /**
   * @see AdminState::getNextState()
   * @return \PluginState
   */
  public function getNextState() {
    LongTailFramework::setConfig($this->current_player);
    return new PluginState($this->current_player, $this->current_post_values);
  }

  /**
   * @see AdminState::getPreviousState()
   * @return \AdvancedState
   */
  public function getPreviousState() {
    LongTailFramework::setConfig($this->current_player);
    return new AdvancedState($this->current_player);
  }

  /**
   * @see AdminState::getCancelState()
   * @return \PlayerState
   */
  public function getCancelState() {
    return new PlayerState("");
  }

  /**
   * @see AdminState::getSaveState()
   * @return \PlayerState
   */
  public function getSaveState() {
    return new PlayerState("");
  }

  public static function getTitle() {
    return WizardState::LTAS_STATE;
  }

  /**
   * @see AdminState::render()
   * @param $form
   * @param $form_state
   */
  public function render(&$form, $form_state) {
    $ltas = LongTailFramework::getLTASConfig();
    parent::getBreadcrumbBar($form, $form_state);
    $this->selectedPlayer($form, $form_state);
    $form["LTAS"] = array(
      "#type" => "fieldset",
      "#title" => t("LongTail AdSolution Settings"),
    );
    $value = $form_state["storage"][LONGTAIL_KEY . "plugin_ltas_enable"] ? $form_state["storage"][LONGTAIL_KEY . "plugin_ltas_enable"] : $ltas["enabled"];
    $form["LTAS"][LONGTAIL_KEY . "plugin_ltas_enable"] = array(
      "#type" => "checkbox",
      "#name" => LONGTAIL_KEY . "plugin_ltas_enable",
      "#title" => t("Enable LongTail AdSolution"),
      "#default_value" => $value,
    );
    $form["LTAS"]["description"] = array(
      "#type" => "item",
      "#description" => JW_PLAYER_LTAS_DESC,
    );
    $value = $_POST[LONGTAIL_KEY . "plugin_ltas_cc"] ? $_POST[LONGTAIL_KEY . "plugin_ltas_cc"] : $ltas["channel_code"];
    $form["LTAS"][LONGTAIL_KEY . "plugin_ltas_cc"] = array(
      "#type" => "textfield",
      "#name" => LONGTAIL_KEY . "plugin_ltas_cc",
      "#title" => t("ltas.cc"),
      "#default_value" => $value,
      "#description" => "Your LTAS channel code.  Obtained from the <a href=https://dashboard.longtailvideo.com/" . JW_PLAYER_GA_VARS . " target=_blank>AdSolution Dashboard.</a>",
    );
    $this->buttonBar($form, LTASState::getID());
  }

}
?>
