<?php
	class Manohar_Locatore_Model_Locatore extends Mage_Core_Model_Abstract
	{
		 	public function _construct()
		    {
		        parent::_construct();
		        $this->_init('locatore/locatore');
		    }
		 	public function displayData()
		    {
		    	 return $result=Mage::getModel('locatore/locatore')->getCollection()->getData();
		    }
		    

			public function selectData($params)
			{
				$result=Mage::getModel('locatore/locatore')->getCollection()->addFieldToFilter('city',$params);
				return $result->getData();
			}

			public function showMap($params)
			{
				$result=Mage::getModel('locatore/locatore')->getCollection()->addFieldToFilter('id',$params);
				$result->getData();
				foreach ($result as $iframeValue) {
					$iframe=$iframeValue['link'];
				}
				return $iframe;

			}

	}
	?>
