<?php

set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors",'On');

$rootDir = ""; // Root Directory Path
include($rootDir."app/Mage.php");
Mage::app("default");

$rootcatId= Mage::app()->getStore()->getRootCategoryId(); // get default store root category id
$categories = Mage::getModel('catalog/category')->getCategories($rootcatId); // else use default category id =2

function show_categories_tree($categories) {
$array= '<ul>';
foreach($categories as $category) {
$cat = Mage::getModel('catalog/category')->load($category->getId());
$count = $cat->getProductCount();
$array .= '<li>'.
'<a href="' . Mage::getUrl($cat->getUrlPath()). '">' .
$category->getName() . "(".$count.")</a>\n";
if($category->hasChildren()) {
$children = Mage::getModel('catalog/category')->getCategories($category->getId());
$array .= show_categories_tree($children);
}
$array .= '</li>';
}
return $array . '</ul>';
}
echo show_categories_tree($categories);

?>