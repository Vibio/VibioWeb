<?php
function vibio_addthis_button($variables) {
  $build_mode = $variables['build_mode'];
  $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
  if (variable_get('addthis_dropdown_disabled', '0')) {
    return ( sprintf('
      <a href="http://www.addthis.com/bookmark.php"
        onclick="addthis_url   = location.href; addthis_title = document.title; return addthis_click(this);">
      <img src="%s" width="%d" height="%d" %s /></a>
      ',
      $https ? addslashes(variable_get('addthis_image_secure', 'https://secure.addthis.com/button1-share.gif')) : addslashes(variable_get('addthis_image', 'http://s9.addthis.com/button1-share.gif')),
      addslashes(variable_get('addthis_image_width', '125')),
      addslashes(variable_get('addthis_image_height', '16')),
      addslashes(variable_get('addthis_image_attributes', 'alt=""'))
    ));
  }
  else {//customized code to display big add this buttons via text decoration
    $options=explode(',',variable_get('addthis_options','expanded'));
    foreach($options as &$service){
        $service = trim($service);
        $service = '<a class="addthis_button_' . $service . '"><img src="/'. path_to_theme() . '/images/addthis/' . $service . '.png" alt="' . $service . '"/></a>';
  }
  return (sprintf('
      <!-- AddThis Button BEGIN -->
      <div class="addthis_toolbox">
      <h3 class="title">Share this!</h3>
      <div class="custom_images">
      %s
      </div>
      </div>
      <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?pub=%s"></script>
      <!-- AddThis Button END -->',
      implode("\n",$options),
      variable_get('addthis_username', '')
      ));
   } // end custom code
}
?>
