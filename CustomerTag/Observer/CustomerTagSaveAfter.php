<?php

namespace Yamato\CustomerTag\Observer;

use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;

class CustomerTagSaveAfter implements ObserverInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    protected $attributeSetFactory;

    /**
     * @var \Yamato\CustomerTag\Helper\Tag
     */
    protected $tag;

    /**
     * AddAppleEmailAttributeToCustomer constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        \Yamato\CustomerTag\Helper\Tag $tag,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->tag = $tag;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**@var \Magento\Eav\Setup\EavSetup $customerSetup*/
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $entityTypeId = $customerSetup->getEntityTypeId(Customer::ENTITY);
        $attributeId = $customerSetup->getAttributeId($entityTypeId, 'tags');
        $option = ['attribute_id' => $attributeId, 'values' => $this->tag->getValues()];
        $customerSetup->addAttributeOption($option);
    }
}
