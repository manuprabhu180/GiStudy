<?php
require_once 'Mage/Checkout/controllers/CartController.php';
Class Manohar_Locatore_Checkout_CartController extends Mage_Checkout_CartController{
	public function addAction()
    {
        
        if (!$this->_validateFormKey()) {
            $this->_goBack();
            return;
        }
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();

        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }
            
            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');

            /**
             * Check product availability
             */
            if (!$product) {
                $this->_goBack();
                return;
            }

            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();
            

            $this->_getSession()->setCartWasUpdated(true);

            /**
             * @todo remove wishlist observer processAddToCart
             */
            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {
                    $result['result'] = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
                     // $this->_getSession()->addSuccess($message);
                    return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                }
                //$this->_goBack();
            }
        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $result['status'] = 0;
                $result['result'] = $e->getMessage();
               return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                //$this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
            } else {
                $result['status'] = 0;
                $result['result'] = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                     return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);
            if ($url) {
                //$this->getResponse()->setRedirect($url);
            } else {
                //$this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        } catch (Exception $e) {
            $result['result'] = 'Cannot add the item to shopping cart.';
            return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

            Mage::logException($e);
            //$this->_goBack();
        }
    }

    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)
                  ->save();
                 $result['status'] = 1;
                 $result['result'] = 'Successfully deleted cart item';
                 $result['cart'] = $this->getLayout()->createBlock('checkout/cart')->setTemplate('checkout/cart.phtml')->toHtml();
                 return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

            } catch (Exception $e) {
                // $this->_getSession()->addError($this->__('Cannot remove the item.'));
                // Mage::logException($e);
                $result['status'] = 0;
                $result['result'] = 'Cannot remove the item';
                return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            }
        }
        //$this->_redirectReferer(Mage::getUrl('*/*'));
    }
}