<?php

class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Categories extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
	protected $_categoryIds;
    protected $_selectedNodes = null;

    public function __construct()
    {
        parent::__construct();
		$this->_withProductCount = false;
        $this->setTemplate('simi/connector/categories.phtml');
    }
	
	public function getCategoryCollection()
    {
        $storeId = $this->getRequest()->getParam('store', $this->_getDefaultStoreId());
        $collection = $this->getData('category_collection');
        if (is_null($collection)) {
            $collection = Mage::getModel('catalog/category')->getCollection();

            /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
            $collection->addAttributeToSelect('name')
                ->addAttributeToSelect('is_active')
                ->setProductStoreId($storeId)
                ->setLoadProductCount($this->_withProductCount)
                ->setStoreId($storeId);

            $this->setData('category_collection', $collection);
        }
        return $collection;
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return false;//$this->getProduct()->getCategoriesReadonly();
    }

    protected function getCategoryIds()
    {
        return explode(',',$this->getIdsString());//$this->getProduct()->getCategoryIds();
    }

    public function getIdsString()
    {		
        return Mage::registry('simi_categories');//Mage::registry('bannerslider_data')->getCategories();//implode(',', $this->getCategoryIds());
    }
}