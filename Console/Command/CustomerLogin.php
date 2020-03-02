<?php

namespace Orangecat\CustomerLoginHistory\Console\Command;

use Orangecat\CustomerLoginHistory\Model\LoginHistoryFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomerLogin extends Command
{
    const COMMAND_NAME = 'customerlogin:deletebyuser';
    const INPUT_KEY_USERID = 'user_id';

    /**
     * @var _factory
     */
    protected $_factory;

    /**
     * Index constructor.
     * @param LoginHistoryFactory $factory
     */
    public function __construct(
        LoginHistoryFactory $factory
    ) {
        $this->_factory = $factory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)->setDescription('Deletes the log records of a specific user determined by their ID');
        $this->addArgument(self::INPUT_KEY_USERID, InputArgument::REQUIRED, __('You must enter the Magento User ID'));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $customerId = $input->getArgument(self::INPUT_KEY_USERID);
            if ($this->_deleteAll($customerId)) {
                $output->writeln('User ID ' . $customerId . ' access records were deleted.');
            } else {
                $output->writeln('User ID ' . $customerId . ' does not exist.');
            }
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
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

    protected function _deleteAll($customerId)
    {
        $model = $this->_factory->create();
        $collection = $model->getCollection();
        if ($customerId != 'all') {
            $collection->addFilter('customer_id', $customerId);
        }
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
