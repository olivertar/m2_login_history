<?php

namespace Orangecat\CustomerLoginHistory\Model\Repository;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Model\AbstractModel;
use Orangecat\CustomerLoginHistory\Api\Data\LoginHistoryInterface;
use Orangecat\CustomerLoginHistory\Model\LoginHistoryFactory;
use Orangecat\CustomerLoginHistory\Model\ResourceModel\LoginHistory;
use Orangecat\CustomerLoginHistory\Api\LoginHistoryRepositoryInterface;

class LoginHistoryRepository implements LoginHistoryRepositoryInterface
{
    /**
     * @var array
     */
    protected $_instances = [];

    /**
     * @var LoginHistory
     */
    protected $_resource;

    /**
     * @var LoginHistoryFactory
     */
    protected $_factory;

    /**
     * LoginHistoryRepository constructor.
     * @param LoginHistory $resource
     * @param LoginHistoryFactory $factory
     */
    public function __construct(
        LoginHistory $resource,
        LoginHistoryFactory $factory
    ) {
        $this->_resource = $resource;
        $this->_factory = $factory;
    }

    /**
     * Save data.
     *
     * @param LoginHistoryInterface $object
     * @return LoginHistoryInterface
     * @throws LocalizedException
     */
    public function save(LoginHistoryInterface $object)
    {
        /** @var LoginHistoryInterface|AbstractModel $object */
        try {
            $this->_resource->save($object);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the record: %1',
                $exception->getMessage()
            ));
        }
        return $object;
    }

    /**
     * Retrieve data.
     *
     * @param int $id
     * @return LoginHistoryInterface
     * @throws LocalizedException
     */
    public function getById($id)
    {
        if (!isset($this->_instances[$id])) {
            /** @var LoginHistoryInterface|AbstractModel $object */
            $object = $this->_factory->create();
            $this->_resource->load($object, $id);
            if (!$object->getId()) {
                throw new NoSuchEntityException(__('Data does not exist'));
            }
            $this->_instances[$id] = $object;
        }
        return $this->_instances[$id];
    }

    /**
     * Delete data.
     *
     * @param LoginHistoryInterface $object
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(LoginHistoryInterface $object)
    {
        /** @var LoginHistoryInterface|AbstractModel $object */
        $id = $object->getId();
        try {
            unset($this->_instances[$id]);
            $this->_resource->delete($object);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new StateException(
                __('Unable to remove %1', $id)
            );
        }
        unset($this->_instances[$id]);
        return true;
    }

    /**
     * Delete data by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
