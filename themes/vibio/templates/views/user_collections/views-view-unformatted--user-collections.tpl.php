<?php
echo "<div class='collections_view_header'>";

if ($view->args[1] == PRIVACY_ONLYME)
{
	echo l(t("Create New Collection"), "collections/new", array("html" => true));
}
echo "</div>";

foreach ($view->result as $collection)
{
	echo theme("collection_list_item", $collection, $view->args[1]);
}
?>
