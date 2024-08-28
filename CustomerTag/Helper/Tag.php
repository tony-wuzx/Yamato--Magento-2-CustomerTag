<?php

    namespace Yamato\CustomerTag\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Psr\Log\LoggerInterface;

class Tag
{
    const ENABLE = 'yamato_customer_tag/configuration/is_enabled';
    const TAGS = 'yamato_customer_tag/configuration/tags';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    /**
     * Customer constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerRepositoryInterface $customerRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->jsonSerializer->unserialize((string) $this->config(self::TAGS));
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $values = [];
        foreach ($this->getTags() as $tag) {
            $values[$tag['value']] = $tag['label'];
        }
        return $values;
    }

    /**
     * @return void
     */
    public function log($message)
    {
        $this->logger->info($message);
    }

    /**
     * @return bool
     */
    public function enable()
    {
        return $this->config(self::ENABLE);
    }

    /**
     * @return mixed
     */
    public function config($key)
    {
        return $this->scopeConfig->getValue($key, ScopeInterface::SCOPE_STORE);
    }
}
