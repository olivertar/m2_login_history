<?php

namespace Orangecat\CustomerLoginHistory\Model;

use Magento\Framework\Model\AbstractModel;
use Orangecat\CustomerLoginHistory\Api\Data\LoginHistoryInterface;
use Orangecat\CustomerLoginHistory\Model\ResourceModel\LoginHistory as ResourceModel;

class LoginHistory extends AbstractModel implements LoginHistoryInterface
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return string
     */
    public function getAgent()
    {
        return $this->getData(self::AGENT);
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->getData(self::IP);
    }

    /**
     * @return string
     */
    public function getLoginTime()
    {
        return $this->getData(self::LOGIN_TIME);
    }

    /**
     * @param  string $agent
     */
    public function setAgent($agent)
    {
        $this->setData(self::AGENT, $agent);
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->setData(self::IP, $ip);
    }

    /**
     * @param string $logintime
     */
    public function setLoginTime($logintime)
    {
        $this->setData(self::LOGIN_TIME, $logintime);
    }
}
