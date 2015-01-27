<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;

/**
 * Class CogIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class CogIcon extends AbstractIcon implements IParameterInterface
{

    /**
     *
     */
    function __construct()
    {

        $this->setValue( AbstractIcon::ICON_COG );
    }
}
