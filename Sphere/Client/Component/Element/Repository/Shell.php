<?php
namespace KREDA\Sphere\Client\Component\Element\Repository;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\IElementInterface;

/**
 * Class Shell
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository
 */
abstract class Shell extends Element implements IElementInterface
{

    /**
     * @return string
     */
    function __toString()
    {

        return $this->getContent();
    }

    abstract public function getContent();

}