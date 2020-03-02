<?php

namespace Orangecat\CustomerLoginHistory\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;


class Data extends AbstractHelper
{
    const CONFIG_MODULE_PATH = 'customerloginhistory';

    /**
     * Data constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isEnabled($store = null)
    {
        return $this->getConfigValue(self::CONFIG_MODULE_PATH . '/general/enabled', $store);
    }

    /**
     * @param null $store
     * @return string
     */
    public function getFakeIp($store = null)
    {
        return trim($this->getConfigValue(self::CONFIG_MODULE_PATH . '/general/fakeip', $store));
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isCleanUpEnabled($store = null)
    {
        return $this->getConfigValue(self::CONFIG_MODULE_PATH . '/cleanup/enabled', $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function getPeriod($store = null)
    {
        return $this->getConfigValue(self::CONFIG_MODULE_PATH . '/cleanup/period', $store);
    }
}
