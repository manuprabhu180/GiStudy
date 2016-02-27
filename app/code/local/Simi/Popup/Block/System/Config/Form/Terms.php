<?php

class Simi_Popup_Block_System_Config_Form_Terms extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        	$rootcatId= Mage::app()->getStore()->getRootCategoryId(); // get default store root category id
			$categories = Mage::getModel('catalog/category')->getCategories(2); // else use default category id =2
			$as = new Simi_Popup_Block_System_Config_Form_Terms();
			$a = $this->show_categories_tree($categories);
		    return $a;
	}
	function show_categories_tree($categories) {
			$array= '<ul>';
			foreach($categories as $category) {
			$cat = Mage::getModel('catalog/category')->load($category->getId());
			$count = $cat->getProductCount();
			$array .= '<li><input type="checkbox">'.$category->getName(); 
			if($category->hasChildren()) {
			$children = Mage::getModel('catalog/category')->getCategories($category->getId());
			$array .= $this->show_categories_tree($children);
			}
			$array .= '</li>';
			}
			return $array . '</ul>';
		}	
	
	
}