<?php
class Manohar_Locatore_IndexController extends Mage_Core_Controller_Front_Action
{
	public function IndexAction()
	{
		//echo "Locatore";
		$this->loadLayout();
		$this->renderLayout();

		
	}
	public function SelectAction()
	{
		$params = $this->getRequest()->getPost('city');
		if($params=='')
		{
			$this->_redirect();
		}
		$status = Mage::getModel('locatore/locatore')->selectData($params);
		echo'<table>';
		 echo'<tr>';
		 echo'<td>Id</td>';
		 echo'<td>Store Name</td>';
		 echo'<td>Store City</td>';
		 echo'<td>Store Address</td>';
		 echo'<td></td>';
		 echo'</tr>';
		foreach ($status as $value) {
			echo'<tr>';
			echo'<td>'.$value["id"].'</td>';
			echo'<td>'.$value["store_name"].'</td>';
			echo'<td>'.$value["city"].'</td>';
			echo'<td>'.$value["store_address"].'</td>';
			echo'<td><input type="submit" value="Show Map" onclick="show_map('.$value["id"].')"></td>';
			echo'</tr>';
		}
		echo'<table>';
		
	}

	public function ShowmapAction()
	{
		$id=$this->getRequest()->getPost('id');
		if($id=='')
		{
			$this->_redirect();
		}
		$iframe=Mage::getModel('locatore/locatore')->showMap($id);
		print_r($iframe);
	} 
}