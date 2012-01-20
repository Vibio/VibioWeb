<?php

foreach ($view->result as $collection)
{
  /*
   * Reformat the $collection object to match the standard node format
   * that collection.tpl.php anticipates. Additional values will be
   * populated in collection_preprocess_collection_list_item().
   * @todo: consider moving into a views preprocess for user_collections1
   */
  $collection->title = $collection->node_title;
  $collection->name = $collection->users_name;
  $collection->uid = $collection-users_uid;
  $collection->body = $collection->node_revisions_body;
	echo theme("collection_list_item", $collection, $view->args[1]);
}
?>
