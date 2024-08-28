<?php
declare(strict_types=1);

namespace Yamato\CustomerTag\Api\Data;

interface TagInterface
{

    const VALUE = 'value';
    const LABEL = 'label';

    /**
     * @param string|null $value
     * @return $this
     */
    public function setValue(?string $value);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string|null $label
     * @return $this
     */
    public function setLabel(?string $label);

    /**
     * @return string
     */
    public function getLabel();
}
