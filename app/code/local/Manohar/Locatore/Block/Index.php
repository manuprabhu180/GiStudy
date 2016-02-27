<?php   
class Manohar_Locatore_Block_Index extends Mage_Core_Block_Template{   

	public function getOwnerName()
	{
		$data=Mage::getModel("locatore/locatore")->displayData();
		return $data;
	}

	public function getCity()
	{
		$city=Mage::getModel("locatore/locatore")->displayCity();
		return $city;
	}

	public function getSelectedCity()
	{
		$selectedCity=Mage::getModel("locatore/locatore")->selectData();
		//return $selectedCity;
	}



}