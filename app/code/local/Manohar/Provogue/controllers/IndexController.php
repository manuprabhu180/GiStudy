<?php
class Manohar_Provogue_IndexController extends Mage_Core_Controller_Front_Action
{
	public function IndexAction()
	{
		//echo 'block';
		$this->loadLayout();
		$this->renderLayout();
		$this->renderLayout();
		$this->renderLayout();

	}
	public function UpdateqtyAction(){
		$result['result'] = Mage::helper('checkout/cart')->getSummaryCount();
		return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}

	public function UpdatecartAction(){
		$result['result'] = $this->getLayout()->createBlock('checkout/cart')->setTemplate('checkout/cart.phtml')->toHtml();
		return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}

	public function productOptionAction(){
		$id = $this->getRequest()->getParam('id');
		Mage::getSingleton('core/session')->setWelcomeMessage($id);
		$product = Mage::getModel('catalog/product')->load($id);
		if( $product->getData('has_options')){
			$result['option'] = $this->getLayout()->createBlock('core/template')->setTemplate('ajaxcart/options.phtml')->toHtml();
			
			return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
			
		}
	}

	public function cycleAction()
	{
		$cyclePeriod = "28+0";
		$placeboPills = '0';
		$cyclePeriodFirstPart = explode('+', $cyclePeriod);
		$RemindersFirstPart = $cyclePeriodFirstPart[0];
		$RemindersSecondPart = $cyclePeriodFirstPart[1];
		$startDate = 'Dec 1 2014';
		$curDate = date('Y-m-d');
	
		// echo $curDate;
		//If today  then its ok but if before today then count days between $startDate and today ,  
		//and subtract from $RemindersFirstPart to startremindesr and add days count to reminders after gap or after placebopills
		if($curDate > date('Y-m-d',strtotime($startDate))) {
			$days = strtotime($curDate) - strtotime(date('Y-m-d',strtotime($startDate)));
			$days = $days/(60*60*24);
			if($RemindersFirstPart < $days) {
				$days = strtotime($curDate) - strtotime(date('Y-m-d',strtotime($startDate)));
				$days = $days/(60*60*24);
				$days = fmod($days,($RemindersFirstPart + $RemindersSecondPart));
				// echo $days;exit();
			}
			if($RemindersFirstPart <= 28){
				$RemindersFirstPart = $RemindersFirstPart - $days;
				if($placeboPills == 1){
					$RemindersSecondPart = $RemindersSecondPart+$days+'2';
				} else {
					$dayCount = $RemindersFirstPart + $RemindersSecondPart;
					$RemindersSecondPart = $days+'2';
					$RemindersSecondPartCounterDate = date('Y-m-d',strtotime($curDate.'+'.$dayCount.'days'));
				}
			} else {
				$RemindersFirstPart = $RemindersFirstPart - $days;
				if($placeboPills == 1){
					$RemindersSecondPart = $RemindersSecondPart+$days;
				} else {
					$dayCount = $RemindersFirstPart + $RemindersSecondPart;
					$RemindersSecondPart = $days;
					$RemindersSecondPartCounterDate = date('Y-m-d',strtotime($curDate.'+'.$RemindersSecondPart.'days'));
				}
			}
		} else {
			if($RemindersFirstPart <= 28){
				$RemindersFirstPart = $RemindersFirstPart ;
				if($placeboPills == 1){
					$RemindersSecondPart = $RemindersSecondPart+'2';
				} else {
					$dayCount = $RemindersFirstPart + $RemindersSecondPart;
					$RemindersSecondPart = '2';
					$RemindersSecondPartCounterDate = date('Y-m-d',strtotime($curDate.'+'.$dayCount.'days'));
				}
			}
			else{
				$RemindersFirstPart = $RemindersFirstPart ;
				if($placeboPills == 1){
					$RemindersSecondPart = $RemindersSecondPart;
				} else {
					$dayCount = $RemindersFirstPart + $RemindersSecondPart;
					$RemindersSecondPart = '2';
					$RemindersSecondPartCounterDate = date('Y-m-d',strtotime($curDate.'+'.$dayCount.'days'));
				}
			}
		}

		for($i=0;$i<$RemindersFirstPart;$i++) {
			$currentDate = date('Y-m-d');
			echo date('Y-m-d',strtotime($currentDate.'+'.$i.'days')).'<br>';
		}
		if($placeboPills == 1){
			$RemindersSecondPartCounterDate = date('Y-m-d',strtotime($curDate.'+'.$RemindersFirstPart.'days'));
			for($i=0;$i<$RemindersSecondPart;$i++) {
				echo date('Y-m-d',strtotime($RemindersSecondPartCounterDate.'+'.$i.'days')).'aaaaaa<br>';
			}
		} else {
			 for($i=0;$i<$RemindersSecondPart;$i++) {
				echo date('Y-m-d',strtotime($RemindersSecondPartCounterDate.'+'.$i.'days')).'bb<br>';
			}
		}
	}
}