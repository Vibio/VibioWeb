diff --git a/modules/search/search.module b/modules/search/search.module
index 35af52b..6f6732e 100644
--- a/modules/search/search.module
+++ b/modules/search/search.module
@@ -962,11 +962,9 @@ function do_search($keywords, $type, $join1 = '', $where1 = '1 = 1', $arguments1
 	global $pager_total;
 	$new_search_page_count = 20; // started as 10, and changing it not working
 	 $pager_total = $new_search_page_count;
-  $result = pager_query("$select $sort_parameters", $new_search_page_count, 0, $count_select, $arguments);
 	*/
-	
-	$result = pager_query("$select $sort_parameters", 10, 0, $count_select, $arguments);
 
+  $result = pager_query("$select $sort_parameters", $new_search_page_count, 0, $count_select, $arguments);
   $results = array();
   while ($item = db_fetch_object($result)) {
     $results[] = $item;
@@ -1170,13 +1168,11 @@ function search_data($keys = NULL, $type = 'node') {
 			//looks fine for users...dsm($results);
       if (isset($results) && is_array($results) && count($results)) {
         if (module_hook($type, 'search_page')) {
-					//die("Search is running"); //--> does not trigger when
-							// ?external_product_search=1 
+					//die("Search is running"); --> does not trigger for a normal search
           return module_invoke($type, 'search_page', $results);
         }
         else {
 					// this fires for users... die("theme me");
-					// And vibio_item ??? 	
           return theme('search_results', $results, $type);
         }
       }
diff --git a/modules/vibio/badges/badge.module b/modules/vibio/badges/badge.module
index 77b29e0..41496ad 100644
--- a/modules/vibio/badges/badge.module
+++ b/modules/vibio/badges/badge.module
@@ -194,13 +194,6 @@ function badge_theme()
 		),
 	);
 }
