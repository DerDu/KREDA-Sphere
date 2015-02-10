<?php

namespace KREDA\Sphere\Application\Management\Frontend\Period;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Period
 * @package KREDA\Sphere\Application\Management\Frontend\Period
 */
class Period extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function guiPeriod()
    {

        $View = new Stage();
        $View->setTitle('Zeiten');
        $View->setDescription('');
        $View->setMessage('Der Inhalt folgt noch.');
        $View->setContent('');
        return $View;
    }
}