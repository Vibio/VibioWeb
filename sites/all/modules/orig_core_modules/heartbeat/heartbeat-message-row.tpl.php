<?php
// $Id: heartbeat-message-row.tpl.php,v 1.1.2.10 2010/02/21 22:45:26 stalski Exp $

/**
 * @file
 *   Template file for one row, rendered by heartbeat
 *
 * @var
 * - $message : after it was parsed by heartbeat (grouped)
 * - $time_info : information about the time of activity
 * - $class : extra classes to use on the row
 * - $attachments : attachment on the message id (of the grouped message)
 *
 * @remarks
 *   beat-item-<uaid> is necessairy. The beat item id is used to toggle
 *   visibility of the "number more" messages when grouping exceeded the
 *   maximum allowed grouped property.
 */

?>
<div class="heartbeat-message-block <?php print $message->message_id . ' ' . $zebra; ?>">
<?php
/* how do I pick the picture?
uid_target  might be interesting?
*/

?>
  <div class="beat-item" id="beat-item-<?php print $message->uaid ?>">

		<span class="heartbeat-pic-in-block"><?php
			global $user;	
			$notme = ($user->uid != $message->uid_target && $message->uid_target > 0 ) 
				? $message->uid_target : $message->uid;

			$u = user_load($notme);
			//Load the correct user picture
			!empty($u->picture) ? $user_picture = $u->picture : $user_picture = 'themes/vibio/images/icons/default_user_large.png';
			$alt = $u->name . '\'s Picture';
			$title = ''; // orname, if it’snot printed right below anyway
			$attributes = ‘’;
			print theme('imagecache', "tiny_profile_pic", $user_picture, $alt,$title, $attributes);
		?></span>

    <?php print $message->content['message']; ?>
    <?php if (!empty($message->content['time_info'])): ?>
    <span class="heartbeat_times"><?php print $message->content['time_info']; ?></span>
    <?php endif; ?>

    <div class="clear"></div>

    <?php if (!empty($message->content['widgets'])) : ?>
    <div class="heartbeat-attachments">
      <?php print $message->content['widgets']; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($message->content['buttons'])) :?>
    <div class="heartbeat-buttons">
      <?php print $message->content['buttons']; ?>
    </div>
    <?php endif; ?>

    <br class="clearfix" />

  </div>

</div>
