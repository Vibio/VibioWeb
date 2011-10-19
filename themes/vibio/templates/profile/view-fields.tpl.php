<?php
/* this produces your Profile "About me" page. */

$res = _profile_get_fields($fields_category);
$offset = strlen("profile_") - 1;
echo "<table class='profile_fields'>";
while ($row = db_fetch_object($res))
{
	$field = is_array($target_user->{$row->name}) ? implode("-", $target_user->{$row->name}) : $target_user->{$row->name};
	$list = implode("<br />", explode("\n", trim($field)));
	$list = $list ? $list : t("Not Specified");
	
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
