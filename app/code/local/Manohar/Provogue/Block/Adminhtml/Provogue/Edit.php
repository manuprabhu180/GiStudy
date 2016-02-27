<?php
	
class Manohar_Provogue_Block_Adminhtml_Provogue_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "provogue_id";
				$this->_blockGroup = "provogue";
				$this->_controller = "adminhtml_provogue";
				$this->_updateButton("save", "label", Mage::helper("provogue")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("provogue")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("provogue")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("provogue_data") && Mage::registry("provogue_data")->getId() ){

				    return Mage::helper("provogue")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("provogue_data")->getId()));

				} 
				else{

				     return Mage::helper("provogue")->__("Add Item");

				}
		}
}