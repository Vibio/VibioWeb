<?php
$sidebar_header=t("!user Collections",array("!user"=>$collection_owner_name));
$collections_link=l(t("View Complete List"),"user/{$collection_owner}/inventory");
echo "

<h1 id='collection-title'>Collection Detail<img id='collection-title-img' src='/themes/vibio/images/btn_arrow.png'/><span class='back-collection'><a href='/user/{$collection_owner}/collections'>Back to {$sidebar_header}</a></span></h1>
	<div id='collection_main'>
		{$collection_display}
	</div>
	<div id='collection_sidebar'>
		$add_item_link
		<h5>{$sidebar_header}</h5>
		{$collections_link}
		{$collection_sidebar_output}
	</div>
	<div class='clear'></div>
";
?>
<script>
	$('.section-collections .item-list').insertAfter('h1#collection-title');
</script>
