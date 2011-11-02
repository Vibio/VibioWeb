<?php
/**
 * Responsible for displaying the Advanced Player configuration options.
 * @file The class definition for AdvancedState
 * @see FlashVarState
 */
class AdvancedState extends FlashVarState {

  /**
   * @see FlashVarState::__construct()
   * @param $player
   * @param string $post_values
   * @return \AdvancedState
   */
  public function __construct($player, $post_values = "") {
    parent::__construct($player, $post_values);
  }

  /**
   * @see FlashVarState::getID()
   * @return string
   */
  public static function getID() {
    return "advanced";
  }

  /**
   * @see FlashVarState::getNextState()
   * @return \LTASState
   */
  public function getNextState() {
    LongTailFramework::setConfig($this->current_player);
    return new LTASState($this->current_player, $this->current_post_values);
  }

  /**
   * @see FlashVarState::getPreviousState()
   * @return \BasicState
   */
  public function getPreviousState() {
    LongTailFramework::setConfig($this->current_player);
    return new BasicState($this->current_player);
  }

  /**
   * @see FlashVarState::getCancelState()
   * @return \PlayerState
   */
  public function getCancelState() {
    return new PlayerState("");
  }

  /**
   * @see FlashVarState::getSaveState()
   * @return \PlayerState
   */
  public function getSaveState() {
    return new PlayerState("");
  }

  /**
   * @see FlashVarState::flashVarCat()
   * @return \LongTailFramework.ADVANCED
   */
  protected function flashVarCat() {
    return LongTailFramework::ADVANCED;
  }

  /**
   * @see FlashVarState::getButtonBar()
   * @param $form
   * @param bool $show_previous
   */
  protected function getButtonBar(&$form, $show_previous = true) {
    $this->buttonBar($form, AdvancedState::getID());
  }

  /**
   * @see FlashVarState::getTitle()
   * @return \WizardState.ADVANCED_STATE
   */
  public static function getTitle() {
    return WizardState::ADVANCED_STATE;
  }

  /**
   * @see FlashVarState::getFooter()
   * @param $form
   * @param $form_state
   */
  protected function getFooter(&$form, $form_state) {
    $form["Additional"] = array(
      "#type" => "fieldset",
      "#title" => t("Additional Flashvars"),
    );
    $name = LONGTAIL_KEY . "player_flashvars";
    $form["Additional"][$name] = array(
      "#type" => "textarea",
      "#title" => "flashvars",
      "#description" => t("Enter a comma delimited list of additional flashvars you would like to be used by this player."),
      "#name" => $name,
      "#default_value" => $form_state["storage"][$name] ? $form_state["storage"][$name] : LongTailFramework::getPlayerAdditionalFlashVars(),
      "#rows" => 2,
    );
  }

}
?>
