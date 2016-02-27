<?php

class Manohar_Provogue_Block_Adminhtml_Provogue_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("provogueGrid");
				$this->setDefaultSort("provogue_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("catalog/product")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("provogue_id", array(
				"header" => Mage::helper("provogue")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "entity_id",
				));
                
				$this->addColumn("Microsite Name", array(
				"header" => Mage::helper("provogue")->__("Microsite Name"),
				"index" => "microsite_name",
				));
				$this->addColumn("Banner Description", array(
				"header" => Mage::helper("provogue")->__("Banner Description"),
				"index" => "banner_description",
				));
				$this->addColumn("Banner Image", array(
				"header" => Mage::helper("provogue")->__("Banner Image"),
				"index" => "banner_image",
				 'renderer' => 'Manohar_Provogue_Block_Adminhtml_Renderer_Image',
				));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('provogue_id');
			$this->getMassactionBlock()->setFormFieldName('provogue_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_provogue', array(
					 'label'=> Mage::helper('provogue')->__('Remove'),
					 'url'  => $this->getUrl('*/adminhtml_index/massRemove'),
					 'confirm' => Mage::helper('provogue')->__('Are you sure?')
				));
			return $this;
		}
			

}