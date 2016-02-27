<?php
 
class Manohar_Locatore_Model_Mysql4_Locatore extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('locatore/locatore', 'id');
    }
}