<h6>init.tpl.php is a pain point and needs to be backtraced and removed</h6>

<?php
// offer2buy_init: "arguments"     => array("nid" => false, "offer_type" => "new"),
// called from function offer2buy_preprocess_product_owner(&$vars) {


$make_offer = t("make offer");
$change_offer = t("change offer");
// passes in $init_text and $nid

switch ($offer_type)
{
	/* called by product.tpl */
	case "button":
		$text = "<a class='offer2buy_init_v1_2 popupsTOOSLOW'
                        href='add/offer/$nid'>offer popup</a>";
		//$init_text = "<button class='offer2buy_init'>$make_offer</button>";
/*
print '<pre>';
debug_print_backtrace();
DDEBUG_BACKTRACE();
*/

		break; 
	case "new":
		$init_text = "&nbsp;- <a class='offer2buy_init'>$make_offer</a>";
		break;
	case "edit":
		$init_text = "&nbsp;- <a class='offer2buy_init'>$change_offer</a>";
		break;
	
}
?>


<div class='offer2buy offer2buy_popup'>
	<span class='offer2buy_nid'><?php echo $nid; ?></span>
	<?php echo $init_text; ?>
</div>
        <?php echo "heres teh link: " . $text; ?>

