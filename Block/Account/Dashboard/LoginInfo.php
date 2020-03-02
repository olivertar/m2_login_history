<?php

namespace Orangecat\CustomerLoginHistory\Block\Account\Dashboard;

use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;
use Orangecat\CustomerLoginHistory\Model\ResourceModel\LoginHistory\Collection as LoginHistoryCollection;
use Orangecat\Geoip\Helper\Data;

class LoginInfo extends \Magento\Framework\View\Element\Template
{
    protected $currentCustomer;

    private $loginHistoryCollection;

    protected $helperGeoIp;

    /**
     * LoginInfo constructor.
     * @param Context $context
     * @param CurrentCustomer $currentCustomer
     * @param LoginHistoryCollection $loginHistoryCollection
     * @param Data $helperGeoIp
     * @param array $data
     */
    public function __construct(
        Context $context,
        CurrentCustomer $currentCustomer,
        LoginHistoryCollection $loginHistoryCollection,
        Data $helperGeoIp,
        array $data = []
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->loginHistoryCollection = $loginHistoryCollection;
        $this->helperGeoIp = $helperGeoIp;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    public function getCustomer()
    {
        try {
            return $this->currentCustomer->getCustomer();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @return mixed|void
     */
    public function getLastLogin()
    {
        $customer = $this->getCustomer();

        if ($customer):
            $this->loginHistoryCollection->addFilter('customer_id', $customer->getId());
        $this->loginHistoryCollection->setOrder('login_id', 'DESC');
        $this->loginHistoryCollection->getSelect()->limit(2);

        return  $this->loginHistoryCollection->getLastItem()->getData();
        endif;

        return;
    }

    /**
     * @return mixed
     */
    public function isGeoIpEnabled()
    {
        return $this->helperGeoIp->isEnabled();
    }
}
