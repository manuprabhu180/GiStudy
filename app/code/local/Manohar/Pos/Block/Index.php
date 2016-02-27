<?php   
class Manohar_Pos_Block_Index extends Mage_Core_Block_Template{   

	public function getProductCollection(){
		$productModel = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
		return $productModel;
	}



}