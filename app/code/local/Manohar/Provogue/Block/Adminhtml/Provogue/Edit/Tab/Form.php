<?php
class Manohar_Provogue_Block_Adminhtml_Provogue_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{
			$form = new Varien_Data_Form();
			$this->setForm($form);
			$fieldset = $form->addFieldset("provogue_form", array("legend"=>Mage::helper("provogue")->__("Item information")));
			
				$fieldset->addField("ID", "text", array(
				"label" => Mage::helper("provogue")->__("ID"),
				"name" => "id",
				'readonly' => true,
				));

				$fieldset->addField("Microsite Name", "select", array(
				"label" => Mage::helper("provogue")->__("Microsite Name"),
				'values' => array('-1'=>'Select','1' => 'Axis','2' => 'Kotak', '3' => 'SBI'),
				"name" => "microsite_name",
				));

				$fieldset->addField("Banner Description", "textarea", array(
				"label" => Mage::helper("provogue")->__("Banner Description"),
				"name" => "banner_description",
				));

				$fieldset->addField("Banner Image", "file", array(
				"label" => Mage::helper("provogue")->__("Banner Image"),
				"name" => "banner_image",
				));
			
			if (Mage::getSingleton("adminhtml/session")->getCnfdetailsData())
			{
				$form->setValues(Mage::getSingleton("adminhtml/session")->getCnfdetailsData());
				Mage::getSingleton("adminhtml/session")->setCnfdetailsData(null);
			} 
			elseif(Mage::registry("provogue_data")) {
			    $form->setValues(Mage::registry("provogue_data")->getData());
			}
			return parent::_prepareForm();
		}
}
