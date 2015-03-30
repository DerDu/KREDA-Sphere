<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;

/**
 * Class Protocol
 *
 * @package KREDA\Sphere\Application\System\Frontend\Protocol
 */
class Protocol extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageStatus()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Protokoll' );
        $View->setDescription( 'Status' );

        $View->setContent(
            new TableData( '/Sphere/System/Table/Protocol', null,
                array(
                    'Id'     => '#',
                    'Editor' => 'Editor',
                    'Origin' => 'Origin',
                    'Commit' => 'Commit'
                ),
                array(
                    "order"      => array(
                        array( 0, 'desc' )
                    ),
                    "columnDefs" => array(
                        array( "orderable" => false, "targets" => 1 ),
                        array( "orderable" => false, "targets" => 2 ),
                        array( "orderable" => false, "targets" => 3 )
                    )
                )
            )
        );

        return $View;
    }

}
