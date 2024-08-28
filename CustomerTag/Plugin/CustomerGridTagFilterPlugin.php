<?php
/**
 * @author Zhixing Wu <acesharkpee@gmail.com>
 * @create_time 8/27/24 5:18 PM
 */

namespace Yamato\CustomerTag\Plugin;

class CustomerGridTagFilterPlugin
{

    public function aroundApply(
        \Magento\Framework\View\Element\UiComponent\DataProvider\RegularFilter $subject,
        callable $proceed,
        \Magento\Framework\Data\Collection $collection,
        \Magento\Framework\Api\Filter $filter
    )
    {
        if ($collection instanceof \Magento\Customer\Model\ResourceModel\Grid\Collection
            && $filter->getField() === 'tags'
        ) {
            $values = !is_array($filter->getValue()) ? explode(',', $filter->getValue()) : $filter->getValue();
            foreach ($values as $item) {
                $collection->addFieldToFilter($filter->getField(), ['finset' => $item]);
            }
        } else {
            $proceed($collection, $filter);
        }
    }
}