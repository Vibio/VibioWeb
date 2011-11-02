<?php
/**
 * Responsible for the display of Player configuration options.
 * @file Contains the class definition for FlashVarState
 * @see AdminState
 */
class FlashVarState extends WizardState {

  /**
   * @see AdminState::__construct()
   * @param $player
   * @param string $post_values
   * @return \FlashVarState
   */
  public function __construct($player, $post_values = "") {
    parent::__construct($player, $post_values);
  }

  /**
   * @see AdminState::getID()
   * @return string
   */
  public static function getID() {
    return "flashvars";
  }

  /**
   * @see AdminState::getNextState()
   * @return \LTASState
   */
  public function getNextState() {
    LongTailFramework::setConfig($this->current_player);
    return new LTASState($this->current_player, $this->current_post_values);
  }

  /**
   * @see AdminState::getPreviousState()
   * @return \PlayerState
   */
  public function getPreviousState() {
    return new PlayerState("");
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

  /**
   * @see AdminState::render()
   * @param $form
   * @param $form_state
   */
  public function render(&$form, $form_state) {
    $flash_vars = LongTailFramework::getPlayerFlashVars($this->flashVarCat());
    parent::getBreadcrumbBar($form, $form_state);
    $this->selectedPlayer($form, $form_state);
    $this->getHeader();
    foreach(array_keys($flash_vars) as $groups) {
      $form[$groups] = array(
        "#type" => "fieldset",
        "#title" => $groups,
      );
      foreach($flash_vars[$groups] as $fvar) {
        $name = LONGTAIL_KEY . "player_" . $fvar->getName();
        $value = $form_state["storage"][$name] ? $form_state["storage"][$name] : $fvar->getDefaultValue();
        $form[$groups][$name] = array(
          "#title" => $fvar->getName(),
          "#description" => $fvar->getDescription(),
          "#default_value" => $value,
        );
        if ($fvar->getType() == FlashVar::SELECT) {
          $form[$groups][$name]["#type"] = "select";
          $options = array();
          foreach ($fvar->getValues() as $val) {
            $options[$val] = $val;
          }
          $form[$groups][$name]["#options"] = $options;
        } else {
          $form[$groups][$name]["#type"] = "textfield";
        }
      }
    }
    $this->getFooter($form, $form_state);
    $this->getButtonBar($form, false);
  }

  /**
   * Returns the flashvar category to be rendered.
   * @return string The flashvar category.  If null it will display all
   * categories.
   */
  protected function flashVarCat() {
    return "";
  }

  /**
   * Renders the button bar.
   * @param $form
   * @param boolean $show_previous Controls whether the previous button is
   * shown.  Defaults to true.
   *
   */
  protected function getButtonBar(&$form, $show_previous = true) {
    $this->buttonBar($form, FlashVarState::getID(), $show_previous);
  }

  /**
   * Returns the title of the page.
   * @return string The title of the page.
   */
  public static function getTitle() {
    return "Player Settings";
  }

  /**
   *
   * @return string <type>
   */
  protected function getHeader() {
    return "";
  }

  /**
   * @param $form
   * @param $form_state   *
   * @return string <type>
   */
  protected function getFooter(&$form, $form_state) {
    return "";
  }

}
?>
