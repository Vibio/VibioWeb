<?php
/**
 * This class is a base class for Wizard states.
 * @file The class definition for AdminState.
 */
abstract class AdminState {
  
  protected $current_player;
  protected $current_post_values;

  /**
   * Constructor.
   * @param String $player The currently selected player.
   * @param String $post_values String representation of current POST values.
   */
  public function __construct($player, $post_values = "") {
    $this->current_player = $player;
    $this->current_post_values = $post_values;
  }

  /**
   * Display an error message at the top of the current page.
   * @param String $message The message to be displayed.
   */
  protected function errorMessage($message) { ?>
    <div class="error fade" id="message">
      <p><strong><?php echo $message ?></strong></p>
    </div> <?php
  }

  /**
   * Display an info message at the top of the current page.
   * @param String $message The message to be displayed.
   */
  protected function infoMessage($message) { ?>
    <div class="fade updated" id="message" onclick="this.parentNode.removeChild (this)">
      <p><strong><?php echo $message ?></strong></p>
    </div> <?php
  }

  /**
   * Renders the currently selected player name.  Displays a text field when
   * creating or copying a player.
   * @param $form
   * @param $form_state
   */
  protected function selectedPlayer(&$form, $form_state) {
    $value = $this->current_player;
    $new_player = LONGTAIL_KEY . "new_player";
    if (isset($form_state["storage"][$new_player]) && $form_state["storage"][$new_player] != "") {
      $value = $form_state["storage"][$new_player];
    }
    $form[$new_player] = array(
      "#type" => "textfield",
      "#title" => t("Selected Player"),
      '#name' => $new_player,
      "#default_value" => $value,
    );
  }

  /**
   * Displays the buttons at the bottom of a Wizard page.  Available buttons
   * can be controlled through parameter options.
   * @param $form
   * @param string $id The id of the current state.
   * @param boolean $show_previous Show the previous button.
   * @param boolean $show_next Show the next button.
   * @param boolean $show_save Show the save button.
   * @param boolean $show_cancel Show the cancel button.
   *
   */
  protected function buttonBar(&$form, $id, $show_previous = true, $show_next = true, $show_save = true, $show_cancel = true) {
    $form["Buttons"] = array(
      "#prefix" => "<p>",
      "#suffix" => "</p>",
    );
    $form["Buttons"][LONGTAIL_KEY . "state"] = array(
      "#type" => "hidden",
      "#name" => LONGTAIL_KEY . "state",
      "#value" => $id,
    );
    if ($show_save) {
      drupal_add_js(drupal_get_path("module", "jwplayermodule") . "/jwplayermodule_admin.php", "module", "footer");
      $form["Buttons"]["Save"] = array(
        "#type" => "submit",
        "#name" => "Save",
        "#value" => "Save",
        "#attributes" => array("onclick", "return saveHandler(this);"),
      );
    }
    if ($show_previous) {
      $form["Buttons"]["Previous"] = array(
        "#type" => "submit",
        "#name" => "Previous",
        "#value" => "Previous",
      );
    }
    if ($show_cancel) {
      $form["Buttons"]["Cancel"] = array(
        "#type" => "submit",
        "#name" => "Cancel",
        "#value" => "Cancel",
      );
    }
    if ($show_next) {
      $form["Buttons"]["Next"] = array(
        "#type" => "submit",
        "#name" => "Next",
        "#value" => "Next",
      );
    }
  }

  /**
   * Get the id of the current state.
   * @return string The id of the current state.
   */
  abstract public static function getID();

  /**
   * This function gets the state to render itself.
   * @param $form
   * @param $form_state
   */
  abstract public function render(&$form, $form_state);

  /**
   * Returns a reference to the state that should be rendered when the next
   * button is clicked.
   * @return AdminState A reference to the next AdminState.
   */
  abstract public function getNextState();

  /**
   * Returns a reference to the state that should be rendered when the previous
   * button is clicked.
   * @return AdminState A reference to the previous AdminState.
   */
  abstract public function getPreviousState();

  /**
   * Returns a reference to the state that should be rendered when the cancel
   * button is clicked.
   * @return AdminState A reference to the cancel AdminState.
   */
  abstract public function getCancelState();

  /**
   * Returns a reference to the state that should be rendered when the save
   * button is clicked.
   * @return AdminState A reference to the save AdminState.
   */
  abstract public function getSaveState();

}

?>
