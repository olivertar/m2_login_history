<?php

namespace Orangecat\CustomerLoginHistory\Plugin;

use Orangecat\CustomerLoginHistory\Plugin\LoginPostAbstract;

class CreatePostPlugin extends LoginPostAbstract
{
    /**
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        $result
    ) {
        $this->saveLoginInfo();

        return $result;
    }

}
