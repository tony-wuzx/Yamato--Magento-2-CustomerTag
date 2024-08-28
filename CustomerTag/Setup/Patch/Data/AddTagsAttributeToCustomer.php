<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Yamato\CustomerTag\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class AddTagsAttributeToCustomer implements DataPatchInterface
{
    const CUSTOMER_ATTRIBUTE = 'tags';

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
     * {@inheritdoc}
     */
    public function apply()
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->removeAttribute(Customer::ENTITY, self::CUSTOMER_ATTRIBUTE);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            self::CUSTOMER_ATTRIBUTE,
            [
                'type' => 'varchar',
                'label' => 'Tags',
                'input' => 'multiselect',
                'required' => false,
                'is_used_in_grid' => 1,
                'is_visible_in_grid' => 1,
                'is_filterable_in_grid' => 1,
                'is_searchable_in_grid' => 1,
                'sort_order' => 1,
                'position' => 1,
                'visible' => true,
                'user_defined' => true,
                'unique' => false,
                'system' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'source' => \Yamato\CustomerTag\Model\Customer\Attribute\Source\Tag::class,
                'backend_model' => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class,
            ]
        );

        $attribute = $customerSetup->getEavConfig()->getAttribute(
            Customer::ENTITY,
            self::CUSTOMER_ATTRIBUTE
        );

        $attribute->addData(
            [
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit'],
            ]
        );

        $attribute->save();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
