<?php
switch ($offer_type)
{
	case "new":
		$init_text = t("make offer");
		$init_text = "&nbsp;- <a class='offer2buy_init'>$init_text</a>";
		break;
	case "edit":
		$init_text = t("change offer");
		$init_text = "<a class='offer2buy_init'>$init_text</a>";
		break;
}
?>

<div class='offer2buy offer2buy_popup'>
	<span class='offer2buy_nid'><?php echo $nid; ?></span>
	<?php echo $init_text; ?>
</div>