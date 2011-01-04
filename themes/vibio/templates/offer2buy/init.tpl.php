<?php
$make_offer = t("make offer");
$change_offer = t("change offer");

switch ($offer_type)
{
	case "button":
		$init_text = "<button class='offer2buy_init'>$make_offer</button>";
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