<?php

namespace Orangecat\CustomerLoginHistory\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Orangecat\CustomerLoginHistory\Api\Data\LoginHistoryInterface;

interface LoginHistoryRepositoryInterface
{
    /**
     * Save record.
     *
     * @param LoginHistoryInterface $object
     * @return LoginHistoryInterface
     * @throws LocalizedException
     */
    public function save(LoginHistoryInterface $object);

    /**
     * Retrieve record.
     *
     * @param int $id
     * @return LoginHistoryInterface
     * @throws LocalizedException
     */
    public function getById($id);

    /**
     * Delete record.
     *
     * @param LoginHistoryInterface $object
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(LoginHistoryInterface $object);

    /**
     * Delete record by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($id);
}
