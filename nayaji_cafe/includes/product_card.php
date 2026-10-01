<?php
if (!isset($product) || !is_array($product)) {
	return;
}

render_product_card($product);
?>