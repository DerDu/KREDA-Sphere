<?php
namespace KREDA\Sphere\Application\Billing\Service\Invoice;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItemAccount;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceAccount;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Invoice
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblInvoice
     */
    protected function entityInvoiceById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblInvoice', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblInvoice[]
     */
    protected function entityInvoiceAll()
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblInvoice' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $IsPaid
     * @return TblInvoice[]|bool
     */
    protected function entityInvoiceAllByIsPaidState( $IsPaid )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblInvoice' )
            ->findBy( array( TblInvoice::ATTR_IS_PAID => $IsPaid ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param $IsVoid
     * @return TblInvoice[]|bool
     */
    protected function entityInvoiceAllByIsVoidState( $IsVoid )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblInvoice' )
            ->findBy( array( TblInvoice::ATTR_IS_VOID => $IsVoid ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblInvoiceItem
     */
    protected function entityInvoiceItemById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblInvoiceItem', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblInvoice $tblInvoice
     * @return string
     */
    protected function sumPriceItemAllStringByInvoice( TblInvoice $tblInvoice)
    {
        return str_replace('.', ',', round($this->sumPriceItemAllByInvoice( $tblInvoice), 2)) . " €";
    }

    /**
     * @param TblInvoice $tblInvoice
     * @return float
     */
    protected function sumPriceItemAllByInvoice( TblInvoice $tblInvoice)
    {
        $sum = 0.00;
        $tblInvoiceItemByInvoice = $this->entityInvoiceItemAllByInvoice( $tblInvoice);
        /** @var TblInvoiceItem $tblInvoiceItem */
        foreach($tblInvoiceItemByInvoice as $tblInvoiceItem)
        {
            $sum += $tblInvoiceItem->getItemPrice() * $tblInvoiceItem->getItemQuantity();
        }

        return $sum;
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return TblInvoiceItem[]|bool
     */
    protected function entityInvoiceItemAllByInvoice( TblInvoice $tblInvoice )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblInvoiceItem' )
            ->findBy( array( TblInvoiceItem::ATTR_TBL_INVOICE => $tblInvoice->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblDebtor $tblDebtor
     * @return bool
     */
    protected function checkInvoiceFromDebtorIsPaidByDebtor( TblDebtor $tblDebtor )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblInvoice' )->findOneBy(array(TblInvoice::ATTR_IS_PAID => $tblDebtor->getId()));
        return ( null === $Entity ? false : true );
    }

    /**
     * @param TblBasket $tblBasket
     * @param $Date
     * @param $TempTblInvoiceList
     *
     * @return bool
     */
    protected function actionCreateInvoiceListFromBasket(
        TblBasket $tblBasket,
        $Date,
        $TempTblInvoiceList
    )
    {
        $Manager = $this->getEntityManager();

        // TODO tblAddress

        foreach ($TempTblInvoiceList as $TempTblInvoice)
        {
            $tblDebtor = Billing::serviceBanking()->entityDebtorById($TempTblInvoice['tblDebtor']);
            $tblPersonDebtor = Management::servicePerson()->entityPersonById($tblDebtor->getServiceManagement_Person());
            $tblPerson = Management::servicePerson()->entityPersonById($TempTblInvoice['tblPerson']);
            $Entity = new TblInvoice();
            $Entity->setIsPaid( false );
            $Entity->setIsVoid( false );
            $Entity->setNumber( "40000000" );
            $Entity->setInvoiceDate( ( new \DateTime( $Date ) )->sub( new \DateInterval( 'P' . Billing::serviceBanking()->entityLeadTimeByDebtor( $tblDebtor ) .'D' ) ) );
            $Entity->setPaymentDate( new \DateTime( $Date ) );
            $Entity->setDiscount( 0 );
            $Entity->setDebtorFirstName( $tblPersonDebtor->getFirstName() );
            $Entity->setDebtorLastName( $tblPersonDebtor->getLastName() );
            $Entity->setDebtorSalutation( $tblPersonDebtor->getTblPersonSalutation()->getName() );
            $Entity->setDebtorNumber($tblDebtor->getDebtorNumber());
            $Entity->setServiceManagementPerson( $tblPerson );

            $Manager->SaveEntity( $Entity );

            $Entity->setNumber( (int)$Entity->getNumber() + $Entity->getId() );
            $Manager->SaveEntity( $Entity );

            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );

            foreach ($TempTblInvoice['Commodities'] as $CommodityId)
            {
                $tblCommodity = Billing::serviceCommodity()->entityCommodityById($CommodityId);
                $tblBasketItemAllByBasketAndCommodity = Billing::serviceBasket()->entityBasketItemAllByBasketAndCommodity($tblBasket, $tblCommodity);
                foreach ($tblBasketItemAllByBasketAndCommodity as $tblBasketItem)
                {
                    $tblItem = $tblBasketItem->getServiceBillingCommodityItem()->getTblItem();

                    if (!($tblItem->getServiceManagementCourse()) && !($tblItem->getServiceManagementStudentChildRank()))
                    {
                        $this->actionCreateInvoiceItem($tblCommodity,$tblItem, $tblBasket, $tblBasketItem, $Entity);
                    }
                    else if ($tblItem->getServiceManagementCourse() && !($tblItem->getServiceManagementStudentChildRank()))
                    {
                        if (( $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson ) )
                            && $tblItem->getServiceManagementCourse()->getId() == $tblStudent->getServiceManagementCourse()->getId()
                        )
                        {
                            $this->actionCreateInvoiceItem($tblCommodity,$tblItem, $tblBasket, $tblBasketItem, $Entity);
                        }
                    }
                    else if (!($tblItem->getServiceManagementCourse()) && $tblItem->getServiceManagementStudentChildRank())
                    {
                        if (( $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson ) )
                            && $tblItem->getServiceManagementStudentChildRank()->getId() == $tblStudent->getTblChildRank()->getId())
                        {
                            $this->actionCreateInvoiceItem($tblCommodity,$tblItem, $tblBasket, $tblBasketItem, $Entity);
                        }
                    }
                    else if ($tblItem->getServiceManagementCourse() && $tblItem->getServiceManagementStudentChildRank())
                    {
                        if (( $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson ) )
                            && $tblItem->getServiceManagementCourse()->getId() == $tblStudent->getServiceManagementCourse()->getId()
                            && $tblItem->getServiceManagementStudentChildRank()->getId() == $tblStudent->getTblChildRank()->getId())
                        {
                            $this->actionCreateInvoiceItem($tblCommodity,$tblItem, $tblBasket, $tblBasketItem, $Entity);
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param TblCommodity $tblCommodity
     * @param TblItem $tblItem
     * @param TblBasket $tblBasket
     * @param TblBasketItem $tblBasketItem
     * @param TblInvoice $tblInvoice
     */
    private function actionCreateInvoiceItem(
        TblCommodity $tblCommodity,
        TblItem $tblItem,
        TblBasket $tblBasket,
        TblBasketItem $tblBasketItem,
        TblInvoice $tblInvoice
    )
    {
        $Entity = new TblInvoiceItem();
        $Entity->setCommodityName( $tblCommodity->getName() );
        $Entity->setCommodityDescription( $tblCommodity->getDescription() );
        $Entity->setItemName( $tblItem->getName() );
        $Entity->setItemDescription( $tblItem->getDescription() );
        if ($tblCommodity->getTblCommodityType()->getName() == 'Einzelleistung') {
            $Entity->setItemPrice( $tblBasketItem->getPrice() );
        } else {
            $Entity->setItemPrice( $tblBasketItem->getPrice() / Billing::serviceBasket()->countPersonByBasket( $tblBasket ) );
        }
        $Entity->setItemQuantity( $tblBasketItem->getQuantity() );
        $Entity->setTblInvoice( $tblInvoice );

        $this->getEntityManager()->SaveEntity( $Entity );
        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
            $Entity );

        $tblItemAccountList = Billing::serviceCommodity()->entityItemAccountAllByItem( $tblItem );
        /** @var TblItemAccount $tblItemAccount */
        foreach ($tblItemAccountList as $tblItemAccount)
        {
            $EntityItemAccount = new TblInvoiceAccount();
            $EntityItemAccount->setTblInvoiceItem( $Entity );
            $EntityItemAccount->setServiceBilling_Account( $tblItemAccount->getServiceBilling_Account() );

            $this->getEntityManager()->SaveEntity($EntityItemAccount);
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $EntityItemAccount );
        }
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return bool
     */
    protected function actionCancelInvoice(
        TblInvoice $tblInvoice
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblInvoice $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoice', $tblInvoice->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setIsVoid( true );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return bool
     */
    protected function actionPayInvoice(
        TblInvoice $tblInvoice
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblInvoice $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoice', $tblInvoice->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setIsPaid( true );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblInvoiceItem $tblInvoiceItem
     * @param $Price
     * @param $Quantity
     *
     * @return bool
     */
    protected function actionEditInvoiceItem(
        TblInvoiceItem $tblInvoiceItem,
        $Price,
        $Quantity
    ) {

        $Manager = $this->getEntityManager();

        /** @var TblInvoiceItem $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoiceItem', $tblInvoiceItem->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setItemPrice( str_replace( ',', '.', $Price ) );
            $Entity->setItemQuantity( str_replace( ',', '.', $Quantity ) );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblInvoiceItem $tblInvoiceItem
     *
     * @return bool
     */
    protected function actionRemoveInvoiceItem(
        TblInvoiceItem $tblInvoiceItem
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblInvoiceItem' )->findOneBy(
            array(
                'Id' => $tblInvoiceItem->getId()
            ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }
}
