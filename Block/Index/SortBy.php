<?php

namespace Orangecat\CustomerLoginHistory\Block\Index;

use Magento\Framework\View\Element\Template;

class SortBy extends Template
{
    /**
     * @return array
     */
    public function getAvailableOrders()
    {
        $orders = ['login_time' => 'Date/Time', 'agent' => 'Agent', 'ip' => 'Ip'];

        return $orders;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isOrderCurrent($key)
    {
        $order = ($this->getRequest()->getParam('sortby')) ? $this->getRequest()->getParam('sortby') : 'login_time';
        if ($order == $key) :
            return true; else:
            return false;
        endif;
    }

    /**
     * @return mixed|string
     */
    public function getCurrentDirection()
    {
        $direction = ($this->getRequest()->getParam('direct')) ? $this->getRequest()->getParam('direct') : 'desc';

        return $direction;
    }
}
