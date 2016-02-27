<?php
class Simi_Popup_Model_System_Config_Source_Category
{

    public function toOptionArray()
    {
        $collection = Mage::getResourceModel('catalog/category_collection');

        $collection->addAttributeToSelect('name')
            ->addFieldToFilter('level', array('gteq' => 1)) //'gtec' Greater than or equals to cool trick
            ->load();

        $options = array();

        $options[] = array(
            'label' => Mage::helper('simi_popup')->__('-- None --'),
            'value' => ''
        );

        foreach ($collection as $category) {
            $label = $category->getName();
            // Trying to create a visiual heiracrchy so you can see what level you're on
            $padLength = ($category->getLevel() - 1) * 2 + strlen($label);
            $label = str_pad($label, $padLength, '-', STR_PAD_LEFT);
            $options[] = array(
               'label' => $label,
               'value' => $category->getId()
            );
        }

        return $options;
    }

}