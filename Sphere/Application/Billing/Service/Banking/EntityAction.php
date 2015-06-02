<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;


/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Account
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param $Id
     * @return bool|TblDebtor
     */
    protected function entityDebtorById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblDebtor', $Id );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param $DebtorNumber
     * @return TblDebtor[]|bool
     */
    protected function entityDebtorByDebtorNumber( $DebtorNumber )
    {
        $Entity = $this->getEntityManager()->getEntity('tblDebtor')->findBy( array(TblDebtor::ATTR_DEBTOR_NUMBER => $DebtorNumber) );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param $ServiceManagement_Person
     * @return TblDebtor[]|bool
     */
    protected function entityDebtorByServiceManagement_Person( $ServiceManagement_Person )
    {
        $Entity = $this->getEntityManager()->getEntity('tblDebtor')->findBy( array(TblDebtor::ATTR_SERVICE_MANAGEMENT_PERSON => $ServiceManagement_Person) );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param TblPerson $tblPerson
     * @return TblDebtor[]|bool
     */
    protected function entityDebtorAllByPerson( TblPerson $tblPerson )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblDebtor' )
            ->findBy( array( TblDebtor::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblDebtor $tblDebtor
     * @return bool
     */
    protected function actionRemoveBanking(
        TblDebtor $tblDebtor
    )
    {
        $Manager = $this->getEntityManager();

        $EntityItems = $Manager->getEntity( 'tblDebtor' )
            ->findBy( array(TblDebtor::ATTR_DEBTOR_NUMBER => $tblDebtor->getId() ) );
        if (null !== $EntityItems)
        {
            foreach($EntityItems as $Entity)
            {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
                $Manager->killEntity( $Entity );
            }
        }

        $Entity = $Manager->getEntity('tblDebtor')->findOneBy( array('Id'=>$tblDebtor->getId() ) );
        if (null !== $Entity)
        {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblDebtor $tblDebtor
     * @param TblCommodity $tblCommodity
     *
     * @return TblDebtorCommodity[]|bool
     */
    protected function entityDebtorCommodityAllByDebtorAndCommodity( TblDebtor $tblDebtor, TblCommodity $tblCommodity )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblDebtorCommodity' )
            ->findBy( array( TblDebtorCommodity::ATTR_TBL_DEBTOR => $tblDebtor->getId(), TblDebtorCommodity::ATTR_SERVICE_BILLING_COMMODITY => $tblCommodity->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }


    /**
     * @param $LeadTimeFollow
     * @param $LeadTimeFirst
     * @param $DebtorNumber
     * @param $ServiceManagement_Person
     * @return TblDebtor
     */
    protected function actionAddDebtor($DebtorNumber, $LeadTimeFirst, $LeadTimeFollow, $ServiceManagement_Person )
    {

        $Manager = $this->getEntityManager();

        $Entity = new TblDebtor();
        $Entity->setLeadTimeFirst( $LeadTimeFirst );
        $Entity->setLeadTimeFollow( $LeadTimeFollow );
        $Entity->setDebtorNumber( $DebtorNumber );
        $Entity->setServiceManagement_Person( $ServiceManagement_Person );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @return array|bool|TblDebtor[]
     */
    protected function entityDebtorAll()
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblDebtor' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

}