<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Commodity as Frontend;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Commodity extends Common
{
    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Commodity/Create', 'Leistung anlegen', new PersonIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Create', 'Artikel anlegen', new PersonIcon()
        );
    }

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity', __CLASS__.'::frontendStatus'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Create', __CLASS__.'::frontendCreate'
        )
            ->setParameterDefault( 'Commodity', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Edit', __CLASS__.'::frontendEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'Commodity', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Create', __CLASS__.'::frontendItemCreate'
        )
            ->setParameterDefault( 'Item', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Commodity/Item/Select', __CLASS__.'::frontendItemSelect'
        )
            ->setParameterDefault( 'Id', null );
    }

    /**
     * @return Stage
     */
    public static function frontendStatus()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendStatus();
    }

    /**
     * @param $Commodity
     *
     * @return Stage
     */
    public static function frontendCreate( $Commodity )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendCreate( $Commodity );
    }

    /**
     * @param $Id
     * @param $Commodity
     *
     * @return Stage
     */
    public static function frontendEdit( $Id, $Commodity )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendEdit( $Id, $Commodity );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemSelect ( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemSelect( $Id);
    }

    /**
     * @param $Item
     *
     * @return Stage
     */
    public static function frontendItemCreate( $Item )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendItemCreate( $Item );
    }
}