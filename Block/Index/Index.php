<?php

namespace Orangecat\CustomerLoginHistory\Block\Index;

use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Orangecat\CustomerLoginHistory\Model\ResourceModel\LoginHistory\Collection as LoginHistoryCollection;
use Orangecat\Geoip\Helper\Data;

class Index extends Template
{
    private $loginHistoryCollection;

    protected $currentCustomer;

    protected $collection;

    protected $helperGeoIp;

    /**
     * Index constructor.
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
        parent::__construct($context, $data);
        $this->currentCustomer = $currentCustomer;
        $this->loginHistoryCollection = $loginHistoryCollection;
        $this->helperGeoIp = $helperGeoIp;
    }

    /**
     * @return bool|LoginHistoryCollection
     */
    public function getLogins()
    {
        if (!($customerId = $this->currentCustomer->getCustomerId())) {
            return false;
        }

        if (!$this->collection) {
            $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
            $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
            $order = ($this->getRequest()->getParam('sortby')) ? $this->getRequest()->getParam('sortby') : 'login_time';
            $direction = ($this->getRequest()->getParam('direct')) ? $this->getRequest()->getParam('direct') : 'desc';

            $this->collection = $this->loginHistoryCollection;
            $this->collection->addFilter('customer_id', $customerId);
            $this->collection->setOrder($order, strtoupper($direction));
            $this->collection->setPageSize($pageSize);
            $this->collection->setCurPage($page);
        }

        return $this->collection;
    }

    /**
     * @return mixed
     */
    public function isGeoIpEnabled()
    {
        return $this->helperGeoIp->isEnabled();
    }

    /**
     * @return bool
     */
    public function isExpanded()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getSubmitUrl()
    {
        $submitUrl = $this->getUrl('*/*/*');
        return $submitUrl;
    }

    /**
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * @return $this|Template
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Customer Login History'));

        if ($this->getLogins()) {
            $toolbar = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'customer_loginhistory_list.toolbar'
            )
                ->setAvailableLimit([5=>5,10=>10,15=>15])
                ->setShowPerPage(true)
                ->setAvailableSortBy(['login_time'=>'login_time','agent'=>'agent'])
                ->setShowSortBy(true)
                ->setCollection($this->getLogins());

            $this->setChild('toolbar', $toolbar);
            $this->getLogins()->load();

            return $this;
        }
    }
}