-//Prepares the badge image to be printed in the template file
-function template_preprocess_badge_list_badge(&$variables){
-	if(!empty($variables['badge']->image_src)){
-		$badge_image = theme('image', $variables['badge']->image_src, 'Badge Image', 'Badge Image', '');
-		$variables['badge_image'] = $badge_image;
-	}
-}
 
 function badge_collections_insert($collection)
 {
@@ -376,7 +369,7 @@ function badge_preprocess_page(&$vars)
 	$badges = array();
 	while ($badge = db_fetch_object($res))
 	{
-		$badge->image_src = 'sites/default/files/uploads/' . file_create_url($badge->image);
+		$badge->image_src = file_create_url($badge->image);
 		$badges[$badge->bid] = $badge;
 	}
 	
@@ -425,7 +418,7 @@ function badge_preprocess_badge_list($vars)
 	if ( $thebadges = badge_get_user_badges($vars['uid']) ) {
 		foreach ($thebadges as $bid => $badge)
 		{
-               		$badge->image_src = 'sites/default/files/uploads/' . file_create_url($badge->image);
+			$badge->image_src = file_create_url($badge->image);
 			$out .= theme("badge_list_badge", $badge);
 		}
 	} else {
diff --git a/modules/vibio/badges/badge.pages.inc b/modules/vibio/badges/badge.pages.inc
index 72a00a8..0475173 100644
--- a/modules/vibio/badges/badge.pages.inc
+++ b/modules/vibio/badges/badge.pages.inc
@@ -260,7 +260,6 @@ function badge_award_back_dated_badges_submit($form, &$state)
 function badge_unearned_list()
 {
 	global $user;
-	global $base_url;
 	
 	$sql = "SELECT *
 			FROM {badge}
@@ -276,7 +275,7 @@ function badge_unearned_list()
 	$out = "";
 	while ($badge = db_fetch_object($res))
 	{
-                $badge->image_src = $base_url . '/sites/default/files/uploads/' . file_create_url($badge->image);
+		$badge->image_src = file_create_url($badge->image);
 		$out .= theme("badge_alert_badge_single", $badge);
 	}
 	
@@ -300,8 +299,8 @@ function badge_unearned_list()
 
 function badge_get_info($badge)
 {
-	global $base_url;
-        $badge->image_src = $base_url . '/sites/default/files/uploads/' . file_create_url($badge->image);	
+	$badge->image_src = file_create_url($badge->image);
+	
 	$out = theme("badge_alert_badge_single", $badge);
 	$out = "
 		<div id='badge_alert_container'>
diff --git a/modules/vibio/overrides/overrides.module b/modules/vibio/overrides/overrides.module
index 4ac7750..4a32c0c 100644
--- a/modules/vibio/overrides/overrides.module
+++ b/modules/vibio/overrides/overrides.module
@@ -160,27 +160,12 @@ function overrides_user_register_submit($form, &$form_state) {
 	sites/uploads
  * Especially needed for imagecache.
 
-grep this too: filesystemhackery
-
-
 v1.0, browser settings:
 File System Path:
 /var/www/vibio/uploads
 File URL:
 http://vibio.com/sites/default/files/uploads
 
-
-The files directory is full of:
-select fid, filepath from drupal_files;
-
-and more recent:
-| 29965 | sites/default/files/uploads/products/B002BTE3JW.jpg                                                       |
-older style:
-| 20346 | /var/www/vibio/uploads/products/B0043RTAHU.jpg                                                            |
-Some images are passed in before this alter as:
- sites/all/themes/vibio/logo.png
-sites/default/files/uploads/imagecache/product_fixed_fluid_grid//var/www/vibio/uploads/products/B003DIHBFI.jpg  (imagecache makes this work!)
-/var/www/vibio/uploads/products/B0043RTAHU.jpg 
  */
 function overrides_file_url_alter(&$path)
 {
@@ -190,30 +175,12 @@ function overrides_file_url_alter(&$path)
 	//     /var/www/vibio/uploads/
 	// that needs to be removed
 
-	// This does not play well with file_create_url and full
-	//  http...etc path names.
-//die($path);
+
   $filepatharray = explode ( '/var/www/vibio/uploads/' , $path);
 	if ($filepatharray[1]) {
 		$path = $filepatharray[0] . $filepatharray[1];
-
-		/*
-		if ($filepatharray[0] ) {
-			// imagecache is happy
-			// http://staging.vibio.com/sites/default/files/uploads/imagecache/tiny_profile_pic/%252Fproducts/B001JIGTMU.jpg
-			$path = $filepatharray[0] . $filepatharray[1];
-//die("top" . $path);
-		} else {
-			// imagecache gets carried away, does this twice?
-			// staging.vibio.com/sites/default/files/uploads/imagecache/tiny_profile_pic/%252Fsites/default/files/uploads/products/B0042TADJ8.jpg
-			//$path = "/sites/default/files/uploads/" . $filepatharray[1];
-//die("bottom" . $path);
-		} 
-		*/
 	}
 
-
-	return $path; // not needed, but then can call this from elsewhere.
 /*  KEEP FOR REFERENCE, v1.0
 	if ($file_url && variable_get('file_downloads', FILE_DOWNLOADS_PUBLIC) == OVERRIDES_FILE_DOWNLOAD_DIRECT && strpos($path, $upload_dir) === 0)
 	{
diff --git a/modules/vibio/profile/profile_ext.module b/modules/vibio/profile/profile_ext.module
index d6edccf..5fbfebf 100644
--- a/modules/vibio/profile/profile_ext.module
+++ b/modules/vibio/profile/profile_ext.module
@@ -309,7 +309,7 @@ function profile_ext_display_block($u)
   $attributes = '';
 
 	if ( !$u->picture ) { /* deal with no pic loaded */
-		$u->picture = "themes/vibio/images/icons/default_user_large.png";
+		$u->picture = "/themes/vibio/images/icons/default_user_large.png";
 	}
 	//elsewhere: $pic = $u->picture ? file_create_url($u->picture) : "/themes/vibio/images/icons/default_user_large.png";
 	$pic = theme('imagecache', "little_profile_pic", $u->picture, $alt, $title, $attributes);
@@ -457,7 +457,7 @@ function profile_ext_preprocess_user_profile(&$vars)
 			$access = module_exists("privacy") ? privacy_get_access_level($uid) : 1;
 			$edit_me = '';
 			if ( $user->uid == $uid ) {
-				$edit_me = l('Edit Information', 'user/' . $uid . '/edit/About%20Me');
+				$edit_me = l('Edit "About Me"', 'user/' . $uid . '/edit/About%20Me');
 			}
 			$vars['sec_content'] = $edit_me . 
 				theme("profile_ext_profile_info", $uid, $access) .
diff --git a/modules/vibio/user_relationships_integration/uri.module b/modules/vibio/user_relationships_integration/uri.module
index cb9a53a..fce81f7 100644
--- a/modules/vibio/user_relationships_integration/uri.module
+++ b/modules/vibio/user_relationships_integration/uri.module
@@ -143,6 +143,9 @@ function uri_preprocess_uri_dashboard_notification(&$vars)
 		$rel_actions = "
 			<table>
 				<tr>
+					<td>
+						<img class='uri_edit_busy_indicator' src='/themes/vibio/images/ajax-loader.gif' />
+					</td>
 					<td>$approve</td>
 					<td>$ignore</td>						
 				</tr>
diff --git a/modules/vibio/vibio_item/collection/.collection.module.swp b/modules/vibio/vibio_item/collection/.collection.module.swp
new file mode 100644
index 0000000..a548519
Binary files /dev/null and b/modules/vibio/vibio_item/collection/.collection.module.swp differ
diff --git a/modules/vibio/vibio_item/collection/collection.module b/modules/vibio/vibio_item/collection/collection.module
index 8c8398d..4775a1f 100644
--- a/modules/vibio/vibio_item/collection/collection.module
+++ b/modules/vibio/vibio_item/collection/collection.module
@@ -119,22 +119,9 @@ function collection_theme(&$existing)
 			"template"	=> "templates/collections/random-collection",
 			"path"		=> drupal_get_path("theme", "vibio"),
 		),
-		"collection_image" => array(
-			"arguments"	=> array('cid' => null, 'imagecache' => 'full_profile_pic', 'access' => FALSE)
-		),
 	);
 }
 
-//This is a theme function to produce collection images. Currently uses collection_get_image; could completly replace
-//when we can refactor the code which uses collection_get_image.
-function theme_collection_image($cid, $imagecache, $access){
-                        $item_image = collection_get_image($cid, false, $access);   
-                        $alt = 'Collection Image';
-                        $title = 'Collection Image';
-                        $attributes = ‘’;
-                        return theme('imagecache', $imagecache, $item_image, $alt, $title, $attributes);
-}
-
 function collection_preprocess_collection_view_collection(&$vars)
 {
 	global $user;
@@ -188,12 +175,10 @@ function collection_preprocess_collection_list_item(&$vars)
 
 	//Add sharing buttons; requires addthis module
 	$share_html = "";
-	//show preview= 1 on the collections page, null on collection. We don't want to add sharing on collections page for right now.
-	if(module_exists('addthis') && empty($vars['show_preview'])){
+	if(module_exists('addthis')){
 		global $base_url;
 		$variables['title'] = $vars['collection']->collection_title;
 		$variables['url'] = $base_url . url('collections/'. $vars['collection']->cid);
-		
 		$share_html = theme('addthis_toolbox', $variables);
 	}
 /*
@@ -232,8 +217,7 @@ function collection_preprocess_collection_list_item_preview(&$vars)
 			$share_html .= theme("tweetassist_tweet", "node", $vars['item']->nid);
 	}
 */
-        //show preview= 1 on the collections page, null on collection. We don't want to add sharing on collections page for right now.
-        if(module_exists('addthis') && empty($vars['show_preview'])){
+	if(module_exists('addthis')){
 		global $base_url;
 		$variables['url'] = $base_url . url('node/'. $vars['item']->nid);
 		$variables['title'] = $vars['item']->node_title;
diff --git a/modules/vibio/vibio_item/product_catalog/product.inc b/modules/vibio/vibio_item/product_catalog/product.inc
index e77ac0d..b733962 100644
--- a/modules/vibio/vibio_item/product_catalog/product.inc
+++ b/modules/vibio/vibio_item/product_catalog/product.inc
@@ -125,11 +125,6 @@ function _product_from_item($item_nid)
 /* Does this get the product or item image, prefering one over the other? 
  * With imagecache, this would be real code?
  * Returns the URL of the image
-
- * What calls this?  Does it return only Additional Images?
- * If so, what is the point of default images?
-
-
  */ 
 function _product_get_image($nid, $is_product=false)
 {
@@ -155,7 +150,6 @@ function _product_get_image($nid, $is_product=false)
 
 /* stephen: wish we didn't call file_create_url and get full http://...
  *  paths on so much.   Eventually consider recoding whole site...
- * // Duplicate code with function file_uncreate_url and vibio_item_get_image
  */
 function file_uncreate_url($url) {
 	$pieces = explode("com/", $url);
@@ -171,17 +165,6 @@ function _product_default_image()
 	return "themes/vibio/images/icons/default_item_large.png";
 }
 
-/* Changed function
-   This function appears to produce only ADDITIONAL images,
-			because it is only called with second arg false, 
-			that I see.
-	 and to have long been producing pointless default images
-   when there was no additional image.  Called by
-	function product_vibio_item_images  (additional images)
-   and
-  "This section is for the page starting after "People who have...."
-		  which I'm not sure does anything now anyway (check up on that)
-*/
 function product_images($node, $get_main=false)
 {
 	if (is_numeric($node))
@@ -198,23 +181,16 @@ function product_images($node, $get_main=false)
 	{
 		$images[] = file_create_url($node->field_main_image[0]['filepath']);
 	}
-
+	
 	if (!empty($node->field_images))
-		// Somehow, this adds default images in it in some case
-		foreach ($node->field_images as $image)
+	foreach ($node->field_images as $image)
+	{
+		if (file_exists($image['filepath']))
 		{
-			/* Shit. We load default images and hold on to them.
-			   That probably occurs in the form, our database is probably
-				 full of these:
-			/var/www/vibio/uploads/imagefield_default_images/default_item_200x200.png
-				 pregmatch these out...
-			*/
-			if (file_exists($image['filepath'])
-				&& !preg_match('/default_item/', $image['filepath'])
-				)	{
-				$images[] = file_create_url($image['filepath']);
-			}
+			$images[] = file_create_url($image['filepath']);
 		}
+	}
+	
 	return $images;
 }
 
diff --git a/modules/vibio/vibio_item/product_catalog/product.module b/modules/vibio/vibio_item/product_catalog/product.module
index 70153a1..9152d9a 100644
--- a/modules/vibio/vibio_item/product_catalog/product.module
+++ b/modules/vibio/vibio_item/product_catalog/product.module
@@ -377,11 +377,7 @@ function product_preprocess_box(&$vars)
 	}
 }
 
-/* Also note template.php's vibio_preprocess_search_results ? */
-/* This function appears to fire on the first round of the search,
-    not the "only external items" search.  The slightly dizzying:
-    "If it's not an external search, add the external search results here"
- */
+/* first this goes to template.php's vibio_preprocess_search_results ? */
 function product_preprocess_search_results(&$vars)
 {
 	if ($vars['type'] == "vibio_item")
@@ -466,20 +462,16 @@ function product_have_want($nid)
 	return $search_links;
 }
 
-// answer this hook (perhaps other uses):
-//       $extra_images = module_invoke_all("vibio_item_images", $node->nid);
-// I believe this is really "additional" images only.  And only called for that?
 function product_vibio_item_images($item_nid)
 {
 	module_load_include("inc", "product");
-
-	// is this a serious error that should never happen?	be watchdogged?
+	
 	if (!($nid = _product_nid_from_item($item_nid)))
 	{
 		return array();
 	}
 	
-	return product_images($nid);  // an array of urls
+	return product_images($nid);
 }
 
 function product_set_autoadd($val=true)
diff --git a/modules/vibio/vibio_item/product_catalog/product.pages.php b/modules/vibio/vibio_item/product_catalog/product.pages.php
index 3651d32..b5d7908 100644
--- a/modules/vibio/vibio_item/product_catalog/product.pages.php
+++ b/modules/vibio/vibio_item/product_catalog/product.pages.php
@@ -7,17 +7,7 @@ exit(t("You already own this item!"));
 }
 module_load_include("php","product","product.forms");
 module_load_include("inc","product");
-
-$image= _product_get_image($product->nid,true);
-// Ugly hack to fix Have popup image
-//   Wait till get Ian approved measures for imagecache and then fix.
-// Something is weird with the above function.  I'm surprised this
-//  is the only thing breaking.  It needs to be looked at (this hack,
-//  that function.)
-//This was a quick-fix patch that can be erased in Dec11... fixed in overrides_file_url_alter instead of here:  $image = "/sites/default/files/uploads/" . $image; ... NOPE.  That fix breaks other things.  Here's the hack.  Imagecache
-// this soon.
-$image = "/sites/default/files/uploads/" . $image;
-
+$image=_product_get_image($product->nid,true);
 $out="  <div id='inventory_top'><span class='bold-text'>So you own this item and want to add it to your Collections?</span><br />
 Vibio is for people who possess a unique sense of style so<br />
 make it good!</div>
diff --git a/modules/vibio/vibio_item/vibio_item.inc.php b/modules/vibio/vibio_item/vibio_item.inc.php
index cf0f4a5..707be7a 100644
--- a/modules/vibio/vibio_item/vibio_item.inc.php
+++ b/modules/vibio/vibio_item/vibio_item.inc.php
@@ -33,8 +33,6 @@ function _vibio_item_search($keys)
 		if ((!variable_get("product_local_search", false) || $_GET['external_product_search']) && ($results = product_external_search($keys)))
 		{
 			$keys = _product_remove_options($keys);
-
-
 //dsm($results);
 // product_external_search is returning 10 results
 // it calls vibio_amazon_product_search($args)
@@ -330,18 +328,12 @@ function _vibio_item_get_image($nid)
  */
 function vibio_item_get_image($nid, $imagecachecode, $alt = null, $title = null, $attributes = null) { // for imagecache
 	$url = _vibio_item_get_image($nid); // could recode and clean this,
-//die($url);  eg themes/vibio/images/icons/default_item_large.png
 		// previous code takes what we want and runs file_create_url over it,
 		// then I undo that here sloppy fast works fine.
-		// Duplicate code with function file_uncreate_url
 	$pattern = "/sites/";
 	$p = preg_split ( $pattern, $url, 2 );
-	if ($p[1] ) {
-		$path ="sites/"  . $p[1];
-	} else {   // new 20111118
-		$path = $url;
-	}
-//return $url;
+	$path ="sites/"  . $p[1];
+
 	return theme('imagecache', $imagecachecode, $path, $alt, $title, $attrbutes);
 
 }
diff --git a/modules/vibio/vibio_item/vibio_item.module b/modules/vibio/vibio_item/vibio_item.module
index b94d99e..dc967f4 100644
--- a/modules/vibio/vibio_item/vibio_item.module
+++ b/modules/vibio/vibio_item/vibio_item.module
@@ -195,15 +195,8 @@ function vibio_item_preprocess_node(&$vars)
 			}
 			
 			$extra_images = module_invoke_all("vibio_item_images", $node->nid);
-
-
-	// Considering this change.
-      /* v1.11 change: extra images now only extra images, not all -stephen */
-      //$images = array_merge($extra_images, $images);
-
-
 			$images = array_merge($extra_images, $images);
-			$vars['item_extra_images'] = theme("vibio_item_images", $extra_images);
+			$vars['item_extra_images'] = theme("vibio_item_images", $images);
 		}
 	}
 }
diff --git a/robots.txt b/robots.txt
index c6742d8..3fbafd1 100644
--- a/robots.txt
+++ b/robots.txt
@@ -1,2 +1,2 @@
 User-Agent: *
-Disallow: /
+Disallow: /staging/
diff --git a/sites/all/modules/addthis/.addthis.module.swp b/sites/all/modules/addthis/.addthis.module.swp
new file mode 100644
index 0000000..f496a9c
Binary files /dev/null and b/sites/all/modules/addthis/.addthis.module.swp differ
diff --git a/sites/all/modules/addthis/addthis.module b/sites/all/modules/addthis/addthis.module
index e8204f1..2110bdb 100644
--- a/sites/all/modules/addthis/addthis.module
+++ b/sites/all/modules/addthis/addthis.module
@@ -298,15 +298,11 @@ function addthis_get_custom_services() {
 function addthis_add_default_js() {
   // Check if we've done this already.
   static $addthis_counter = 0;
+  print $addthis_counter;
   if ($addthis_counter++ > 0) {
     return $addthis_counter;
   }
-//experiment with this next, dsm & die($_SERVER['HTTP_X_REQUESTED_WITH']);
-// look for two varieties
-
-
-	//global $enough_addthis_counters = 1;
- //print "<p>...and in addthis_add_default"; 
+  
   // Get the base configuration settings array.
   $config = variable_get('addthis_config', array('ui_use_css' => TRUE, 'data_use_cookies' => TRUE,));
   
@@ -346,7 +342,7 @@ function addthis_add_default_js() {
   // used, go ahead and load the cached file.
   if (variable_get('addthis_cache_js', 0) && variable_get('file_downloads', 1) == 1 && $source = addthis_cache_js('http://s7.addthis.com/js/250/addthis_widget.js')) {
     // We need the async fragment in the src argument, so we can't use
-    // drupal_add_js() so far as I know. 
+    // drupal_add_js() so far as I know.
     drupal_set_html_head("<script type='text/javascript' src='" . base_path() . $source . "#async=1'></script>");
   }
   else {
diff --git a/themes/vibio/.template.php.swp b/themes/vibio/.template.php.swp
new file mode 100644
index 0000000..27808d2
Binary files /dev/null and b/themes/vibio/.template.php.swp differ
diff --git a/themes/vibio/css/collections.css b/themes/vibio/css/collections.css
index 65f7658..6729777 100644
--- a/themes/vibio/css/collections.css
+++ b/themes/vibio/css/collections.css
@@ -25,22 +25,9 @@
 }
 .collections_view_header {
 	position: absolute;
-	right: 22px;
-	top:-33px;
+	right: 22px
 }
 .collection_list_collection {
-	border-bottom: 1px solid #CCCCCC;
-	margin-top: 18px;
-}
-div.view-user-collections .collection_list_collection{
-	border:none	
-}
-.manage_collection_link{
-	float:right;
-	padding-right: 22px;
-}
-table.user-collection-preview{
-	margin:0
 }
 .collection_image {
 	float: left;
@@ -48,7 +35,7 @@ table.user-collection-preview{
 }
 .collection_summary {
 	float: left;
-	width: 420px;
+	width: 555px;
 	margin-bottom:10px;
 }
 .collection_summary h3 {
@@ -107,10 +94,9 @@ table.user-collection-preview{
 .collection_list_image img {
 	max-height: 100px;
 	border: 1px solid #CCCCCC;
-	max-width: 165px;
 }
 .collection_list_item_summary {
-    float: left;
+
 }
 tr.row-last .collection-preview-boxshadow{
 	border:none
@@ -129,19 +115,8 @@ tr.row-last .collection-preview-boxshadow{
 	bottom: 5px;
 	right: 0px;
 }
-.back-collection{
-	font-size:13px;
-	font-weight:normal;
-	color: #1691bd;
-	padding-left:15px;
-}
-#collection-title-img{
-	position: absolute;
-	top:26px;
-	left:395px;
-}
-.collection-user{
-	font-weight:bold
+.collection_list_item_summary span.item_price {
+	width: 60px;
 }
 .user-collection-preview h5, #collection_sidebar .collection_sidebar_collection h5 {
 	margin: 0;
@@ -307,12 +282,6 @@ div#block-collection-random_collection .collection_link{
 .addthis_default_style{
 	margin-top:10px;
 }
-/*Collection detil page small share buttons*/
-div.collection_list_item_summary .addthis_default_style{
-	margin: 52px 0 0 -3px;
-	width: 80px;
-}
-
 .share-text{
 	float:left;
 	clear:both;
diff --git a/themes/vibio/css/forms.css b/themes/vibio/css/forms.css
index a6b5a7a..d8b20df 100644
--- a/themes/vibio/css/forms.css
+++ b/themes/vibio/css/forms.css
@@ -30,7 +30,6 @@
 }
 .form-submit/* The submit button */ {
 }
-
 .container-inline div, .container-inline label/* Inline labels and form divs */ {
 	display: inline;
 }
@@ -374,6 +373,20 @@ td.active{
 tr.privatemsg-unread{
 	background: #ccecff;
 }
+/*Invite popoup*/
+form#uri-request-relationship-form{
+	margin: 30px 0 0 20px;
+	width:340px;
+}
+form#uri-request-relationship-form .description{
+	margin-bottom:20px
+}
+input#edit-uri-request-relationship-confirm{
+	margin-right: 5px;
+}
+input#edit-field-images-0-filefield-upload, input#edit-field-main-image-0-filefield-upload{
+	
+}
 /*User account settings*/
 form#user-profile-form{
 	font-size:13px;
diff --git a/themes/vibio/css/layout-fixed.css b/themes/vibio/css/layout-fixed.css
index ce00ec3..789cd17 100644
--- a/themes/vibio/css/layout-fixed.css
+++ b/themes/vibio/css/layout-fixed.css
@@ -141,12 +141,11 @@ body {
 .region-sidebar-first {
   float: left; /* LTR */
   width: 190px;
+  margin-left: 0; /* LTR */
+  margin-right: -200px; /* LTR */ /* Negative value of .region-sidebar-first's width + left margin. */
   padding: 0; /* DO NOT CHANGE. Add padding or margin to .region-sidebar-first .section. */
- margin: -3px -200px 0 0;
-}
-.not-logged-in .region-sidebar-first{
-	margin-top:0
 }
+
 .region-sidebar-first .section {
   padding: 0;
 }
diff --git a/themes/vibio/css/messages.css b/themes/vibio/css/messages.css
index aaa7e5a..f33339b 100644
--- a/themes/vibio/css/messages.css
+++ b/themes/vibio/css/messages.css
@@ -6,87 +6,61 @@
  *
  * Sensible styling for Drupal's error/warning/status messages.
  */
-div.messages {
-	position: relative;
-}
-div.messages div#close-message {
-	background:url(../images/btn_close.png) no-repeat;
-	text-indent:-9999px;
-	cursor: pointer;
-	position: absolute;
-	top: 4px;
-	right: 3px;
-	height: 20px;
-	width: 20px;
-	overflow: hidden;
-}
-div.notice div#close-message {
-	background-position: -40px 0;
-}
-div.error div#close-message {
-	background-position: -80px 0;
-}
-div.warning div#close-message {
-	background-position: -120px 0;
-}
-div.messages, div.status, div.warning, div.error, div.notice/* Important messages (status, warning, and error) for the user */ {
-	position: absolute;
-	min-height: 44px;
-	margin: 0;
-	padding: 15px 5px 15px 78px; /* LTR */
-	background-image: url(../images/message_icons.png);
-	background-repeat: no-repeat;
-	box-shadow: 0 2px 10px 0px rgba(0,0,0,.5);
-	-moz-box-shadow: 0 2px 10px 0px rgba(0,0,0,.5);
-	-webkit-box-shadow: 0 2px 10px 0 rgba(0,0,0,.5);
-	top: 29px;
-	right: 124px;
-	width: 417px;
-	z-index: 1000;
+
+
+div.messages,
+div.status,
+div.warning,
+div.error,
+div.notice /* Important messages (status, warning, and error) for the user */ {
+  position:absolute;
+  min-height: 44px;
+  margin: 0;
+  padding: 29px 5px 5px 78px; /* LTR */
+  background-image: url(../images/message_icons.png);
+  background-repeat: no-repeat;
+   box-shadow: 0 2px 10px 0px rgba(0,0,0,.5);
+  -moz-box-shadow: 0 2px 10px 0px rgba(0,0,0,.5);
+  -webkit-box-shadow: 0 2px 10px 0 rgba(0,0,0,.5);
+  top:29px;
+  right:124px;
+  width:417px;
+  z-index: 1000;
 }
-div.status/* Normal priority messages */ {
-	background-color: #CCECFF;
-	border: 2px solid #7AC8FF;
+
+div.status /* Normal priority messages */ {
+	background-color:#CCECFF;
+	border:2px solid #7AC8FF;
 	background-position: 0px 14px;
 }
-div.notice/* Normal priority messages */ {
-	background-color: #EAF7DA;
-	border: 2px solid #BEE78D;
+div.notice /* Normal priority messages */ {
+	background-color:#EAF7DA;
+	border:2px solid #BEE78D;
 	background-position: 0px -78px;
-	padding: 15px 5px 15px 78px;
+	padding:15px 5px 15px 78px;
 }
 
-div.warning, tr.warning {
-	color: #556720; /* Drupal core uses #220 */
-	background-color: #FFF7C2;
-	border: 2px solid #FFE180;
-	background-position: 0px -268px;
+div.warning,
+tr.warning {
+  color: #556720; /* Drupal core uses #220 */
+  background-color:#FFF7C2;
+  border:2px solid #FFE180;
+  background-position: 0px -268px;
 }
-div.error, tr.error {
-	color: #556720; /* Drupal core uses #220 */
-	background-color: #FFD9DA;
-	border: 2px solid #FFABAD;
-	background-position: 4px -174px;
+
+div.error,
+tr.error {
+  color: #556720; /* Drupal core uses #220 */
+  background-color:#FFD9DA;
+  border:2px solid #FFABAD;
+  background-position: 4px -174px;
 }
+
 div.messages ul {
-	margin: 0;
-	padding: 0;
+  margin: 0;
+  padding: 0;
 }
+
 div.messages ul li {
-	list-style-type: none;
-}
-/*Tooltip Not For Sale*/
-.not_for_sale {
-	position: relative
-}
-.not-sale-popup {
-	background: url(../images/white.png) no-repeat;
-	position: absolute;
-	top: -104px;
-	height: 70px;
-	font-size: 15px;
-	left: -33px;
-	padding: 33px 19px 10px;
-	width: 173px;
-	height: 109px;
-}
+  list-style-type: none;
+}
\ No newline at end of file
diff --git a/themes/vibio/css/pages.css b/themes/vibio/css/pages.css
index 24a9787..81cadb4 100644
--- a/themes/vibio/css/pages.css
+++ b/themes/vibio/css/pages.css
@@ -81,6 +81,9 @@ body.not-front, body.front{
 #name-and-slogan /* Wrapper for website name and slogan */ {
   float: left;
 }
+#name-and-slogan img{
+	width:132px;
+}
 
 h1#site-name,
 div#site-name /* The name of the website */ {
@@ -142,7 +145,7 @@ margin: 13px 0 0;
 padding: 0;
 }
 
-h1#page_title, h1#box_page_title, h1#collection-title{
+h1#page_title, h1#box_page_title{
 background:url("../images/long_border.png") no-repeat scroll 0 32px transparent;
 height:43px;
 margin: 3px 0 5px;
@@ -150,9 +153,9 @@ padding: 0;
 }
 
 .section_title{
-	font-size: 18px;
+	font-size: 22px;
 	color: #556270;
-	font-weight: 500;
+	font-weight: bold;
 }
 
 .section_sub_title a{
diff --git a/themes/vibio/css/search_page.css b/themes/vibio/css/search_page.css
index 8092368..10eca50 100644
--- a/themes/vibio/css/search_page.css
+++ b/themes/vibio/css/search_page.css
@@ -11,7 +11,7 @@
 #search_page_prefix {
 	text-align: right;
 	height: 20px;
-	overflow: none
+	overflow: none;
 }
 #search_page_prefix_additional {
 	position: relative;
@@ -200,7 +200,7 @@ button.add_connection:hover{
 	width:54px;
 	position: relative;
 	border:1px solid #CCCCCC;
-	margin:10px 0;
+	margin-top:10px;
 }
 .picture img{
 	width:44px;
diff --git a/themes/vibio/css/stephen.css b/themes/vibio/css/stephen.css
index b48da41..da784b6 100644
--- a/themes/vibio/css/stephen.css
+++ b/themes/vibio/css/stephen.css
@@ -310,7 +310,7 @@ div.beat-item a:hover{
 	color: #106F93;
 	font-weight: bold;
 	min-height: 255px;
-	margin: 50px -10px 0;
+	margin: 0 -10px;
 }
 .not-logged-in #block-block-5 {
 	display: none;
diff --git a/themes/vibio/css/uri.css b/themes/vibio/css/uri.css
index 464c176..c398d8d 100644
--- a/themes/vibio/css/uri.css
+++ b/themes/vibio/css/uri.css
@@ -1,138 +1,106 @@
-#uri-remove-relationship-form, #uri-pending-request-action-form, #uri-request-relationship-form {
-	margin: 20px 20px 0;
-	font-size: 13px;
-}
 img.uri_edit_busy_indicator {
 	display: none;
 }
+
 .uri_edit_elaboartion {
 	color: #9AA753;
 }
+
 .relationship_extra {
 	font-size: 11px;
 }
-/*relationship buttons*/
-#edit-uri-request-relationship-cancel, #edit-uri-request-relationship-confirm, div.profile_notifications_actions table tbody tr td a.approve button, div.profile_notifications_actions table tbody tr td a.disapprove button, input#edit-uri-remove-relationship-confirm, input#edit-uri-remove-relationship-cancel, #edit-uri-pending-request-confirm, #edit-uri-pending-request-cancel {
-	background: url(../images/btn_relationships.png) no-repeat;
-	height: 27px;
-	width: 79px;
-	border: 0;
-	text-indent: -9999px;
-	cursor: pointer;
-	margin-top: 10px;
-}
-/*Send Cancel buttons*/
-#edit-uri-request-relationship-confirm {
-	background-position: 0 -92px
-}
-#edit-uri-request-relationship-confirm:hover, #edit-uri-pending-request-confirm:hover {
-	background-position: 0 -122px;
-}
-#edit-uri-request-relationship-cancel {
-	background-position: -84px -92px;
-}
-#edit-uri-request-relationship-cancel:hover, #edit-uri-pending-request-cancel:hover {
-	background-position: -84px -122px;
-}
-/* Yes no buttons*/
-input#edit-uri-remove-relationship-confirm, #edit-uri-pending-request-confirm {
-	background-position: 0 -184px
-}
-input#edit-uri-remove-relationship-confirm:hover, #edit-uri-pending-request-confirm:hover {
-	background-position: 0 -214px
-}
-input#edit-uri-remove-relationship-cancel, #edit-uri-pending-request-cancel {
-	background-position: -86px -184px
-}
-input#edit-uri-remove-relationship-cancel:hover, #edit-uri-pending-request-cancel:hover {
-	background-position: -86px -214px
-}
-/*Add Friend Ignore buttons*/
-div.profile_notifications_actions table tbody tr td a.disapprove button {
-	background-position: 1px 0;
-}
-div.profile_notifications_actions table tbody tr td a.disapprove button:hover {
-	background-position: 1px -30px;
-}
-div.profile_notifications_actions table tbody tr td a.approve button {
-	background-position: -86px 0;
-	width: 111px;
-}
-div.profile_notifications_actions table tbody tr td a.approve button:hover {
-	background-position: -86px -30px;
-}
+
 #uri_elaborations_edit {
 	display: none;
 }
