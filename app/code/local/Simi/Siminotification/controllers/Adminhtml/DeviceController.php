<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category 	Magestore
 * @package 	Magestore_Siminotification
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

 /**
 * Siminotification Adminhtml Controller
 * 
 * @category 	Magestore
 * @package 	Magestore_Siminotification
 * @author  	Magestore Developer
 */
class Simi_Siminotification_Adminhtml_DeviceController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * init layout and set active for current menu
	 *
	 * @return Simi_Siminotification_Adminhtml_Deviceontroller
	 */
	protected function _initAction(){
		$this->loadLayout()
			->_setActiveMenu('siminotification/device')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Devices Manager'), Mage::helper('adminhtml')->__('Devices Manager'));
		return $this;
	}
 
	/**
	 * index action
	 */
	public function indexAction(){
		if(Mage::getStoreConfig('connector/free_version') == 0){
            echo Mage::helper('adminhtml')->__('You must buy SimiCart to use this feature. ') . '<a href="https://www.simicart.com/usermanagement/buy/lite/">BUY NOW</a>';
            return;
        }
		$this->_initAction()
			->renderLayout();
	}

	/**
	 * view and edit item action
	 */
	public function editAction() {
		$id	 = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('connector/device')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data))
				$model->setData($data);

			Mage::register('device_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('connector/device');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Device Manager'), Mage::helper('adminhtml')->__('Device Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Device News'), Mage::helper('adminhtml')->__('Device News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()->createBlock('siminotification/adminhtml_device_edit'))
				->_addLeft($this->getLayout()->createBlock('siminotification/adminhtml_device_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('siminotification')->__('Device does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}

	/**
     * delete item action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('connector/device');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Device was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction() {
        $deviceIds = $this->getRequest()->getParam('siminotification');

        if (!is_array($deviceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($deviceIds as $deviceId) {
                    $device = Mage::getModel('connector/device')->load($deviceId);
                    $device->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d device(s) were successfully deleted', count($bannerIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
 
}