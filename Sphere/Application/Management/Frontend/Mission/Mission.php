<?php

namespace KREDA\Sphere\Application\Management\Frontend\Mission;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Mission
 * @package KREDA\Sphere\Application\Management\Frontend\Mission
 */
class Mission extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function guiMission()
    {

        $View = new Stage();
        $View->setTitle('Aufträge');
        $View->setDescription('');
        $View->setMessage('Der Inhalt folgt noch.');
        $View->setContent('');
        return $View;
    }
}