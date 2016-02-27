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
 * @category    Magestore
 * @package     Magestore_Siminotification
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

 /**
 * Siminotification Adminhtml Controller
 * 
 * @category    Magestore
 * @package     Magestore_Siminotification
 * @author      Magestore Developer
 */
class Simi_Siminotification_Adminhtml_SiminotificationController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Simi_Siminotification_Adminhtml_SiminotificationController
     */
    protected function _initAction(){
        $this->loadLayout()
            ->_setActiveMenu('siminotification/siminotification')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
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
        $id  = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('connector/notice')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data))
                $model->setData($data);

            Mage::register('siminotification_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('connector/notice');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Notification Manager'), Mage::helper('adminhtml')->__('Notification Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Notification News'), Mage::helper('adminhtml')->__('Notification News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('siminotification/adminhtml_siminotification_edit'))
                ->_addLeft($this->getLayout()->createBlock('siminotification/adminhtml_siminotification_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('siminotification')->__('Notification does not exist'));
            $this->_redirect('*/*/');
        }
    }
 
    public function newAction() {
        $this->_forward('edit');
    }
 
    /**
     * save item action
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            // Zend_debug::dump($_FILES['image_url']['name']);die();
            if(isset($_FILES['image_url']['name']) && $_FILES['image_url']['name'] != '') {
                try {
                    /* Starting upload */   
                    $uploader = new Varien_File_Uploader('image_url');
                    
                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                    $uploader->setAllowRenameFiles(false);
                    
                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //  (file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);
                            
                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . 'simi' . DS . 'simicart' . DS . 'notification' . DS . 'images';
                    // $result = $uploader->save($path, $_FILES['image_url']['name'] );
                    $result = $uploader->save($path, md5(time()).'.png');
                    $imageUrl = 'simi/simicart/notification/images/'.md5(time()).'.png';
                } catch (Exception $e) {
                    $imageUrl = 'simi/simicart/notification/images/'.md5(time()).'.png';
                }
            }
            // Zend_debug::dump($data);die();
            
            $data['created_time'] = now();
            $model = Mage::getModel('connector/notice');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
            if(!$imageUrl && is_array($data['image_url'])){
                if($data['image_url']['delete'])
                    $data['delete'] = $data['image_url']['delete'];
                $data['image_url'] = $data['image_url']['value'];
                $imageUrl = $data['image_url'];
            }

            if($data['delete']){
                $data['image_url'] = null;
                $imageUrl = null;
            }
            if($imageUrl){    
                $data['image_url'] = $imageUrl;
                $model->setImageUrl($imageUrl);
            }
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('siminotification')->__('Message was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false); 
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
            }else{ 
                if($data['image_url'])
                    $data['image_url'] = Mage::getBaseUrl('media').$data['image_url'];

                $data['notice_type'] = 0;
                $list = getimagesize($data['image_url']);
                $data['width'] = $list[0];
                $data['height'] = $list[1];
                $resultSend = $this->sendNotice($data);      
            }
            if (!$resultSend) {
                $this->_redirect('*/*/');
                return;
            }
            $this->_redirect('*/*/');
            return;
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('siminotification')->__('Unable to find item to send'));
        $this->_redirect('*/*/');
    }
 
    /**
     * delete item action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('connector/notice');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Message was successfully deleted'));
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
        $messageIds = $this->getRequest()->getParam('connector');

        if (!is_array($messageIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($messageIds as $messageId) {
                    $notice = Mage::getModel('connector/notice')->load($messageId);
                    $notice->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($bannerIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function sendNotice($data) {
        $trans = $this->send($data);
        // update notification history
        $history = Mage::getModel('siminotification/history'); 
        if(!$trans)
            $data['status'] = 0;
        else
            $data['status'] = 1;
        $history->setData($data);
        $history->save();
        return $trans;
    }

    public function send($data) {
        if($data['category_id']){
            $categoryId = $data['category_id'];
            $category = Mage::getModel('catalog/category')->load($categoryId);                                    
            $categoryChildrenCount = $category->getChildrenCount();
            $categoryName = $category->getName();
            $data['category_name'] = $categoryName;
            if($categoryChildrenCount > 0)
                $categoryChildrenCount = 1;
            else
                $categoryChildrenCount = 0;
            $data['has_child'] = $categoryChildrenCount;
            if(!$data['has_child']){
                $data['has_child'] = '';
            }
        }
        if($data['product_id']){
            $productId = $data['product_id'];
            $productName = Mage::getModel('catalog/product')->load($productId)->getName();
            $data['product_name'] = $productName;
        }
        $website = $data['website_id'];
        $collectionDevice = Mage::getModel('connector/device')->getCollection();
        if ($data['country'] != "0") {
            $country_id = trim($data['country']);
            $collectionDevice->addFieldToFilter('country', array('like' => '%' . $data['country'] . '%'));
        }
        if (isset($data['state']) && ($data['state'] != null)) {
            $city = trim($city);
            $collectionDevice->addFieldToFilter('state', array('like' => '%' . $data['state'] . '%'));
        }
        if (isset($data['city']) && ($data['city'] != null)) {
            $city = trim($city);
            $collectionDevice->addFieldToFilter('city', array('like' => '%' . $data['city'] . '%'));
        }
        if (isset($data['zipcode']) && ($data['zipcode'] != null)) {
            $city = trim($city);
            $collectionDevice->addFieldToFilter('zipcode', array('like' => '%' . $data['zipcode'] . '%'));
        }
        if ((int) $data['device_id'] != 0) {
            $collectionDevice->addFieldToFilter('website_id', array('eq' => $website));
            if ((int) $data['device_id'] == 2) {
                //send android
                $collectionDevice->addFieldToFilter('plaform_id', array('eq' => 3));
                return $this->sendAndroid($collectionDevice, $data);
            } else {
                //send IOS
                $collectionDevice->addFieldToFilter('plaform_id', array('neq' => 3));
                return $this->sendIOS($collectionDevice, $data);
            }
        } else {
            //send all
            $collection = $collectionDevice->addFieldToFilter('website_id', array('eq' => $website));
            $collectionDevice = Mage::getModel('connector/device')->getCollection()->addFieldToFilter('plaform_id', array('eq' => 3));
            $collection->addFieldToFilter('plaform_id', array('neq' => 3));
            $resultIOS = $this->sendIOS($collection, $data);
            $resultAndroid = $this->sendAndroid($collectionDevice, $data);
            if ($resultIOS || $resultAndroid)
                return true;
            else
                return false;
        }
    }

    public function sendIOS($collectionDevice, $data) {
        $ch = Mage::helper('connector')->getDirPEMfile();
        $dir = Mage::helper('connector')->getDirPEMPassfile();
        $message = $data['notice_content'];
        $body['aps'] = array(
            'alert' => $data['notice_title'],
            'sound' => 'default',
            'badge' => 1,
            'title' => $data['notice_title'],
            'message' => $message,
            'url' => $data['notice_url'],
            'type' => $data['type'],
            'productID' => $data['product_id'],
            'categoryID' => $data['category_id'],
            'categoryName' => $data['category_name'],
            'has_child'  => $data['has_child'],
            'imageUrl'   => $data['image_url'],
            'height'     => $data['height'],
            'width'     => $data['width'],
            'show_popup'   => $data['show_popup'],
        );
        // Zend_debug::dump($data);die();
        $payload = json_encode($body);
        $totalDevice = 0;
        foreach ($collectionDevice as $item) {
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $ch);
            if ((int) $data['notice_sanbox'] == 1 && (int) $data['device_id'] == 1) {
                $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
            } else {
             $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
            }
            if (!$fp) {
             Mage::getSingleton('adminhtml/session')->addError("Failed to connect:" . $err . $errstr . PHP_EOL . "(IOS)");
                return;
            }
        
            $deviceToken = $item->getDeviceToken();
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
            if (!$result) {
                Mage::getSingleton('adminhtml/session')->addError('Message not delivered (IOS)' . PHP_EOL);
                return false;
            }
            fclose($fp);
            $totalDevice++;
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Message successfully delivered to %s devices (IOS)', $totalDevice));
        return true;
    }

    public function sendAndroid($collectionDevice, $data) {
        $api_key = Mage::getStoreConfig('connector/android_key');
//        $api_key = "AIzaSyALAL5f9FOjn2e9s3WkJJvyTvWN9LAyDTs";
        // please enter the registration id of the device on which you want to send the message
        $registrationIDs = array();
        foreach ($collectionDevice as $item) {
            $registrationIDs[] = $item->getDeviceToken();
        }
        $message = array(
            'message' => $data['notice_content'], 
            'url' => $data['notice_url'], 
            'title' => $data['notice_title'],
            'type' => $data['type'],
            'productID' => $data['product_id'],
            'categoryID' => $data['category_id'],
            'categoryName' => $data['category_name'],
            'has_child'  => $data['has_child'],
            'imageUrl'   => $data['image_url'],
            'height'     => $data['height'],
            'width'     => $data['width'],
            'show_popup'   => $data['show_popup'],
        );
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registrationIDs,
            'data' => array("message" => $message),
        );

        $headers = array(
            'Authorization: key=' . $api_key,
            'Content-Type: application/json');
        $result = '';
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            
        }
        $re = json_decode($result);
        if ($re == NULL || $re->success == 0) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Message not delivered (Android)'));
            return false;
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Message successfully delivered to %s devices (Android)', $collectionDevice->getSize()));
        return true;
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('connector');
    }

    // public function downloadFileAction() {
    //     $imageUrl = $this->getRequest()->getParam('image_url');
    //     if ($imageUrl) {
    //         $filename = Mage::getBaseDir('media') . DS . 'simi' . DS . 'simicart' . DS . 'notification' . DS . 'images' . DS . $app_icon . '.zip';
    //         $this->_prepareDownloadResponse('app_icon_' . $app_icon . '.zip', file_get_contents($filename));
    //         return;
    //     }
    // }

    public function guideAction() {            
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Google Application Guide'));
        $this->renderLayout();
    }

    public function chooserMainCategoriesAction(){
        $request = $this->getRequest();
        $id = $request->getParam('selected', array());
        $block = $this->getLayout()->createBlock('siminotification/adminhtml_siminotification_edit_tab_categories','maincontent_category', array('js_form_object' => $request->getParam('form')))
                ->setCategoryIds($id)
        ;

        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    /**
     * Get tree node (Ajax version)
     */
    public function categoriesJsonAction() {
        if ($categoryId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $categoryId);

            if (!$category = $this->_initCategory()) {
                return;
            }
            $this->getResponse()->setBody(
                    $this->getLayout()->createBlock('adminhtml/catalog_category_tree')
                            ->getTreeJson($category)
            );
        }
    }

    /**
     * Initialize category object in registry
     *
     * @return Mage_Catalog_Model_Category
     */
    protected function _initCategory() {
        $categoryId = (int) $this->getRequest()->getParam('id', false);
        $storeId = (int) $this->getRequest()->getParam('store');

        $category = Mage::getModel('catalog/category');
        $category->setStoreId($storeId);

        if ($categoryId) {
            $category->load($categoryId);
            if ($storeId) {
                $rootId = Mage::app()->getStore($storeId)->getRootCategoryId();
                if (!in_array($rootId, $category->getPathIds())) {
                    $this->_redirect('*/*/', array('_current' => true, 'id' => null));
                    return false;
                }
            }
        }

        Mage::register('category', $category);
        Mage::register('current_category', $category);

        return $category;
    }

    public function categoriesJson2Action() {
        $this->_initItem();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('siminotification/adminhtml_siminotification_edit_tab_categories')
                        ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }

    public function chooserMainProductsAction() {
        $request = $this->getRequest();
        $block = $this->getLayout()->createBlock(
                'siminotification/adminhtml_siminotification_edit_tab_products', 'promo_widget_chooser_sku', array('js_form_object' => $request->getParam('form'),
                ));
        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }
    }
}