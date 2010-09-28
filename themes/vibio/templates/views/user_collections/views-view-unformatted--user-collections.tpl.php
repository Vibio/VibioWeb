<?php
echo "<div class='collections_view_header'>";

if ($view->args[1] == PRIVACY_ONLYME)
{
	echo l("<button>".t("Create New Collection")."</button>", "collections/new", array("html" => true))."<br />";
}
echo "</div>";

foreach ($view->result as $collection)
{
	echo theme("collection_list_item", $collection, $view->args[1]);
}
?>
