<?php
$slow_add = l("I have one!", "product/{$nid}/add-to-inventory");
$quick_add = l("I have one! (quick add)", "product/{$nid}/add-to-inventory/quick");

echo "
	$slow_add<br />
	$quick_add
";
?>