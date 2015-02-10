<?php
namespace KREDA\Sphere\Application\Management\Frontend\Subject;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Subject
 *
 * @package KREDA\Sphere\Application\Management\Subject
 */
class Subject extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function guiSubject()
    {

        $View = new Stage();
        $View->setTitle( 'Fächer' );
        $View->setDescription( '' );
        $View->setMessage( 'Der Inhalt folgt noch.' );
        $View->setContent( '' );
        return $View;
    }
}
