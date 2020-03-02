<?php

namespace Orangecat\CustomerLoginHistory\Cron;

use Exception;
use Orangecat\CustomerLoginHistory\Helper\Data;
use Orangecat\CustomerLoginHistory\Model\ResourceModel\LoginHistory\CollectionFactory;
use Psr\Log\LoggerInterface;

/**
 * Class CleanLoginHistory
 * @package Orangecat\CustomerLoginHistory\Cron
 */
class CleanLoginHistory
{
    /**
     * @var Data
     */
    protected $_helper;
    /**
     * @var _collection
     */
    protected $_collection;
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * CleanLoginHistory constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(
        Data $helper,
        CollectionFactory $collectionfactory,
        LoggerInterface $logger
    ) {
        $this->_helper = $helper;
        $this->_collection = $collectionfactory;
        $this->_logger = $logger;
    }

    /**
     * Execute Cron CleanLoginHistory
     */
    public function execute()
    {
        try {
            if ($this->_helper->isCleanUpEnabled()) {
                $period = $this->_helper->getPeriod();
                if ($this->_deleteLoginHistoryByPeriod($period)) {
                    $this->_logger->info('Access history cleared');
                }
            }
        } catch (Exception $e) {
            $this->_logger->debug($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param $id
     */
    protected function _deleteById($id)
    {
        try {
            $model = $this->_factory->create();
            $model->load($id);
            $model->delete();
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }

    protected function _deleteLoginHistoryByPeriod($period)
    {
        $periodDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-$period month"));

        $collection = $this->_collection->create();
        $collection->addFieldToFilter('login_time', ['lteq' => $periodDate]);
        $collection->load();

        if (count($collection)) {
            foreach ($collection as $item) {
                $this->_deleteById($item->getLoginId());
            }
            return true;
        } else {
            return false;
        }
    }
}
