<?php

namespace Yamato\CustomerTag\Block\Adminhtml\System\Config\Form\Field;

class Tag extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn('value', ['label' => __('ID')]);
        $this->addColumn('label', ['label' => __('Label')]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
