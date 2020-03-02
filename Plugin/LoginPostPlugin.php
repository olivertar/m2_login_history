<?php

namespace Orangecat\CustomerLoginHistory\Plugin;

use Orangecat\CustomerLoginHistory\Plugin\LoginPostAbstract;

class LoginPostPlugin extends LoginPostAbstract
{
    /**
     * @param \Magento\Customer\Controller\Account\LoginPost $subject
     * @param $result
     * @return mixed
     * @throws \Exception
     */
    public function afterExecute(
        \Magento\Customer\Controller\Account\LoginPost $subject,
        $result
    ) {
        $this->saveLoginInfo();

        return $result;
    }

}
