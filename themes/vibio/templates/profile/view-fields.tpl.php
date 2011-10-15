<?php
/* this produces your Profile "About me" page. 
 *  For both "About me" and "About [somebody else]"
 */
$res = _profile_get_fields($fields_category);
$offset = strlen("profile_") - 1;

global $user;
//print_r( array('target' => $target_user) );
if ( $target_user->uid == $user->uid ) {
	$me = true;
}

echo "<table class='profile_fields'>";
while ($row = db_fetch_object($res))
{
	$field = is_array($target_user->{$row->name}) ? implode("-", $target_user->{$row->name}) : $target_user->{$row->name};
	$list = implode("<br />", explode("\n", trim($field)));

	// What do to if nothing specified?
	// v1.0,possibly put back
	// $list = $list ? $list : t("Not Specified");

if ( !$list ) {
		if ( $me ) {
			$list = t("Not Yet Specified");
		} else {
			break;  // next $row
		}
}

	echo "
		<tr>
			<td class='field_name'>
				{$row->title}:
			</td>
			<td class='field_val'>
				{$list}
			</td>
		</tr>
	";
}
echo "</table>";
?>
