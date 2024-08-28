<?php

declare(strict_types=1);

namespace Yamato\CustomerTag\Plugin\Store;

use Yamato\CustomerTag\Api\Data\TagInterface;
use Yamato\CustomerTag\Model\Entity\TagFactory;
use Magento\Store\Api\Data\StoreConfigInterface;
use Magento\Store\Model\Service\StoreConfigManager;
use Magento\Store\Api\Data\StoreConfigExtension;
use Magento\Store\Api\Data\StoreConfigExtensionFactory;

class StoreConfigManagerPlugin
{

    /**
     * @var StoreConfigExtensionFactory
     */
    protected $extensionFactory;

    /**
     * @var TagFactory
     */
    protected $tagFactory;

    /**
     * @var \Yamato\CustomerTag\Helper\Tag
     */
    protected $tag;

    /**
     * StoreConfigManagerPlugin constructor.
     *
     * @param StoreConfigExtensionFactory $extensionFactory
     * @param \Yamato\CustomerTag\Helper\Tag $tag
     * @param TagFactory $tagFactory
     */
    public function __construct(
        StoreConfigExtensionFactory $extensionFactory,
        \Yamato\CustomerTag\Helper\Tag $tag,
        TagFactory $tagFactory
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->tagFactory = $tagFactory;
        $this->tag = $tag;
    }


    /**
     * @param StoreConfigManager $subject
     * @param $storeConfigs
     * @return mixed
     */
    public function afterGetStoreConfigs(StoreConfigManager $subject, $storeConfigs)
    {
        if ($this->tag->enable()) {
            /** @var StoreConfigInterface $storeConfig */
            foreach ($storeConfigs as $storeConfig) {
                $extensionAttributes = $storeConfig->getExtensionAttributes();
                if (null === $extensionAttributes) {
                    $extensionAttributes = $this->extensionFactory->create();
                }

                $tags = [];
                /** @var TagInterface $tag */
                foreach ($this->tag->getTags() as $_tag) {
                    $tag = $this->tagFactory->create();
                    $tag->setValue($_tag['value']);
                    $tag->setLabel($_tag['label']);
                    $tags[] = $tag;
                }

                $extensionAttributes->setCustomerTags($tags);
                $storeConfig->setExtensionAttributes($extensionAttributes);
            }
        }
        return $storeConfigs;
    }
}
