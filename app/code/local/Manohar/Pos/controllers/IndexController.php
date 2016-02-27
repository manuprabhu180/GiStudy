<?php
class Manohar_Pos_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
    $this->renderLayout();
	  /*$this->getLayout()->getBlock("head")->setTitle($this->__("Pos"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("pos", array(
                "label" => $this->__("Pos"),
                "title" => $this->__("Pos")
		   ));*/
	  
    }
}