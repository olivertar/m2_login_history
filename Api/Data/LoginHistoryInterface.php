<?php

namespace Orangecat\CustomerLoginHistory\Api\Data;

interface LoginHistoryInterface
{
    const ID = 'login_id';
    const AGENT = 'agent';
    const IP = 'ip';
    const LOGIN_TIME = 'login_time';
    const ENTITY_TITLE = 'LoginHistory';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getAgent();

    /**
     * @return string
     */
    public function getIp();

    /**
     * @return string
     */
    public function getLoginTime();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @param  string $agent
     */
    public function setAgent($agent);

    /**
     * @param string $ip
     */
    public function setIp($ip);

    /**
     * @param string $logintime
     */
    public function setLoginTime($logintime);
}
