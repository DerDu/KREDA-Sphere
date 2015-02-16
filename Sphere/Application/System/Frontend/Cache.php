<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Cache\Frontend\Status;
use KREDA\Sphere\Common\Cache\Type\ApcSma;
use KREDA\Sphere\Common\Cache\Type\ApcUser;
use KREDA\Sphere\Common\Cache\Type\OpCache;
use KREDA\Sphere\Common\Cache\Type\TwigCache;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitDanger;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;
use KREDA\Sphere\Common\Frontend\Redirect;

/**
 * Class Cache
 *
 * @package KREDA\Sphere\Application\System\Frontend
 */
class Cache extends AbstractFrontend
{

    /**
     * @param bool $Clear
     *
     * @return Stage
     */
    public static function stageStatus( $Clear = false )
    {

        $View = new Stage();
        $View->setTitle( 'Cache' );
        $View->setDescription( 'Status' );
        if (isset( $_REQUEST['Clear'] ) || $Clear) {
            ApcSma::clearCache();
            ApcUser::clearCache();
            OpCache::clearCache();
            TwigCache::clearCache();
        }
        if (isset( $_REQUEST['Clear'] )) {
            $View->setContent( new Redirect( '/Sphere/System/Cache/Status', 0 ) );
            return $View;
        }

        $View->setContent(
            new FormDefault( array(
                new GridFormGroup( new GridFormRow(
                    new GridFormCol( new Status( new ApcSma() ) )
                ), new GridFormTitle( 'APC (SMA)' ) ),
                new GridFormGroup( new GridFormRow(
                    new GridFormCol( new Status( new ApcUser() ) )
                ), new GridFormTitle( 'APC (Info-User)' ) ),
                new GridFormGroup( new GridFormRow(
                    new GridFormCol( new Status( new OpCache() ) )
                ), new GridFormTitle( 'OPC' ) ),
                new GridFormGroup( new GridFormRow(
                    new GridFormCol( new Status( new TwigCache() ) )
                ), new GridFormTitle( 'Twig' ) ),
            ), new ButtonSubmitDanger( 'Clear', new FlashIcon() ), '/Client/Sphere/System/Cache/Status?Clear' )
        );
        return $View;
    }
}