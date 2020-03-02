<?php

namespace Orangecat\CustomerLoginHistory\Model\ResourceModel\LoginHistory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Orangecat\CustomerLoginHistory\Model\LoginHistory as Model;
use Orangecat\CustomerLoginHistory\Model\ResourceModel\LoginHistory as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = Model::ID;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Model::class,
            ResourceModel::class
        );
    }
}
