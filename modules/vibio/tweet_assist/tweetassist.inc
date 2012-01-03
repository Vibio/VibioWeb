<?php
define("TWEETASSIST_MAX_TITLE_LENGTH", 64);

function tweetassist_tweet_item($nid)
{
	if (!($node = node_load($nid)))
	{
		return false;
	}
	
	$prefix = module_exists("offer2buy") && $node->field_posting_type[0]['value'] == VIBIO_ITEM_TYPE_SELL ?	"selling my" : "check out my";
	$title = strlen($node->title) > TWEETASSIST_MAX_TITLE_LENGTH ?
		substr($node->title, 0, TWEETASSIST_MAX_TITLE_LENGTH)."..." : $node->title;
	$format = "$prefix \"$title\" on #vibio [url]";
	$url = url("node/{$node->nid}", array("absolute" => true));
	
	return tweet_to_twitter("Twitter", "icon", $format, $url);
}

function tweetassist_tweet_collection($cid)
{
	$collection = collection_load($cid);
	$title = strlen($collection->title) > TWEETASSIST_MAX_TITLE_LENGTH ?
		substr($collection->title, 0, TWEETASSIST_MAX_TITLE_LENGTH)."..." : $collection->title;
	$format = "check out my \"$title\" collection on #vibio [url]";
	$url = url("collections/{$cid}", array("absolute" => true));
	
	return tweet_to_twitter("Twitter", "icon", $format, $url);
}
?>