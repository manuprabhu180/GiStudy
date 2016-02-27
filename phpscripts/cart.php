<?php
require_once('../app/Mage.php');
umask(0);
Mage::app();

echo '<pre>';
$customer = Mage::getModel('customer/customer')->load(1);
//print_r($customer->getData());exit();
//$cartHelper =Mage::helper('checkout/cart')->getItems();


$quote = Mage::getModel('sales/quote')->loadByCustomer($customer);

// print_r($quote->getCart());
// exit();
foreach ($quote->getAllItems() as $value) {
	$quote ->removeItem(4)->save();
	print_r($value->getName());
	echo'<br>';
}











?>