+
 #uri_elaborations_edit fieldset {
 	width: 480px;
 }
+
 #friends_tabs {
 }
+
 .potential_friend {
 	margin-bottom: 15px;
 }
+
 .potential_friend .picture {
 	float: left;
 }
+
 .potential_friend .potential_friend_summary {
 	float: left;
 	margin-left: 10px;
 	width: 350px;
 }
+
 .potential_friend .potential_friend_links {
 	float: right;
 }
+
 .potential_friend_links table {
 	margin: 0;
 }
+
 .uri_notification.profile_notification .profile_notifications_actions {
 	float: right;
 }
+
 .uri_notification.profile_notification .profile_notifications_summary {
 	width: 500px;
 }
-.user-relationships-listing-table {
+
+.user-relationships-listing-table{
 	margin-top: 7px;
 }
+
 .user-relationships-listing-table tr {
 	background: inherit;
 	border-bottom: 1px solid #CCCCCC;
 }
+
 .user-relationships-listing-table tr:first-child {
 	border-top: none;
 }
+
 /*All little pictures with a 5px border should go here*/
-.user-relationships-listing-table .picture, .potential_friend .picture, .profile_notification .picture {
+.user-relationships-listing-table .picture, .potential_friend .picture, .profile_notification .picture{
 	padding: 5px 0 0 5px;
 	background: #FFF;
 	border: 1px solid #CCCCCC;
 	height: 50px;
 	width: 50px;
 }
-.uri_notification {
+
+.uri_notification{
 	border-bottom: 1px solid #CCCCCC;
 }
-.user-relationships-listing-table .picture img, .potential_friend .picture img, .profile_notification .picture img {
-	height: 42px;
-	width: 42px;
+.user-relationships-listing-table .picture img, .potential_friend .picture img , .profile_notification .picture img{
+	height:42px;
+	width:42px;
 	border: 1px solid #CCCCCC;
 }
+
 .user-relationships-listing-table td {
 	vertical-align: top;
 	padding: 8px 0;
 }
+
 .user-relationships-listing-table .ur_friend_info {
 	padding-left: 5px;
 	width: 535px;
 }
+
 .ur_friend_remove {
 	width: 30px;
 }
+
 .ur_friend_remove img {
 	display: none;
-	margin-top: 10px;
+	margin-top:10px;
 }
+
 .ur_friend_busy {
 	width: 15px;
 }
diff --git a/themes/vibio/css/user.css b/themes/vibio/css/user.css
index df7c449..caeb184 100644
--- a/themes/vibio/css/user.css
+++ b/themes/vibio/css/user.css
@@ -200,9 +200,12 @@ table.profile_fields td {
 	padding: 0 15px 10px;
 }
 
-.profile_edit_link {
-	display:none
-}
+/* .profile_edit_link {
+	float: right;
+	display: none;
+	font-size: 10px;
+	color: red;
+} */
 
 #profile_about_me, #profile_demographics {
 	width: 690px;
diff --git a/themes/vibio/css/vibio_dialog.css b/themes/vibio/css/vibio_dialog.css
index c07de51..e758388 100644
--- a/themes/vibio/css/vibio_dialog.css
+++ b/themes/vibio/css/vibio_dialog.css
@@ -1,31 +1,3 @@
-
-/* trash these useless efforts to quick-theme HAVE popup once find real answer (here to prevent repeat efforts)
-
-.ui-widget-overlay {
-	margin-top: -200px;
-}
-/* moving the HAVE button popup higher... 
-   expect to return to this, fine tune it?  
-   Move it below when things settle
-	
-	Dammit... messes up Close button, the pieces here are separte
-
-.vibio_dialog_content {
-  position: relative;
-  top: -200px;
-}
-.ui-widget-overlay {
-  top: -800px;
-}
-.ui-widget {
-  margin-top: 400px;
-}
-*/
-
-
-
-
-
 #vibio_dialog {
 	/*padding: 10px;
 	background: #375670;*/
@@ -96,8 +68,6 @@ span.ui-icon{
 		Where is the entire file from, what dialog
 		was it expecting, to change automodal?
 		We do want the titlebar --stephen */
-		height:40px;
-		
 }
 
 .ui-dialog-titlebar img {
@@ -113,7 +83,7 @@ span.ui-icon{
 .vibio_dialog_close_button {
 	position: absolute;
 	right: -20px;
-	top: 20px;
+	top: -2px;
 }
 
 #vibio_dialog .views_message {
diff --git a/themes/vibio/images/btn_arrow.png b/themes/vibio/images/btn_arrow.png
deleted file mode 100755
index 8abebdc..0000000
Binary files a/themes/vibio/images/btn_arrow.png and /dev/null differ
diff --git a/themes/vibio/images/btn_close.png b/themes/vibio/images/btn_close.png
deleted file mode 100755
index 2596736..0000000
Binary files a/themes/vibio/images/btn_close.png and /dev/null differ
diff --git a/themes/vibio/images/btn_ignore_add.png b/themes/vibio/images/btn_ignore_add.png
deleted file mode 100644
index f9b713b..0000000
Binary files a/themes/vibio/images/btn_ignore_add.png and /dev/null differ
diff --git a/themes/vibio/images/btn_relationships.png b/themes/vibio/images/btn_relationships.png
deleted file mode 100644
index 994d4c4..0000000
Binary files a/themes/vibio/images/btn_relationships.png and /dev/null differ
diff --git a/themes/vibio/images/btn_send_cancel.png b/themes/vibio/images/btn_send_cancel.png
deleted file mode 100644
index 0b74327..0000000
Binary files a/themes/vibio/images/btn_send_cancel.png and /dev/null differ
diff --git a/themes/vibio/images/collections/box.png b/themes/vibio/images/collections/box.png
index bc53e91..bf6fdd8 100644
Binary files a/themes/vibio/images/collections/box.png and b/themes/vibio/images/collections/box.png differ
diff --git a/themes/vibio/images/no_collections_other.png b/themes/vibio/images/no_collections_other.png
deleted file mode 100644
index 37c69ac..0000000
Binary files a/themes/vibio/images/no_collections_other.png and /dev/null differ
diff --git a/themes/vibio/images/no_collections_you.png b/themes/vibio/images/no_collections_you.png
deleted file mode 100644
index 8cc92b2..0000000
Binary files a/themes/vibio/images/no_collections_you.png and /dev/null differ
diff --git a/themes/vibio/images/vibio-logo.png b/themes/vibio/images/vibio-logo.png
deleted file mode 100644
index 459f602..0000000
Binary files a/themes/vibio/images/vibio-logo.png and /dev/null differ
diff --git a/themes/vibio/images/white.png b/themes/vibio/images/white.png
deleted file mode 100755
index ced5603..0000000
Binary files a/themes/vibio/images/white.png and /dev/null differ
diff --git a/themes/vibio/js/dom-manipulate.js b/themes/vibio/js/dom-manipulate.js
index 95dfa2b..a271420 100644
--- a/themes/vibio/js/dom-manipulate.js
+++ b/themes/vibio/js/dom-manipulate.js
@@ -1,6 +1,6 @@
 /**
- * @author Craig Tockman - Reoder Elements up and down the DOM
- */
+* @author Craig Tockman - Reoder Elements up and down the DOM
+*/
 
 /*Login button position */
 $(window).load(function() {
@@ -61,16 +61,5 @@ $(document).ready(function() {
 		$.scrollTo(0, 2000);
 		return false;
 	});
-	/*Messages close button*/
-	$('div.messages').prepend('<div id="close-message">Close</div>');
-	/*Messages close function*/
-	$('div#close-message').click(function() {
-		$('div.messages').fadeOut();
-	});
-	/*Not for Sale Popup*/
-	$('div.product_extra_data div.not_for_sale').hover(function() {
-		$(this).prepend('<div class="not-sale-popup">Many items on Vibio are not for sale. <a href="/faq#why">Find out why</a>.</div>');
-	}, function() {
-		$('.not-sale-popup').remove();
-	});
+	$('.section-collections .item-list').insertAfter('h1#page_title');
 });
diff --git a/themes/vibio/js/fixed-scroll.js b/themes/vibio/js/fixed-scroll.js
index c78e112..c18761e 100644
--- a/themes/vibio/js/fixed-scroll.js
+++ b/themes/vibio/js/fixed-scroll.js
@@ -1,8 +1,8 @@
 /*fixed scroll*/
 $(document).ready(function() {
     $('#block-block-5').scrollToFixed();
-    $('#block-block-5').bind('fixed', function() { $(this).css('margin', '10px 0px 0'); });
-    $('#block-block-5').bind('unfixed', function() { $(this).css('margin', '50px -10px 0'); });
+    $('#block-block-5').bind('fixed', function() { $(this).css('margin', '0px 0px'); });
+    $('#block-block-5').bind('unfixed', function() { $(this).css('margin', '0px -10px'); });
     //$('.view.view-flag-featured .view-footer').scrollToFixed();
 });
 /*scroll to top*/
diff --git a/themes/vibio/js/jquery.imagesloaded.js b/themes/vibio/js/jquery.imagesloaded.js
deleted file mode 100644
index 6d8ecf9..0000000
--- a/themes/vibio/js/jquery.imagesloaded.js
+++ /dev/null
@@ -1,54 +0,0 @@
-/*!
- * jQuery imagesLoaded plugin v1.0.4
- * http://github.com/desandro/imagesloaded
- *
- * MIT License. by Paul Irish et al.
- */
-
-(function($, undefined) {
-
-  // $('#my-container').imagesLoaded(myFunction)
-  // or
-  // $('img').imagesLoaded(myFunction)
-
-  // execute a callback when all images have loaded.
-  // needed because .load() doesn't work on cached images
-
-  // callback function gets image collection as argument
-  //  `this` is the container
-
-  $.fn.imagesLoaded = function( callback ) {
-    var $this = this,
-        $images = $this.find('img').add( $this.filter('img') ),
-        len = $images.length,
-        blank = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
-
-    function triggerCallback() {
-      callback.call( $this, $images );
-    }
-
-    function imgLoaded( event ) {
-      if ( --len <= 0 && event.target.src !== blank ){
-        setTimeout( triggerCallback );
-        $images.unbind( 'load error', imgLoaded );
-      }
-    }
-
-    if ( !len ) {
-      triggerCallback();
-    }
-
-    $images.bind( 'load error',  imgLoaded ).each( function() {
-      // cached images don't fire load sometimes, so we reset src.
-      if (this.complete || typeof this.complete === "undefined"){
-        var src = this.src;
-        // webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
-        // data uri bypasses webkit log warning (thx doug jones)
-        this.src = blank;
-        this.src = src;
-      }
-    });
-
-    return $this;
-  };
-})(jQuery);
diff --git a/themes/vibio/js/scroll-init.js b/themes/vibio/js/scroll-init.js
index b39bfb7..71b7493 100644
--- a/themes/vibio/js/scroll-init.js
+++ b/themes/vibio/js/scroll-init.js
@@ -1,69 +1,60 @@
-/* This code is BELIEVED to work on the Featured page (yeah!) and the Search page (boo! -- fixed below)
- And maybe everywhere else on the site.
- It hides the pager, and almost certainly many more things that bug fixers can document
- when they break things.
- It appears to call/coordinate the masonry javascript.
+/* This code is BELIEVED to work on the Featured page (yeah!) and the Search page (boo!)
+	 And maybe everywhere else on the site. 
+   It hides the pager, and almost certainly many more things that bug fixers can document 
+   when they break things.
+   It appears to call/coordinate the masonry javascript.  
  */
 
-$(function() {
+$(function(){
 
-	// encapsulate this run-away script, Features page only
-	if($("body").hasClass("front") && $("body").hasClass("logged-in")) {
+// encapsulate this run-away script, Features page only
+if($("body").hasClass("front") && $("body").hasClass("logged-in") ) {
 
-		if($(".pager-last").find('a').attr('href') != undefined) {
+	 if($(".pager-last").find('a').attr('href') != undefined){
 			//Get the number of pages from the Views Pager (Use the full pager, it will be hidden with .infinitescroll() anyway)
-			lastPageHref = $(".pager-last").find('a').attr('href').toString();
-			lastPageHref = lastPageHref.split("=");
-			numOfPages = parseInt(lastPageHref[1]);
-            $('.view.view-flag-featured .view-content').imagesLoaded(function(){
-      		$('.view.view-flag-featured .view-content').masonry({
-        	columnWidth : 180,
-        	itemSelector : '.views-row:visible'
-     	 	});
-	    });
+			 lastPageHref = $(".pager-last").find('a').attr('href').toString(); 
+				 lastPageHref = lastPageHref.split("=");
+				 numOfPages = parseInt(lastPageHref[1]);
 
+			$('.view.view-flag-featured .view-content').masonry({
+					columnWidth: 180, 
+					itemSelector: '.views-row:visible' 
+			});
 
 			$('.view.view-flag-featured .view-content').infinitescroll({
-				navSelector : ".pager", // selector for the paged navigation
-				nextSelector : ".pager .pager-next a", // selector for the NEXT link (to page 2)
-				itemSelector : ".views-row", // selector for all items you'll retrieve
-				loadingImg : '/themes/vibio/images/barloader.gif',
-				donetext : "No more pages to load.",
-				debug : false,
-				pathParse : '?page=1',
-				animate : false,
-				pages : numOfPages, //NEW OPTION: number of pages in the Views Pager
-				errorCallback : function() {
-					$('#infscr-loading').animate({
-						opacity : .8
-					}, 2000).fadeOut('normal');
-					//fade out the error message after 2 seconds
+					navSelector  : ".pager",    // selector for the paged navigation 
+					nextSelector : ".pager .pager-next a",    // selector for the NEXT link (to page 2)
+					itemSelector : ".views-row",       // selector for all items you'll retrieve
+					loadingImg   : '/themes/vibio/images/barloader.gif',
+					donetext     : "No more pages to load.",
+					debug        : false,
+					pathParse    : '?page=1',
+					animate      : false, 
+					pages        : numOfPages, //NEW OPTION: number of pages in the Views Pager
+					errorCallback: function() { $('#infscr-loading').animate({opacity: .8},2000).fadeOut('normal');//fade out the error message after 2 seconds
 
 				}
 			},
 			// call masonry as a callback.
+			function() { $('.view.view-flag-featured .view-content').masonry({ appendedContent: $(this) }); 
+			$('div.views-field.views-field-nid').hide();
+			$('div.views-field.views-field-field-main-image-fid').hover(function() {
+				$(this).next().show();
+			}, 
+			function() {
+				$(this).next().hide();
+			});
+			$('div.views-field.views-field-nid').hover(function() {
+				$(this).show();
+				$(this).prev().addClass('hover-shadow');
+			}, 
 			function() {
-        var existingMaterial = $(this);
-				$('.view.view-flag-featured .view-content').imagesLoaded(function(){
-          $('.view.view-flag-featured .view-content').masonry({
-            appendedContent : existingMaterial
-          });
-        });
-				$('div.views-field.views-field-nid').hide();
-				$('div.views-field.views-field-field-main-image-fid').hover(function() {
-					$(this).next().show();
-				}, function() {
-					$(this).next().hide();
-				});
-				$('div.views-field.views-field-nid').hover(function() {
-					$(this).show();
-					$(this).prev().addClass('hover-shadow');
-				}, function() {
-					$(this).hide();
-					$(this).prev().removeClass('hover-shadow');
-				});
+				$(this).hide();
+				$(this).prev().removeClass('hover-shadow');
 			});
-		}
+		 }
+		);
+	}
 
 	}
 
diff --git a/themes/vibio/js/user.js b/themes/vibio/js/user.js
index 091f605..fe194f2 100644
--- a/themes/vibio/js/user.js
+++ b/themes/vibio/js/user.js
@@ -11,16 +11,16 @@ $(document).ready(function()
 	
 	$("#profile_user_tabs").tabs();
 	
-	//$(".profile_editable").hover(
-		//function()
-		//{
-			//$(this).find(".profile_edit_link").fadeIn(200);
-		//},
-		//function()
-		//{
-			//$(this).find(".profile_edit_link").fadeOut(200);
-		//}
-	//);
+	$(".profile_editable").hover(
+		function()
+		{
+			$(this).find(".profile_edit_link").fadeIn(200);
+		},
+		function()
+		{
+			$(this).find(".profile_edit_link").fadeOut(200);
+		}
+	);
 	
 	$("#profile_change_picture a").click(function()
 	{
diff --git a/themes/vibio/template.php b/themes/vibio/template.php
index 308ffb8..cc64bef 100644
--- a/themes/vibio/template.php
+++ b/themes/vibio/template.php
@@ -10,12 +10,7 @@
  * @return <type> 
  */
 function vibio_addthis_toolbox($variables) {
-  global $addthis_counter;
-  empty($addthis_counter) ? $addthis_counter = 0 : '';
-  if($addthis_counter < 1){
-    addthis_add_default_js();
-    $addthis_counter++;
-  }
+  addthis_add_default_js();
   $url = $variables['url'];
   if(module_exists('shorten')){
     $abbreviated_url = shorten_url($url, 'TinyURL');
@@ -47,12 +42,7 @@ function vibio_addthis_toolbox($variables) {
  * @return <type> 
  */
 function vibio_addthis_button($variables){
-  global $addthis_counter;
-  empty($addthis_counter) ? $addthis_counter = 0 : '';
-  if($addthis_counter < 1){
-    addthis_add_default_js();
-    $addthis_counter++; 
-  }
+  addthis_add_default_js();
   $title = $variables['title'];
   if($node = $variables['node']){
   $description = $node->body;
@@ -112,8 +102,9 @@ function vibio_filter_tips($tips, $long = FALSE, $extra = '') {
  * filter/tips
  */
 function vibio_filter_tips_more_info() {
-  return '<p>If you want fancy formatting options, read ' . l(t('this stuff.'), "filter/tips", array('attributes' => 
-			array('class' => "automodal"))) . '</p>';
+  return 'If you want fancy formatting options, read ' .
+		l(t('this stuff.'), "filter/tips", array('attributes' => 
+			array('class' => "automodal"))) . '<p>';
 			// at least Private Messages looks bad without <p> at end
 }
 
@@ -130,47 +121,13 @@ function vibio_filter_tips_more_info() {
 
  */
 function vibio_preprocess_search_results(&$variables) {
-	/* notes and research:
- 	dsm(array("variables" => $variables));
-	// If it's owned ($results under some circumstances),
-	// $user has a link to the user (html, boolean)
-	// $node->uid has the owner's uid
-	//*/
-
+	//dsm($variables);
 	if ( $variables['type'] != 'vibio_item' ) {  return; }
 			// this should fix search_type_not_lost --- ToDo!!
-
-
-	// Is it mine?   Could go in preprocess_search_result (singular)?
-	global $user;
-	foreach ( $variables['results'] as $result) {
-		$item = $result['node']; // !!! eyeball: will this fire inappropriately
-				// for products?  Need to check.  Not themed yet, so who knows.
-		if ( $user->uid == $item->uid ) {
-			$item->thisismine = true;
-		}
-	}
-
-
-
-	// (May change all this if move to masonry javascript) Move
-	//	things into 4 columns		
+		
   $variables['search_results'] = '';
 	$zebra = 1;
-	// Combine $variables['results'] and $variables['unthemed_other_results']
-	// It's not consistent what these are: results is both local and Amazon,
-	//  if results is local, other is Amazon.  I think. More varieties
-	//  possible.  Keep testing or rebuild from scratch.
-	if(!IsSet($variables['unthemed_other_results'])) {
-		$all_results = $variables['results'];
-	} else {  // hey, if this was a sane one-pass search, we'd need to deal
-						// with only other results, but I don't think that's a case yet?
-		$all_results = array_merge($variables['results'], $variables['unthemed_other_results']);
-	}
-//die("did we get here");
-//dsm($variables['results']);
-//dsm($all_results);
-  foreach ($all_results as $result) {
+  foreach ($variables['results'] as $result) {
 		$z = $result['zebra'] = $zebra%4;
 		$zebra++;
     $variables["search_results_$z"] .= theme('search_result', $result, $variables['type']);
@@ -271,7 +228,7 @@ Is it really desired for anything ever to go to parent zen theme?
 }
 */
 
-function customy_break_Drupal_css_for_no_known_reason_vibio_preprocess_page(&$vars, $hook)
+function vibio_preprocess_page(&$vars, $hook)
 {
 	zen_preprocess_page($vars, $hook);
 	
@@ -310,7 +267,7 @@ function phptemplate_user_relationships_pending_request_approve_link($uid, $rid)
 		"relationships/{$uid}/{$rid}/approve",
 		array(
 			"attributes"=> array(
-				"class"	=> "uri_popup_link approve",
+				"class"	=> "uri_popup_link",
 			),
 			"html"		=> true,
 		)
@@ -324,7 +281,7 @@ function phptemplate_user_relationships_pending_request_disapprove_link($uid, $r
 		"relationships/{$uid}/{$rid}/disapprove",
 		array(
 			"attributes"=> array(
-				"class"	=> "uri_popup_link disapprove",
+				"class"	=> "uri_popup_link",
 			),
 			"html"		=> true,
 		)
diff --git a/themes/vibio/templates/badge/list-badge.tpl.php b/themes/vibio/templates/badge/list-badge.tpl.php
index 070c6e2..a66cd3f 100644
--- a/themes/vibio/templates/badge/list-badge.tpl.php
+++ b/themes/vibio/templates/badge/list-badge.tpl.php
@@ -2,7 +2,7 @@
 echo "
 <div class='badge_list_badge'>
 		<div class='badge_image_container'>
-			{$badge_image}
+			<img class='badge_image' src='{$badge->image_src}' />
 		</div>
 		<div class='badge_info'>
 			<h1>{$badge->title}</h1>
diff --git a/themes/vibio/templates/collections/collection-view.tpl.php b/themes/vibio/templates/collections/collection-view.tpl.php
index 2becf98..0397c25 100644
--- a/themes/vibio/templates/collections/collection-view.tpl.php
+++ b/themes/vibio/templates/collections/collection-view.tpl.php
@@ -1,14 +1,8 @@
-<style type="text/css">
-	h1#page_title {
-		display: none
-	}
-</style>
 <?php
-$sidebar_header=t("!user Collections",array("!user"=>$collection_owner_name));
-$collections_link=l(t("View Complete List"),"user/{$collection_owner}/inventory");
-echo "
+$sidebar_header = t("!user Collections", array("!user" => $collection_owner_name));
+$collections_link = l(t("View Complete List"), "user/{$collection_owner}/inventory");
 
-<h1 id='collection-title'>Collection Detail<img id='collection-title-img' src='/themes/vibio/images/btn_arrow.png'/><span class='back-collection'><a href='/user/{$collection_owner}/collections'>Back to {$sidebar_header}</a></span></h1>
+echo "
 	<div id='collection_main'>
 		{$collection_display}
 	</div>
@@ -20,7 +14,4 @@ echo "
 	</div>
 	<div class='clear'></div>
 ";
-?>
-<script>
-	$('.section-collections .item-list').insertAfter('h1#collection-title');
-</script>
\ No newline at end of file
+?>
\ No newline at end of file
diff --git a/themes/vibio/templates/collections/collection.tpl.php b/themes/vibio/templates/collections/collection.tpl.php
index 61ee674..2fe2103 100644
--- a/themes/vibio/templates/collections/collection.tpl.php
+++ b/themes/vibio/templates/collections/collection.tpl.php
@@ -1,9 +1,8 @@
 <?php
 $collection_url = url("collections/{$collection->cid}");
-$manage_link = $collection->is_owner ? l(t("Edit Collection"), "collections/manage/{$collection->cid}") : "";
+$manage_link = $collection->is_owner ? l(t("Rename Collection"), "collections/manage/{$collection->cid}") : "";
 $total_items = t("!count items", array("!count" => $collection->total_items));
 $expand = t("View this collection");
-$collection->user = l($collection->user_name, "user/{$collection->uid}");
 
 if ($show_preview)
 {
@@ -18,13 +17,11 @@ if ($show_preview)
 			</div>
 		</div>
 	";
-	$collection->share_html = null;
 }
 $collection_image = theme('imagecache', 'collection_fixed_fluid_grid_77', $collection->image, $collection->collection_description, $collection->collection_description, '');
 
 echo "
 	<div class='collection_list_collection' id='collection_{$collection->cid}'>
-		<div class='manage_collection_link'>$manage_link</div>
 		<div class='collection_image'>
 			<a href='$collection_url'>
 				{$collection_image}
@@ -35,9 +32,9 @@ echo "
 				<h3>{$collection->collection_title}</h3>
 			</a>
 			<div class='collection_item_count'>Number of items: $total_items</div>
-			<div class='collection-user'>Collection creator: {$collection->user}</div>
-			<p>{$collection->collection_description}</p>
+			<div class='manage_collection_link'>$manage_link</div>
 			<div class='clear'></div>
+			<p>{$collection->collection_description}</p>
 			{$collection->collection_categories}
 			{$collection->share_html}
 		</div>
diff --git a/themes/vibio/templates/node-product.tpl.php b/themes/vibio/templates/node-product.tpl.php
index 44baf0e..51d8891 100644
--- a/themes/vibio/templates/node-product.tpl.php
+++ b/themes/vibio/templates/node-product.tpl.php
@@ -24,15 +24,6 @@ if(isset($product_image)){
 if (isset($image))
 {
 	$image = file_uncreate_url($image);
-	//Looks like: pocket watch.jpg  
-	// filesystemhackery 20111121
-	if ( !preg_match('/sites\/default\/files/', $image) ) {
-		$urlimage = "/sites/default/files/uploads/" . $image;	 // new: 20111121 (erase note)
-	} else {
-//die($image);
-		$urlimage = "/$image"; // hey look, terrible, recode!
-	}
-		
 	$pattern = "/^\//";
 	$image = preg_replace($pattern,"",$image);
         $alt = 'Product Image';
@@ -42,7 +33,7 @@ if (isset($image))
                 $image, 
                 $alt, $imgtitle, $attributes);
         $image = "
-                <a href='$urlimage' rel='prettyphoto[item_image]'>
+                <a href='$image' rel='prettyphoto[item_image]'>
                         $imagecached
                 </a>
         ";
diff --git a/themes/vibio/templates/profile/display-block.tpl.php b/themes/vibio/templates/profile/display-block.tpl.php
index a362a78..d8a7292 100644
--- a/themes/vibio/templates/profile/display-block.tpl.php
+++ b/themes/vibio/templates/profile/display-block.tpl.php
@@ -55,7 +55,7 @@ $buying_menu = l("Offers from you", "buying", array(
 );
 // !!! Notifications becoming invitations?
 $notifications_menu =
-	l("Notifications", "notifications", array(
+	l("Invitations", "notifications", array(
 	attributes => array(
 		'title' => 'Connect with other Vibio Users',
 		'class' => $taint_path == 'notifications' ? " active" : '',
diff --git a/themes/vibio/templates/search/search-result-vibio_item.tpl.php b/themes/vibio/templates/search/search-result-vibio_item.tpl.php
index ea5c3aa..12e6abb 100644
--- a/themes/vibio/templates/search/search-result-vibio_item.tpl.php
+++ b/themes/vibio/templates/search/search-result-vibio_item.tpl.php
@@ -2,13 +2,8 @@
 $node = $result[node]; //!!! Different from node-view-flag_featured.tpl.php
 		// !!! Also, $search_links is set here, not there.
 $flag = flag_create_link('feature', $node->nid);
-//dsm($node->field_main_image[0]['filepath']);
 $img = theme('imagecache', 'product_fixed_width', $node->field_main_image[0]['filepath']);
 
-// $mine should be true if it's yours,  true or null
-// Fires for PRODUCTS (merely searched products too)
-//if ($node->thisismine) { $minetext = "It's mine all mine"; }
-
 // $search_links are have and want buttons
 
 // the views-fluidgrid-item class was drive-by-theming, senseless. 
@@ -23,7 +18,6 @@ $rectangle = "
   			 $img
   		</a>
   	</div>	
-			$minetext
   		<div class='search-links'>$search_links</div>
   		<div class='views-field-title'>
   			<a class='item-title' href='$url'>$title</a>
diff --git a/themes/vibio/templates/search/search-results-vibio_item.tpl.php b/themes/vibio/templates/search/search-results-vibio_item.tpl.php
index a18d33e..b4a9287 100644
--- a/themes/vibio/templates/search/search-results-vibio_item.tpl.php
+++ b/themes/vibio/templates/search/search-results-vibio_item.tpl.php
@@ -27,12 +27,12 @@ echo $pager;
 
  */
 echo " 
-$search_results
+$other_results
 	<!-- table class='search-results $type' -->
+		<div class='search-col 1'>$search_results_0</div>
 		<div class='search-col 2'>$search_results_1</div>
 		<div class='search-col 3'>$search_results_2</div>
 		<div class='search-col 4'>$search_results_3</div>
-		<div class='search-col 1'>$search_results_0</div>
 		<div class='clearfix'></div>
 	<!-- /table -->
 ";
diff --git a/themes/vibio/templates/vibio_item/additional-images.tpl.php b/themes/vibio/templates/vibio_item/additional-images.tpl.php
index ea7f13c..7a69871 100644
--- a/themes/vibio/templates/vibio_item/additional-images.tpl.php
+++ b/themes/vibio/templates/vibio_item/additional-images.tpl.php
@@ -1,39 +1,14 @@
 <?php
-/* 
-Notes for themer: change 'tiny_profile_pic' to any other imagecache
-to change the size
-
-Notes for devel finding code:
-20111118: produced here: modules/vibio/vibio_item/product_catalog/product.module
-function product_vibio_item_images($item_nid)
-calls
-modules/vibio/vibio_item/product_catalog/product.inc:function product_images($node, $get_main=false)    returns images as urls
-
- */
 if (!empty($images))
 {
 	$header = t("Additional Images");
 	$image_html = "";
-	$attributes = array(
-		'class'=>'node_view_item_image'
-		);
-	// images come here as urls
+	
 	foreach ($images as $image)
 	{
-		$imagecached = theme('imagecache', "tiny_profile_pic", $image, "Additional images of this item", "", $attributes) ;
-
-		// filesystemhackery 20111121 -> not sure this perfect, where does it fire?:
-		if ( !preg_grep('sites/default/files', $image) ) {
-			die("bad image");
-			$image_pretty = "/sites/default/files/uploads/" . $image; // crap fix hack
-		} else {
-			$image_pretty = $image;
-			die($image);
-		}
-	
 		$image_html .= "
-			<a href='$image_pretty' rel='prettyphoto[item_image]'>
-				$imagecached
+			<a href='$image' rel='prettyphoto[item_image]'>
+				<img src='$image' class='node_view_item_image' />
 			</a>";
 	}
 	
@@ -42,4 +17,4 @@ if (!empty($images))
 		$image_html
 	";
 }
-?>
+?>
\ No newline at end of file
diff --git a/themes/vibio/templates/views/views-view-field--message.tpl.php b/themes/vibio/templates/views/views-view-field--message.tpl.php
index c4eb4b5..7997764 100644
--- a/themes/vibio/templates/views/views-view-field--message.tpl.php
+++ b/themes/vibio/templates/views/views-view-field--message.tpl.php
@@ -20,7 +20,7 @@ if ($row->nid)
 	{
 		module_load_include("inc", "collection");
 		$access = privacy_get_access_level($row->actor->uid); // this works because other users viewing this view will have the same access level as the current user
-		$image = theme('collection_image', $row->nid, 'tiny_profile_pic', $access);
+		$image = collection_get_image($row->nid, false, $access);
 	}
 	else
 	{
@@ -36,7 +36,7 @@ if ($row->nid)
 }
 
 /* Neither friends nor badges print the second image.  Seems like they
- *  sure should.  Begun work here, realized its a feature not a bug/priority,
+ *  sure should.  Begun work here, realized it's a feature not a bug/priority,
  *  code snippets should get you started...
  */
 //if   [message_id] => heartbeat_become_friends,  $row->uid_target
diff --git a/themes/vibio/vibio-logo.png b/themes/vibio/vibio-logo.png
index 459f602..6286f79 100644
Binary files a/themes/vibio/vibio-logo.png and b/themes/vibio/vibio-logo.png differ
diff --git a/themes/vibio/vibio.info b/themes/vibio/vibio.info
index c1f3794..a385ca5 100644
--- a/themes/vibio/vibio.info
+++ b/themes/vibio/vibio.info
@@ -6,26 +6,19 @@ base theme = zen
 
    ; Stephen making quick edits in various places, better
    ;  to separate my work than integrate with each file here 
-stylesheets[all][]   = css/badge.css
-stylesheets[all][]   = css/blocks.css
-stylesheets[all][]   = css/collections.css
-stylesheets[all][]   = css/comments.css
-stylesheets[all][]   = css/facebook.css
-stylesheets[all][]   = css/forms.css
+stylesheets[all][]   = css/stephen.css
+stylesheets[all][]   = css/travis.css
 stylesheets[all][]   = css/html-reset.css
 stylesheets[all][]   = css/layout-fixed.css
+stylesheets[all][]   = css/tabs.css
 stylesheets[all][]   = css/messages.css
-stylesheets[all][]   = css/nodes.css
-stylesheets[all][]   = css/offer.css
-stylesheets[all][]   = css/offer2buy.css
 stylesheets[all][]   = css/pages.css
-stylesheets[all][]   = css/profile.css
-stylesheets[all][]   = css/slideshow.css
-stylesheets[all][]   = css/stephen.css
-stylesheets[all][]   = css/tabs.css
-stylesheets[all][]   = css/travis.css
-stylesheets[all][]   = css/uri.css
 stylesheets[all][]   = css/views-styles.css
+stylesheets[all][]   = css/nodes.css
+stylesheets[all][]   = css/comments.css
+stylesheets[all][]   = css/forms.css
+stylesheets[all][]   = css/blocks.css
+stylesheets[all][]   = css/slideshow.css
 
 
   ; Set the conditional stylesheets that are processed by IE.
@@ -37,9 +30,9 @@ scripts[] = js/dom-removal.js
 scripts[] = js/dom-manipulate.js
 scripts[] = js/product.js
 scripts[] = js/select.js
-scripts[] = js/masonry.js
 scripts[] = js/scroll.js
 scripts[] = js/scroll-init.js
+scripts[] = js/masonry.js
 scripts[] = js/fixed-scroll.js
 scripts[] = js/jquery-scrolltofixed-min.js
 scripts[] = js/jquery.scrollTo-1.4.2-min.js
@@ -47,7 +40,6 @@ scripts[] = js/mb_menu.js
 scripts[] = js/mbMenu/inc/mbMenu.js
 scripts[] = js/mbMenu/inc/jquery.hoverIntent.js
 scripts[] = js/mbMenu/inc/jquery.metadata.js
-scripts[] = js/jquery.imagesloaded.js
 
   ; The regions defined in Zen's default page.tpl.php file.  The name in
   ; brackets is the name of the variable in the page.tpl.php file, (e.g.
diff --git a/version.txt b/version.txt
deleted file mode 100644
index 4ccd35a..0000000
--- a/version.txt
+++ /dev/null
@@ -1,6 +0,0 @@
-Versions
-
-v1.0 Programmed long ago
-v1.1 Thorough redesign, new offers system
-v1.2 Changes to file system and many bug fixes and design improvements
-
