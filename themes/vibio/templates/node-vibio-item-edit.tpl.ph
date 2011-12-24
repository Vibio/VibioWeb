<?php 
die("Are we here yet?"); ?>
// new_collection
// Very simple: collections go near the top.  So just the title and collections,
//  then the custom stuff,
//  then drupal_render($form);
print drupal_render($form['title']);
//print drupal_render($form['field_collection']);
print "<h2>Some stuff about collections with javascript will get scooted in here</h2>";
// THIS SHOULD WRAP UP WHATEVER IS LEFT:
print drupal_render($form);

    ?>
</div>
<?php 
  
  //Enable below to show all Array Variables of Form 
/*
  print '<pre>';
	print_r($form['field_chat'][0][value]);
//  print_r($form);
  print '</pre>';
*/
