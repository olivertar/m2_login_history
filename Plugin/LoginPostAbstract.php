<?php

namespace Orangecat\CustomerLoginHistory\Plugin;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Header;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Orangecat\CustomerLoginHistory\Helper\Data as HelperLoginHistory;
use Orangecat\CustomerLoginHistory\Model\LoginHistoryFactory;
use Orangecat\Geoip\Helper\Data as HelperGeoIp;

class LoginPostAbstract
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LoginHistoryFactory
     */
    private $loginHistoryFactory;

    /**
     * @var httpHeader
     */
    protected $httpHeader;

    /**
     * @var remoteIp
     */
    protected $remoteIp;

    /**
     * @var helperLoginHistory
     */
    protected $helperLoginHistory;

    /**
     * @var helperGeoIp
     */
    protected $helperGeoIp;

    /**
     * LoginPostAbstract constructor.
     * @param Session $session
     * @param ScopeConfigInterface $scopeConfig
     * @param Header $httpHeader
     * @param RemoteAddress $remoteIp
     * @param LoginHistoryFactory $loginHistoryFactory
     * @param HelperLoginHistory $helperLoginHistory
     * @param HelperGeoIp $helperGeoIp
     */
    public function __construct(
        Session $session,
        ScopeConfigInterface $scopeConfig,
        Header $httpHeader,
        RemoteAddress $remoteIp,
        LoginHistoryFactory $loginHistoryFactory,
        HelperLoginHistory $helperLoginHistory,
        HelperGeoIp $helperGeoIp
    ) {
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
        $this->loginHistoryFactory = $loginHistoryFactory;
        $this->httpHeader = $httpHeader;
        $this->remoteIp = $remoteIp;
        $this->helperLoginHistory = $helperLoginHistory;
        $this->helperGeoIp = $helperGeoIp;
    }

    /**
     * @throws \Exception
     */
    public function saveLoginInfo()
    {
        if ($this->session->isLoggedIn()) {
            $ip = $this->remoteIp->getRemoteAddress();
            if ($ipFake = $this->helperLoginHistory->getFakeIp()) {
                $ip = $ipFake;
            }
            if ($this->helperGeoIp->isEnabled()) {
                $geoipdata = $this->helperGeoIp->getGeoIpData($ip);
                if (isset($geoipdata['country_name'])) {
                    $data['country'] = $geoipdata['country_name'];
                    $data['city'] = $geoipdata['city'];
                } else {
                    $data['country'] = 'Local IP';
                }
            }

            $data['customer_id'] = $this->session->getCustomerId();
            $data['agent'] = $this->httpHeader->getHttpUserAgent();
            $data['ip'] = $ip;

            $loginHistory = $this->loginHistoryFactory->create();
            $loginHistory->setData($data);
            $loginHistory->save();
        }
    }
}
