<?php


class Manohar_Provogue_Block_Adminhtml_Provogue extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{
		$this->_controller = "adminhtml_provogue";
		$this->_blockGroup = "provogue";
		$this->_headerText = Mage::helper("provogue")->__("Gid Data");
		$this->_addButtonLabel = Mage::helper("provogue")->__("Add New Item");
		parent::__construct();
	}

}