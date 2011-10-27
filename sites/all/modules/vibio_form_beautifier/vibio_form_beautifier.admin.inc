<?php

/**
 * Form builder function for module settings.
 */
function vibio_form_beautifier_settings() {
  $form['vibio_form_beautifier_enable'] = array(
    '#type' => 'fieldset',
    '#title' => 'Enable / Disable Features',
  );
  $form['vibio_form_beautifier_enable']['vibio_form_beautifier_chk_rdio_enable'] = array(
    '#type' => 'checkbox',
    '#title' => 'Enable Checkbox and Radio',
    //'#description' => l('example', 'http://bit.ly/EtQfr', array('attributes' => array('target' => '_blank'))),
    '#default_value' => variable_get('vibio_form_beautifier_chk_rdio_enable', 1),
  );
  $form['vibio_form_beautifier_enable']['vibio_form_beautifier_drpdwn_enable'] = array(
    '#type' => 'checkbox',
    '#title' => 'Enable Dropdown',
    //'#description' => l('example', 'http://bit.ly/kqS69', array('attributes' => array('target' => '_blank'))),
    '#default_value' => variable_get('vibio_form_beautifier_drpdwn_enable', 1),
  );
  $form['vibio_form_beautifier_enable']['vibio_form_beautifier_focus_enable'] = array(
    '#type' => 'checkbox',
    '#title' => 'Enable Focus Field',
    //'#description' => l('example', 'http://bit.ly/xPxbs', array('attributes' => array('target' => '_blank'))),
    '#default_value' => variable_get('vibio_form_beautifier_focus_enable', 1),
  );
  
  
  // Settings for Checkboxes and Radio Buttons
  if (variable_get('vibio_form_beautifier_chk_rdio_enable', 1)) {
	  $form['vibio_form_beautifier_checkboxes_radios'] = array(
	    '#type' => 'fieldset',
	    '#title' => 'Checkboxes &amp; Radio Buttons',
	    '#collapsible' => TRUE,
		  '#collapsed' => TRUE,
	  );
	  $options = array('prettyCheckboxes' => 'prettyCheckboxes');
	  if (module_exists('jquery_ui')) {
	    $options += array('checkbox' => 'jQueryUI Checkbox');
	  }
	  else {
	    variable_set('vibio_form_beautifier_library', 'prettyCheckboxes');
	  }
	  
	  $form['vibio_form_beautifier_checkboxes_radios']['vibio_form_beautifier_checkboxes_radios_library'] = array(
	    '#type' => 'radios',
	    '#title' => 'JS Library Option',
	  	'#options' => $options,
	  	'#default_value' => variable_get('vibio_form_beautifier_checkboxes_radios_library', 'prettyCheckboxes'),
		);
		
		if (variable_get('vibio_form_beautifier_checkboxes_radios_library', 'prettyCheckboxes') == 'prettyCheckboxes') {
		  $form['vibio_form_beautifier_checkboxes_radios']['vibio_form_beautifier_prettycheckboxes'] = array(
		    '#type' => 'fieldset',
		    '#title' => 'prettyCheckboxes Settings',
		    '#collapsible' => TRUE,
		    '#description' => 'prettyCheckboxes allows you to set a width and height to match custom CSS',
		  );
			$form['vibio_form_beautifier_checkboxes_radios']['vibio_form_beautifier_prettycheckboxes']['vibio_form_beautifier_prettycheckboxes_width'] = array(
			  '#type' => 'textfield',
			  '#title' => 'width',
			  '#default_value' => variable_get('vibio_form_beautifier_prettycheckboxes_width', 17),
			);
			$form['vibio_form_beautifier_checkboxes_radios']['vibio_form_beautifier_prettycheckboxes']['vibio_form_beautifier_prettycheckboxes_height'] = array(
			  '#type' => 'textfield',
			  '#title' => 'height',
			  '#default_value' => variable_get('vibio_form_beautifier_prettycheckboxes_height', 17),
			);
			/*$form['vibio_form_beautifier_prettycheckboxes']['vibio_form_beautifier_display'] = array(
			  '#type' => 'radios',
			  '#title' => 'Display Options',
			  '#options' => array('list' => 'Vertical', 'inline' => 'Horizontal'),
			  '#default_value' => variable_get('vibio_form_beautifier_display', 'list'),
			);*/
		}
	
	  $form['vibio_form_beautifier_checkboxes_radios']['vibio_form_beautifier_checkboxes_radios_include'] = array(
	    '#type' => 'textarea',
	    '#title' => 'Enabled Forms',
	    '#description' => 'Use individual Form IDs separated by a comma to limit which forms this module affects. <br >\'*\' specifies ALL FORMS.',
	    '#default_value' => variable_get('vibio_form_beautifier_checkboxes_radios_include', '*'),
	  );
	  $form['vibio_form_beautifier_checkboxes_radios']['vibio_form_beautifier_checkboxes_radios_exclude'] = array(
	    '#type' => 'textarea',
	    '#title' => 'Disabled Forms',
	    '#description' => 'Use individual Form IDs separated by a comma to limit which forms this module DOES NOT affects. This value will override Enabled Form settings above.',
	    '#default_value' => variable_get('vibio_form_beautifier_checkboxes_radios_exclude', ''),
	  );  
  }
  
  
  // Settings for Dropdown Select
  if (variable_get('vibio_form_beautifier_drpdwn_enable', 1)) {
	  $form['vibio_form_beautifier_dropdown'] = array(
	    '#type' => 'fieldset',
	    '#title' => 'Dropdown Select',
	    '#collapsible' => TRUE,
		  '#collapsed' => TRUE,
	  );
	  $form['vibio_form_beautifier_dropdown']['vibio_form_beautifier_drpdwn_style'] = array(
	    '#type' => 'radios',
	    '#title' => 'Choose Dropdown Style',
	    '#options' => array('custom' => 'Default', 'sexy' => 'Sexy'),
	    '#default_value' => variable_get('vibio_form_beautifier_drpdwn_style', 'sexy'),
	  );
	  $form['vibio_form_beautifier_dropdown']['vibio_form_beautifier_drpdwn_init_value'] = array(
	    '#type' => 'textfield',
	    '#title' => 'Initial Hidden value',
	    '#description' => 'Initial value of the hidden input of the dropdown box.',
	    '#default_value' => variable_get('vibio_form_beautifier_drpdwn_init_value', ''),
	  );
	  $form['vibio_form_beautifier_dropdown']['vibio_form_beautifier_drpdwn_empty_text'] = array(
	    '#type' => 'textfield',
	    '#title' => 'Empty Text',
	    '#description' => 'Will be shown when an empty text input has no focus.',
	    '#default_value' => variable_get('vibio_form_beautifier_drpdwn_empty_text', '--Select Option--'),
	  );
	  $form['vibio_form_beautifier_dropdown']['vibio_form_beautifier_drpdwn_autofill'] = array(
	    '#type' => 'checkbox',
	    '#title' => 'Use Empty Text',
	    '#description' => 'Will be shown when an empty text input has no focus.',
	    '#default_value' => variable_get('vibio_form_beautifier_drpdwn_autofill', 1),
	  );
	  $form['vibio_form_beautifier_dropdown']['vibio_form_beautifier_drpdwn_selected'] = array(
	    '#type' => 'checkbox',
	    '#title' => 'Default Selected',
	    '#description' => 'The selected option of the selectbox will become the initial value',
	    '#default_value' => variable_get('vibio_form_beautifier_drpdwn_selected', 1),
	  );	
	  $form['vibio_form_beautifier_dropdown']['vibio_form_beautifier_drpdwn_up'] = array(
	    '#type' => 'checkbox',
	    '#title' => 'Display Above',
	    '#description' => 'The dropdown list will appear above text input. ',
	    '#default_value' => variable_get('vibio_form_beautifier_drpdwn_up', 0),
	  );
	  $form['vibio_form_beautifier_dropdown']['vibio_form_beautifier_drpdwn_include'] = array(
	    '#type' => 'textarea',
	    '#title' => 'Enabled Forms',
	    '#description' => 'Use individual Form IDs separated by a comma to limit which forms this module affects. <br >\'*\' specifies ALL FORMS.',
	    '#default_value' => variable_get('vibio_form_beautifier_drpdwn_include', '*'),
	  );
	  $form['vibio_form_beautifier_dropdown']['vibio_form_beautifier_drpdwn_exclude'] = array(
	    '#type' => 'textarea',
	    '#title' => 'Disabled Forms',
	    '#description' => 'Use individual Form IDs separated by a comma to limit which forms this module DOES NOT affects. This value will override Enabled Form settings above.',
	    '#default_value' => variable_get('vibio_form_beautifier_drpdwn_exclude', ''),
	  );  
  }

  
  
  // Settings for Field Focus
  if (variable_get('vibio_form_beautifier_focus_enable', 1)) {
	  $form['vibio_form_beautifier_focus'] = array(
	    '#type' => 'fieldset',
	    '#title' => 'Field Focus',
	    '#collapsible' => TRUE,
		  '#collapsed' => TRUE,
	  );
	  $form['vibio_form_beautifier_focus']['vibio_form_beautifier_focus_include'] = array(
	    '#type' => 'textarea',
	    '#title' => 'Enabled Forms',
	    '#description' => 'Use individual Form IDs separated by a comma to limit which forms this module affects. <br >\'*\' specifies ALL FORMS.',
	    '#default_value' => variable_get('vibio_form_beautifier_focus_include', '*'),
	  );
	  $form['vibio_form_beautifier_focus']['vibio_form_beautifier_focus_exclude'] = array(
	    '#type' => 'textarea',
	    '#title' => 'Disabled Forms',
	    '#description' => 'Use individual Form IDs separated by a comma to limit which forms this module DOES NOT affects. This value will override Enabled Form settings above.',
	    '#default_value' => variable_get('vibio_form_beautifier_focus_exclude', ''),
	  );  
  }
  $form = system_settings_form($form);

  return $form;
}