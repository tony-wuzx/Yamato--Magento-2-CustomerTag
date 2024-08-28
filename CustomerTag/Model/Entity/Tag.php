<?php

declare(strict_types=1);

namespace Yamato\CustomerTag\Model\Entity;

use Yamato\CustomerTag\Api\Data\TagInterface;
use Magento\Framework\DataObject;

class Tag extends DataObject implements TagInterface
{

    /**
     * @inheritDoc
     */
    public function setValue(?string $value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /**
     * @inheritDoc
     */
    public function setLabel(?string $label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->getData(self::LABEL);
    }
}
