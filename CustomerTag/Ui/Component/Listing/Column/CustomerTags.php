<?php
/**
 * @author Zhixing Wu <acesharkpee@gmail.com>
 * @create_time 8/23/24 2:46 PM
 */

namespace Yamato\CustomerTag\Ui\Component\Listing\Column;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Psr\Log\LoggerInterface;

class CustomerTags extends Column
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepository;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepositoryInterface;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        Repository $assetRepository,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        UrlInterface $urlBuilder,
        LoggerInterface $logger,
        array $components = [], array $data = [])
    {
        $this->assetRepository = $assetRepository;
        $this->customerFactory = $customerFactory;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->urlBuilder = $urlBuilder;
        $this->logger = $logger;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (!empty($item["customer_id"])) {
                    try {
                        $customer = $this->customerRepositoryInterface->getById($item["customer_id"]);
                        $tags = $customer->getCustomAttribute('tags') ?
                            $customer->getCustomAttribute('tags')->getValue()
                            : null
                        ;
                        if ($tags) {
                            $item['customer_tags'] = $tags;
                        }
                    } catch (\Exception $e) {
                        //do nothing
                    }
                }
            }
        }
        return $dataSource;
    }
}