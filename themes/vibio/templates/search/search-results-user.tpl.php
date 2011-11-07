<?php
//dsm($search_results); --> sometime during devel of v1.1,
//	this has started coming back blank.
// search.pages.inc:	 was $variables['search_results
// works for now, fix later.
//dsm(debug_backtrace());

echo "$pager
<table class='search-results $type'>
		$search_results_not_lost
	</table>
";
?>
