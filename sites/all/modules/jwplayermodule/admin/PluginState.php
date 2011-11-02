<?php
/**
 * Responsible for rendering plugin selection and configuration.
 * @file Class definition of PluginState
 * @see AdminState
 */
class PluginState extends WizardState {

  /**
   * @see AdminState::__construct()
   * @param $player
   * @param string $post_values
   * @return \PluginState
   */
  public function __construct($player, $post_values = "") {
    parent::__construct($player, $post_values);
  }

  /**
   * @see AdminState::getID()
   * @return string
   */
  public static function getID() {
    return "plugin";
  }

  /**
   * @see AdminState::getNextState()
   */
  public function getNextState() {
    echo "This shouldn't be called";
  }

  /**
   * @see AdminState::getPreviousState()
   * @return \LTASState
   */
  public function getPreviousState() {
    LongTailFramework::setConfig($this->current_player);
    return new LTASState($this->current_player);
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
    return WizardState::PLUGIN_STATE;
  }

  /**
   * @see AdminState::render()
   * @param $form
   * @param $form_state
   */
  public function render(&$form, $form_state) {
    $plugins = LongTailFramework::getPlugins();
    parent::getBreadcrumbBar($form, $form_state);
    $this->selectedPlayer($form, $form_state);


    $id = LONGTAIL_KEY . "plugin_selector";
    $form["Plugins"] = array(
      "#prefix" => "<div>",
      "#suffix" => "</div>",
    );
    $form["Plugins"][$id] = array();
    $form["Plugins"][$id]["tab"] = array(
      "#id" => "$id" . "_tab",
      "#type" => "item",
      "#value" => "<a href='#$id'>Plugin Selector</a>",
    );
    foreach ($plugins as $plugin) {
      $id = LONGTAIL_KEY . "plugin_" . $plugin->getRepository();
      $form["Plugins"][$id] = array();
      $form["Plugins"][$id]["tab"] = array(
        "#id" => "$id" . "_tab",
        "#type" => "item",
        "#value" => "<a href='#$id'>" . $plugin->getTitle() . "</a>",
      );
    }

    $id = LONGTAIL_KEY . "plugin_selector";
    $form["Plugins"][$id]["body"] = array();
    foreach($plugins as $plugin) {
      $name = LONGTAIL_KEY . "plugin_" . $plugin->getRepository() . "_" . "enable";
      $value = $form_state["storage"][$name] ? $form_state["storage"][$name] : $plugin->isEnabled();
      $form["Plugins"][$id]["body"][$name] = array(
        "#type" => "checkbox",
        "#title" => "Enable " . $plugin->getTitle(),
        "#description" => $plugin->getDescription(),
        "#name" => $name,
        "#default_value" => $value,
      );
    }

    foreach($plugins as $plugin) {
      $id = LONGTAIL_KEY . "plugin_" . $plugin->getRepository();
      $form["Plugins"][$id]["body"] = array();
      foreach(array_keys($plugin->getFlashVars()) as $plugin_flash_vars) {
        $p_vars = $plugin->getFlashVars();
        foreach($p_vars[$plugin_flash_vars] as $plugin_flash_var) {
          $name = LONGTAIL_KEY . "plugin_" . $plugin->getPluginPrefix() . "_" . $plugin_flash_var->getName();
          $value = $form_state["storage"][$name] ? $form_state["storage"][$name] : $plugin_flash_var->getDefaultValue();
          $form["Plugins"][$id]["body"][$name] = array(
            "#title" => $plugin->getPluginPrefix() . "." . $plugin_flash_var->getName(),
            "#description" => $plugin_flash_var->getDescription(),
            "#default_value" => $value,
          );
          if ($plugin_flash_var->getType() == FlashVar::SELECT) {
            $form["Plugins"][$id]["body"][$name]["#type"] = "select";
            $options = array();
            foreach($plugin_flash_var->getValues() as $val) {
              $options[$val] = $val;
            }
            $form["Plugins"][$id]["body"][$name]["#options"] = $options;
          } else {
            $form["Plugins"][$id]["body"][$name]["#type"] = "textfield";
          }
        }
      }
    }
    drupal_add_js($this->generateScript($plugins), "inline");
    $this->buttonBar($form, PluginState::getID(), true, false);
  }

  private function generateScript($plugins) {
    $script = "$(document).ready(function () {\n";
    $script .= "$('#tabs').tabs();\n";
    $script .= "removeAllTabs();\n";
    $script .= "$('#" . LONGTAIL_KEY . "plugin_selector_tab" . "').css('display', 'block');\n";
    foreach ($plugins as $plugin) {
      if ($plugin->isEnabled()) {
        $script .= "$('#" . LONGTAIL_KEY . "plugin_" . $plugin->getRepository() . "_tab').css('display', 'block');\n";
      }
    }
    $script .= "$('#tabs').css('display', 'block');\n";
    $script .= "$(':checkbox').change(function() {\n";
    $script .= "var name = '#' + $(this)[0].name.replace('enable', 'tab');\n";
    $script .= "var hidden = $(this)[0].name + '_hidden';\n";
    $script .= "if($(this)[0].checked) {\n";
    $script .= "$(name).css('display', 'block');\n";
    $script .= "$(name).val(true);\n";
    $script .= "} else {\n";
    $script .= "$(name).css('display', 'none');\n";
    $script .= "$(name).val(false);\n";
    $script .= "}\n";
    $script .= "})\n";
    $script .= "});\n";
    $script .= "function removeAllTabs() {\n";
    $script .= "$('#tabNavigation').children().css('display','none');\n";
    $script .= "}\n";
    return $script;
  }

}
?>
