<?php

namespace Yamato\CustomerTag\Ui\Component\Listing\Column\Tag;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    protected $tag;

    public function __construct(\Yamato\CustomerTag\Helper\Tag $tag)
    {
        $this->tag = $tag;
    }

    public function toOptionArray()
    {
        return $this->tag->getTags();
    }
}
