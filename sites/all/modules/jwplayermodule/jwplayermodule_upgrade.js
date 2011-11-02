var player, t;

$(document).ready(function() {
  t = setTimeout(playerNotReady, 2000);
});

function playerReady(object) {
  clearTimeout(t);
  var player = document.getElementById(object.id);
  $("#version_message-wrapper").text($("#version_message-wrapper").text() + "JW Player " + player.getConfig().version);
  $("#edit-ahah-version").val(player.getConfig().version);
  $("#edit-ahah-version").change();
}

function playerNotReady() {
  alert("Couldn't detect player version.");
}