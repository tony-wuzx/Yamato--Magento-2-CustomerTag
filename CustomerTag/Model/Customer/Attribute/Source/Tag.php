<?php
namespace Yamato\CustomerTag\Model\Customer\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Tag extends AbstractSource implements OptionSourceInterface, SourceInterface
{
    protected $tag;

    public function __construct(
        \Yamato\CustomerTag\Helper\Tag $tag
    ) {
        $this->tag = $tag;
    }

    /**
     * @inheritdoc
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->tag->getTags();
        }

        return $this->_options;
    }
}
