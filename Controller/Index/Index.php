<?php

namespace Orangecat\CustomerLoginHistory\Controller\Index;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Orangecat\CustomerLoginHistory\Model\LoginHistoryFactory;

class Index extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var messageManager
     */
    protected $messageManager;
    /**
     * @var _factory
     */
    protected $_factory;

    /**
     * Index constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param ManagerInterface $messageManager
     * @param LoginHistoryFactory $factory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        ManagerInterface $messageManager,
        LoginHistoryFactory $factory
    ) {
        $this->_customerSession = $customerSession;
        $this->messageManager = $messageManager;
        $this->_factory = $factory;
        parent::__construct($context);
    }
    /**
     * Execute view action
     * @return ResultInterface
     */
    public function execute()
    {
        $loginHistoryIds = $this->getRequest()->getParam('login_history');

        if (!empty($loginHistoryIds)) {
            if ($loginHistoryIds == 'all') {
                $this->_deleteAll();
                $total = 'all';
            } else {
                $ids = explode(',', $loginHistoryIds);
                $total = count($ids);
                foreach ($ids as $id) {
                    $this->_deleteById($id);
                }
            }
            $this->messageManager->addSuccessMessage(
                __('%1 records have been deleted.', $total)
            );
        }

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if ($navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('loginhistory');
        }
        return $resultPage;
    }

    /**
     * @return Session
     */
    protected function _getSession()
    {
        return $this->_customerSession;
    }

    /**
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->_getSession()->authenticate()) {
            $this->_actionFlag->set('', 'no-dispatch', true);
        }
        return parent::dispatch($request);
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

    protected function _deleteAll()
    {
        $model = $this->_factory->create();
        $collection = $model->getCollection()->addFilter('customer_id', $this->_customerSession->getCustomerId());
        $collection->load();
        foreach ($collection as $item) {
            $this->_deleteById($item->getLoginId());
        }
    }
}
