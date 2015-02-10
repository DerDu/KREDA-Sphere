<?php

namespace KREDA\Sphere\Application\Management\Frontend\Group;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Group
 * @package KREDA\Sphere\Application\Management\Frontend\Group
 */
class Group extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function guiGroup()
    {

        $View = new Stage();
        $View->setTitle('Klasse');
        $View->setDescription('1-10 und a-c');
        $View->setMessage('Der Inhalt folgt noch.');
        $View->setContent('');
        return $View;
    }
}