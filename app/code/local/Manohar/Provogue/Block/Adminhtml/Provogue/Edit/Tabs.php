<?php
class Manohar_Provogue_Block_Adminhtml_Provogue_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("provogue_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("provogue")->__("Banner Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("provogue")->__("Banner Information"),
				"title" => Mage::helper("provogue")->__("Banner Information"),
				"content" => $this->getLayout()->createBlock("provogue/adminhtml_provogue_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
