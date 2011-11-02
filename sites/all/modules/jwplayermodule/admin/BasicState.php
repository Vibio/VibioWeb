<?php
/**
 * Responsible for display the Basic Player configuration options.
 * @file The class definition for BasicState
 * @author Cameron
 * @see FlashVarState
 */
class BasicState extends FlashVarState {

  /**
   * @see FlashVarState::__construct()
   * @param $player
   * @param string $post_values
   * @return \BasicState
   */
  public function __construct($player, $post_values = "") {
    parent::__construct($player, $post_values);
  }

  /**
   * @see FlashVarState::getId()
   * @return string
   */
  public static function getID() {
    return "basic";
  }

  /**
   * @see FlashVarState::getNextState()
   * @return \AdvancedState
   */
  public function getNextState() {
    LongTailFramework::setConfig($this->current_player);
    return new AdvancedState($this->current_player, $this->current_post_values);
  }

  /**
   * @see FlashVarState::getPreviousState()
   * @return \PlayerState
   */
  public function getPreviousState() {
    return new PlayerState("");
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
   * @return \LongTailFramework.BASIC
   */
  protected function flashVarCat() {
    return LongTailFramework::BASIC;
  }

  /**
   * @see FlashVarState::getButtonBar()
   * @param $form
   * @param bool $show_previous
   */
  protected function getButtonBar(&$form, $show_previous = true) {
    $this->buttonBar($form, BasicState::getID(), $show_previous);
  }

  /**
   * @see FlashVarState::getTitle()
   * @return \WizardState.BASIC_STATE
   */
  public static function getTitle() {
    return WizardState::BASIC_STATE;
  }

}
?>
