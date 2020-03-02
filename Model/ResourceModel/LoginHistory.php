<?php

namespace Orangecat\CustomerLoginHistory\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Orangecat\CustomerLoginHistory\Model\LoginHistory as Model;

class LoginHistory extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::getTableName(), Model::ID);
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'login_history';
    }
}
