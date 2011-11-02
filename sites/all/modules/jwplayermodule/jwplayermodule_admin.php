<?php

require_once("framework/LongTailFramework.php");

?>

function updateHandler(button) {
  button.form.submit();
}

function selectionHandler(button) {
  var field = document.getElementById("<?php echo LONGTAIL_KEY . "player" ?>");
  field.setAttribute("value", button.id.replace("<?php echo LONGTAIL_KEY . "player_"; ?>", ""));
  var field = document.getElementById("<?php echo LONGTAIL_KEY . "new_player"; ?>");
  field.setAttribute("value", button.id.replace("<?php echo LONGTAIL_KEY . "player_"; ?>", ""));
}

function copyHandler(button) {
  var field = document.getElementById("<?php echo LONGTAIL_KEY . "player" ?>");
  field.setAttribute("value", button.id.replace("<?php echo LONGTAIL_KEY . "player_"; ?>", ""));
  var field = document.getElementById("<?php echo LONGTAIL_KEY . "new_player"; ?>");
  field.setAttribute("value", button.id.replace("<?php echo LONGTAIL_KEY . "player_"; ?>", "") + "_copy");
}

function deleteHandler(button) {
  var result = confirm("Are you sure wish to delete the Player?");
  if (result) {
    selectionHandler(button);
    return true;
  }
  return false;
}

function saveHandler(button) {
  var configs = eval('(' + '<?php echo json_encode(LongTailFramework::getConfigs()); ?>' + ')');
  var newVal = document.getElementById("<?php echo LONGTAIL_KEY . "new_player"; ?>");
  var configVal = document.getElementById("<?php echo LONGTAIL_KEY . "config"; ?>");
  if (configVal != null && newVal != null && configVal.value == newVal.value) {
    return true;
  }
  for (var config in configs) {
    if (newVal.value == configs[config]) {
      return confirm("A player with this name already exists and will be overwritten.  Would you like to continue?");
    }
  }
  return true;
}