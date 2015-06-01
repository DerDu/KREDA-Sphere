<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceAccount;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Billing\Service\Invoice\EntityAction;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Invoice extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Invoice', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

    }

    /**
     * @param int $Id
     *
     * @return bool|TblInvoice
     */
    public  function entityInvoiceById($Id)
    {
        return parent::entityInvoiceById($Id);
    }

    /**
     * @return bool|TblInvoice[]
     */
    public  function entityInvoiceAll()
    {
        return parent::entityInvoiceAll();
    }

    /**
     * @param $IsConfirmed
     * @return bool|Invoice\Entity\TblInvoice[]
     */
    public  function entityInvoiceAllByIsConfirmedState($IsConfirmed)
    {
        return parent::entityInvoiceAllByIsConfirmedState($IsConfirmed);
    }

    /**
     * @param $IsPaid
     * @return bool|Invoice\Entity\TblInvoice[]
     */
    public function entityInvoiceAllByIsPaidState($IsPaid)
    {
        return parent::entityInvoiceAllByIsPaidState($IsPaid);
    }

    /**
     * @param $IsVoid
     *
     * @return bool|Invoice\Entity\TblInvoice[]
     */
    public  function entityInvoiceAllByIsVoidState($IsVoid)
    {
        return parent::entityInvoiceAllByIsVoidState($IsVoid);
    }

    /**
     * @param int $Id
     *
     * @return bool|TblInvoiceItem
     */
    public  function entityInvoiceItemById($Id)
    {
        return parent::entityInvoiceItemById($Id);
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return bool|Invoice\Entity\TblInvoiceItem[]
     */
    public  function entityInvoiceItemAllByInvoice(TblInvoice $tblInvoice)
    {
        return parent::entityInvoiceItemAllByInvoice($tblInvoice);
    }

    /**
     * @param TblBasket $tblBasket
     * @param \DateTime $Date
     *
     * @return bool
     */
    public function executeCreateInvoiceListFromBasket(
        TblBasket $tblBasket,
        $Date
    )
    {
        return $this->actionCreateInvoiceListFromBasket($tblBasket, $Date);
    }


    /**
     * @param TblInvoice $tblInvoice
     *
     * @return string
     */
    public function executeConfirmInvoice(
        TblInvoice $tblInvoice
    )
    {
        if ($this->actionConfirmInvoice($tblInvoice))
        {
            return new Success( 'Die Rechnung wurde erfolgreich bestätigt und freigegeben' )
                .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed', 0 );
        }
        else
        {
            return new Warning( 'Die Rechnung wurde konnte nicht bestätigt und freigegeben werden' )
                .new Redirect( '/Sphere/Billing/Invoice/Edit', 2, array( 'Id' => $tblInvoice->getId()) );
        }
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return string
     */
    public function executeCancelInvoice(
        TblInvoice $tblInvoice
    )
    {
        if (!$tblInvoice->getIsConfirmed())
        {
            if ($this->actionCancelInvoice($tblInvoice))
            {
                return new Success( 'Die Rechnung wurde erfolgreich storniert' )
                    .new Redirect( '/Sphere/Billing/Invoice/IsNotConfirmed', 0 );
            }
            else
            {
                return new Warning( 'Die Rechnung konnte nicht storniert werden' )
                    .new Redirect( '/Sphere/Billing/Invoice/Edit', 2, array('Id'=>$tblInvoice->getId()) );
            }
        }
        else
        {
            //TODO cancel confirmed invoice
        }
    }

    /**
     * @param AbstractType $View
     * @param TblInvoiceItem $tblInvoiceItem
     * @param $InvoiceItem
     *
     * @return AbstractType|string
     */
    public function executeEditInvoiceItem(
        AbstractType &$View = null,
        TblInvoiceItem $tblInvoiceItem,
        $InvoiceItem
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $InvoiceItem
        ) {
            return $View;
        }

        $Error = false;

        if (isset( $InvoiceItem['Price'] ) && empty(  $InvoiceItem['Price'] )) {
            $View->setError( 'InvoiceItem[Price]', 'Bitte geben Sie einen Preis an' );
            $Error = true;
        }
        if (isset( $InvoiceItem['Quantity'] ) && empty(  $InvoiceItem['Quantity'] )) {
            $View->setError( 'InvoiceItem[Quantity]', 'Bitte geben Sie eine Menge an' );
            $Error = true;
        }

        if (!$Error) {
            if ($this->actionEditInvoiceItem(
                $tblInvoiceItem,
                $InvoiceItem['Price'],
                $InvoiceItem['Quantity']
            )) {
                $View .= new Success( 'Änderungen gespeichert, die Daten werden neu geladen...' )
                    .new Redirect( '/Sphere/Billing/Invoice/Edit', 1, array( 'Id' => $tblInvoiceItem->getTblInvoice()->getId()) );
            } else {
                $View .= new Danger( 'Änderungen konnten nicht gespeichert werden' )
                    .new Redirect( '/Sphere/Billing/Invoice/Edit', 2, array( 'Id' => $tblInvoiceItem->getTblInvoice()->getId()) );
            };
        }
        return $View;
    }

    /**
     * @param TblInvoiceItem $tblInvoiceItem
     *
     * @return string
     */
    public function executeRemoveInvoiceItem(
        TblInvoiceItem $tblInvoiceItem
    )
    {
        if ($this->actionRemoveInvoiceItem($tblInvoiceItem))
        {
            return new Success( 'Der Artikel ' . $tblInvoiceItem->getItemName() . ' wurde erfolgreich entfernt' )
            .new Redirect( '/Sphere/Billing/Invoice/Edit', 0, array( 'Id' => $tblInvoiceItem->getTblInvoice()->getId()) );
        }
        else
        {
            return new Warning( 'Der Artikel ' . $tblInvoiceItem->getItemName() . ' konnte nicht entfernt werden' )
            .new Redirect( '/Sphere/Billing/Invoice/Edit', 2, array( 'Id' => $tblInvoiceItem->getTblInvoice()->getId()) );
        }
    }
}
