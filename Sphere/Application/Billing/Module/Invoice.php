<?php
namespace KREDA\Sphere\Application\Billing\Module;

use KREDA\Sphere\Application\Billing\Frontend\Invoice as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Module
 */
class Invoice extends Common
{
    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @return void
     */
    protected static function setupApplicationNavigation()
    {
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Commodity/Select', 'Fakturierung anlegen', new PersonIcon()
        );
    }

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {
        self::$Configuration = $Configuration;

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Commodity/Select', __CLASS__.'::frontendBasketCommoditySelect'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Create', __CLASS__.'::frontendBasketCreate'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Item', __CLASS__.'::frontendBasketItemStatus'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Item/Remove', __CLASS__.'::frontendBasketItemRemove'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Item/Edit', __CLASS__.'::frontendBasketItemEdit'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'BasketItem', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Person/Select', __CLASS__.'::frontendBasketPersonSelect'
        )
            ->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Person/Add', __CLASS__.'::frontendBasketPersonAdd'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'PersonId', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Person/Remove', __CLASS__.'::frontendBasketPersonRemove'
        )
            ->setParameterDefault( 'Id', null )
            ->setParameterDefault( 'PersonId', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Billing/Invoice/Basket/Summary', __CLASS__.'::frontendBasketSummary'
        )
            ->setParameterDefault( 'Id', null );
    }

    /**
     * @return Stage
     */
    public static function frontendBasketCommoditySelect()
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketCommoditySelect();
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketItemStatus( $Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketItemStatus( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketCreate( $Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketCreate( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketItemRemove( $Id)
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketItemRemove( $Id );
    }

    /**
     * @param $Id
     * @param $BasketItem
     *
     * @return Stage
     */
    public static function frontendBasketItemEdit( $Id, $BasketItem )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketItemEdit( $Id, $BasketItem );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketPersonSelect( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketPersonSelect( $Id );
    }

    /**
     * @param $Id
     * @param $PersonId
     *
     * @return Stage
     */
    public static function frontendBasketPersonAdd( $Id, $PersonId )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketPersonAdd( $Id, $PersonId );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketPersonRemove( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketPersonRemove( $Id );
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketSummary( $Id )
    {
        self::setupModuleNavigation();
        self::setupApplicationNavigation();
        return Frontend::frontendBasketSummary( $Id );
    }
}
