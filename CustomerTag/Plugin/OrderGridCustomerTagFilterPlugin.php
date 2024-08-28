<?php
/**
 * @author Zhixing Wu <acesharkpee@gmail.com>
 * @create_time 8/27/24 5:18 PM
 */

namespace Yamato\CustomerTag\Plugin;

use Closure;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\DB\Select;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class OrderGridCustomerTagFilterPlugin
{
    const CUSTOMER_ATTRIBUTE = 'tags';
    const ORDER_TAG_FILTER = 'customer_tags';

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

    public function aroundAddFieldToFilter(
        SearchResult $subject,
        Closure      $proceed,
        $field,
        $condition = null
    ): mixed {
        if ($field === self::ORDER_TAG_FILTER) {
            if (!empty($condition)) {

                $values = array_values($condition['in']);
                if (count($values) > 1) {
                    foreach ($values as $value) {
                        $conditions[] = ['finset' => $value];
                    }
                } else {
                    $condition = ['finset' => $values[0]];
                }

                $condition = $conditions ?? $condition;
                $condition = $subject->getConnection()->prepareSqlCondition('cev.value', $condition);

                /**@var \Magento\Customer\Setup\CustomerSetup $customerSetup*/
                $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
                $attrId = $customerSetup->getAttributeId(Customer::ENTITY, self::CUSTOMER_ATTRIBUTE);

                $resource = $subject->getResource();
                $subject->getSelect()->joinLeft(
                    ['cev' => $resource->getTable('customer_entity_varchar')],
                    'cev.entity_id = main_table.customer_id AND cev.attribute_id =' . $attrId,
                    ['cev.value']
                );
                $subject->getSelect()->where($condition, null, Select::TYPE_CONDITION);
            }

            return $subject;
        }
        return $proceed($field, $condition);
    }
}