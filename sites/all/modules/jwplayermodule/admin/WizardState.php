<?php
/**
 * Description of WizardState
 *
 * @author Cameron
 */
abstract class WizardState extends AdminState {

  const BASIC_STATE = "Basic Settings";
  const ADVANCED_STATE = "Advanced Settings";
  const LTAS_STATE = "Ad Serving";
  const PLUGIN_STATE = "Plugins";

  /**
   * @see AdminState::__construct()
   * @param $player
   * @param string $post_values
   * @return \WizardState
   */
  public function __construct($player, $post_values = "") {
    parent::__construct($player, $post_values);
  }

  protected function getBreadcrumbBar(&$form, $form_state) {
    $form["Breadcrumbs"] = array(
      "#prefix" => "<div id='breadcrumbs'>",
      "#suffix" => "</div>",
    );
    $form["Breadcrumbs"][LONGTAIL_KEY . BasicState::getID()] = array(
      "#type" => "radio",
      "#id" => LONGTAIL_KEY . BasicState::getID(),
      "#printed" => true,
      "#name" => "breadcrumb",
      "#title" => BasicState::getTitle(),
      "#return_value" => BasicState::getID(),
      "#default_value" => $this->getID(),
    );
    $form["Breadcrumbs"][LONGTAIL_KEY . AdvancedState::getID()] = array(
      "#type" => "radio",
      "#id" => LONGTAIL_KEY . AdvancedState::getID(),
      "#name" => "breadcrumb",
      "#title" => AdvancedState::getTitle(),
      "#return_value" => AdvancedState::getID(),
      "#default_value" => $this->getID(),
    );
    $form["Breadcrumbs"][LONGTAIL_KEY . LTASState::getID()] = array(
      "#type" => "radio",
      "#id" => LONGTAIL_KEY . LTASState::getID(),
      "#name" => "breadcrumb",
      "#title" => LTASState::getTitle(),
      "#return_value" => LTASState::getID(),
      "#default_value" => $this->getID(),
    );
    $form["Breadcrumbs"][LONGTAIL_KEY . PluginState::getID()] = array(
      "#type" => "radio",
      "#id" => LONGTAIL_KEY . PluginState::getID(),
      "#name" => "breadcrumb",
      "#title" => PluginState::getTitle(),
      "#return_value" => PluginState::getID(),
      "#default_value" => $this->getID(),
    );
  }

  abstract public static function getTitle();

}
?>
