<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountAccessList;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountSession;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Account
 */
abstract class EntityAction extends EntitySchema
{

    /** @var TblAccount[] $EntityAccountByUsernameCache */
    private static $EntityAccountByUsernameCache = array();
    /** @var TblAccount[] $EntityAccountByIdCache */
    private static $EntityAccountByIdCache = array();
    /** @var TblAccountType[] $EntityAccountTypeByIdCache */
    private static $EntityAccountTypeByIdCache = array();
    /** @var TblAccountType[] $EntityAccountTypeByNameCache */
    private static $EntityAccountTypeByNameCache = array();
    /** @var TblAccountRole[] $EntityAccountRoleByIdCache */
    private static $EntityAccountRoleByIdCache = array();
    /** @var TblAccountRole[] $EntityAccountRoleByNameCache */
    private static $EntityAccountRoleByNameCache = array();
    /** @var TblAccountSession[] $EntityAccountBySessionCache */
    private static $EntityAccountBySessionCache = array();

    /**
     * @param string           $Username
     * @param string           $Password
     * @param TblAccountType   $tblAccountType
     * @param TblAccountRole   $tblAccountRole
     * @param null|TblToken    $tblToken
     * @param null|TblPerson   $tblPerson
     * @param null|TblConsumer $tblConsumer
     *
     * @return TblAccount
     */
    protected function actionCreateAccount(
        $Username,
        $Password,
        $tblAccountType,
        $tblAccountRole = null,
        $tblToken = null,
        $tblPerson = null,
        $tblConsumer = null
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        if (null === $Entity) {
            $Entity = new TblAccount( $Username );
            $Entity->setPassword( hash( 'sha256', $Password ) );
            $Entity->setTblAccountType( $tblAccountType );
            $Entity->setTblAccountRole( $tblAccountRole );
            $Entity->setServiceGatekeeperToken( $tblToken );
            $Entity->setServiceManagementPerson( $tblPerson );
            $Entity->setServiceGatekeeperConsumer( $tblConsumer );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Username
     *
     * @return bool|TblAccount
     */
    protected function entityAccountByUsername( $Username )
    {

        if (isset( self::$EntityAccountByUsernameCache[$Username] )) {
            return self::$EntityAccountByUsernameCache[$Username];
        }
        $Entity = $this->getEntityManager()->getEntity( 'TblAccount' )
            ->findOneBy( array( TblAccount::ATTR_USERNAME => $Username ) );
        self::$EntityAccountByUsernameCache[$Username] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccount
     */
    protected function entityAccountById( $Id )
    {

        if (isset( self::$EntityAccountByIdCache[$Id] )) {
            return self::$EntityAccountByIdCache[$Id];
        }
        $Entity = $this->getEntityManager()->getEntityById( 'TblAccount', $Id );
        self::$EntityAccountByIdCache[$Id] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountSession
     */
    protected function entitySessionById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountSession', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblToken $tblToken
     *
     * @return bool|Entity\TblAccount[]
     */
    protected function entityAccountAllByToken( TblToken $tblToken )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccount' )->findBy( array(
            TblAccount::ATTR_SERVICE_GATEKEEPER_TOKEN => $tblToken->getId()
        ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|Entity\TblAccount[]
     */
    protected function entityAccountAllByPerson( TblPerson $tblPerson )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccount' )->findBy( array(
            TblAccount::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId()
        ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param TblConsumer $tblConsumer
     *
     * @return bool|Entity\TblAccount[]
     */
    protected function entityAccountAllByConsumer( TblConsumer $tblConsumer )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccount' )->findBy( array(
            TblAccount::ATTR_SERVICE_GATEKEEPER_CONSUMER => $tblConsumer->getId()
        ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountType
     */
    protected function entityAccountTypeById( $Id )
    {

        if (isset( self::$EntityAccountTypeByIdCache[$Id] )) {
            return self::$EntityAccountTypeByIdCache[$Id];
        }
        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountType', $Id );
        self::$EntityAccountTypeByIdCache[$Id] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblAccountType[]
     */
    protected function entityAccountTypeAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccountType' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAccountType
     */
    protected function entityAccountTypeByName( $Name )
    {

        if (isset( self::$EntityAccountTypeByNameCache[$Name] )) {
            return self::$EntityAccountTypeByNameCache[$Name];
        }
        $Entity = $this->getEntityManager()->getEntity( 'TblAccountType' )
            ->findOneBy( array(
                TblAccountType::ATTR_NAME => $Name
            ) );
        self::$EntityAccountTypeByNameCache[$Name] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountRole
     */
    protected function entityAccountRoleById( $Id )
    {

        if (isset( self::$EntityAccountRoleByIdCache[$Id] )) {
            return self::$EntityAccountRoleByIdCache[$Id];
        }
        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountRole', $Id );
        self::$EntityAccountRoleByIdCache[$Id] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblAccountRole
     */
    protected function entityAccountRoleByName( $Name )
    {

        if (isset( self::$EntityAccountRoleByNameCache[$Name] )) {
            return self::$EntityAccountRoleByNameCache[$Name];
        }
        $Entity = $this->getEntityManager()->getEntity( 'TblAccountRole' )->findOneBy( array(
            TblAccountRole::ATTR_NAME => $Name
        ) );
        self::$EntityAccountRoleByNameCache[$Name] = $Entity;
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblAccountRole[]
     */
    protected function entityAccountRoleAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccountRole' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param string $Username
     * @param string $Password
     * @param TblAccountType $tblAccountType
     *
     * @return bool|TblAccount
     */
    protected function entityAccountByCredential( $Username, $Password, TblAccountType $tblAccountType )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccount' )
            ->findOneBy( array(
                TblAccount::ATTR_USERNAME => $Username,
                TblAccount::ATTR_PASSWORD => hash( 'sha256', $Password ),
                TblAccount::ATTR_TBL_ACCOUNT_TYPE => $tblAccountType->getId()
            ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblAccountRole $tblAccountRole
     *
     * @return bool|TblAccess[]
     */
    protected function entityAccessAllByAccountRole( TblAccountRole $tblAccountRole )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccountAccessList' )->findBy( array(
            TblAccountAccessList::ATTR_TBL_ACCOUNT_ROLE => $tblAccountRole->getId()
        ) );
        array_walk( $EntityList, function ( TblAccountAccessList &$V ) {

            $V = $V->getServiceGatekeeperAccess();
        } );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param TblAccount $tblAccount
     *
     * @return bool|TblAccountSession[]
     */
    protected function entitySessionAllByAccount( TblAccount $tblAccount )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblAccountSession' )->findBy( array(
            TblAccountSession::ATTR_TBL_ACCOUNT => $tblAccount->getId()
        ) );
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param TblAccount  $tblAccount
     * @param null|string $Session
     * @param integer     $Timeout
     *
     * @return TblAccountSession
     */
    protected function actionCreateSession( TblAccount $tblAccount, $Session = null, $Timeout = 1800 )
    {

        if (null === $Session) {
            $Session = session_id();
        }
        $Manager = $this->getEntityManager();
        /** @var TblAccountSession $Entity */
        $Entity = $Manager->getEntity( 'TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
        }
        $Entity = new TblAccountSession( $Session );
        $Entity->setTblAccount( $tblAccount );
        $Entity->setTimeout( time() + $Timeout );
        $Manager->saveEntity( $Entity );
        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblAccountType
     */
    protected function actionCreateAccountType( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountType' )
            ->findOneBy( array( TblAccountType::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccountType();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblAccountRole
     */
    protected function actionCreateAccountRole( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountRole' )
            ->findOneBy( array( TblAccountRole::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAccountRole();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param null|string $Session
     *
     * @return bool
     */
    protected function actionDestroySession( $Session = null )
    {

        if (null === $Session) {
            $Session = session_id();
        }

        $Manager = $this->getEntityManager();
        /** @var TblAccountSession $Entity */
        $Entity = $Manager->getEntity( 'TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $tblAccount
     *
     * @return bool
     */
    protected function actionDestroyAccount( TblAccount $tblAccount )
    {

        $Manager = $this->getEntityManager();
        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById( 'TblAccount', $tblAccount->getId() );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param string          $Password
     * @param null|TblAccount $tblAccount
     *
     * @return bool
     */
    protected function actionChangePassword( $Password, TblAccount $tblAccount = null )
    {

        if (null === $tblAccount) {
            $tblAccount = $this->entityAccountBySession();
        }
        $Manager = $this->getEntityManager();
        /**
         * @var TblAccount $Protocol
         * @var TblAccount $Entity
         */
        $Entity = $Manager->getEntityById( 'TblAccount', $tblAccount->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setPassword( hash( 'sha256', $Password ) );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param null|string $Session
     *
     * @return bool|TblAccount
     */
    protected function entityAccountBySession( $Session = null )
    {

        if (null === $Session) {
            $Session = session_id();
        }
        if (isset( self::$EntityAccountBySessionCache[$Session] )) {
            return self::$EntityAccountBySessionCache[$Session];
        }
        /** @var TblAccountSession $Entity */
        $Entity = $this->getEntityManager()->getEntity( 'TblAccountSession' )
            ->findOneBy( array( TblAccountSession::ATTR_SESSION => $Session ) );
        if (null === $Entity) {
            return false;
        } else {
            return self::$EntityAccountBySessionCache[$Session] = $Entity->getTblAccount();
        }
    }

    /**
     * @param TblToken        $tblToken
     * @param null|TblAccount $tblAccount
     *
     * @return bool
     */
    protected function actionChangeToken( TblToken $tblToken, TblAccount $tblAccount = null )
    {

        if (null === $tblAccount) {
            $tblAccount = $this->entityAccountBySession();
        }
        $Manager = $this->getEntityManager();
        /**
         * @var TblAccount $Protocol
         * @var TblAccount $Entity
         */
        $Entity = $Manager->getEntityById( 'TblAccount', $tblAccount->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity && $Protocol->getServiceGatekeeperToken() != $tblToken) {
            $Entity->setServiceGatekeeperToken( $tblToken );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblConsumer     $tblConsumer
     * @param null|TblAccount $tblAccount
     *
     * @return bool
     */
    protected function actionChangeConsumer( TblConsumer $tblConsumer, TblAccount $tblAccount = null )
    {

        if (null === $tblAccount) {
            $tblAccount = $this->entityAccountBySession();
        }
        $Manager = $this->getEntityManager();
        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById( 'TblAccount', $tblAccount->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setServiceGatekeeperConsumer( $tblConsumer );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblPerson       $tblPerson
     * @param null|TblAccount $tblAccount
     *
     * @return bool
     */
    protected function actionChangePerson( TblPerson $tblPerson, TblAccount $tblAccount = null )
    {

        if (null === $tblAccount) {
            $tblAccount = $this->entityAccountBySession();
        }
        $Manager = $this->getEntityManager();
        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById( 'TblAccount', $tblAccount->getId() );
        if (null !== $Entity) {
            $Entity->setServiceManagementPerson( $tblPerson );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblAccountRole $TblAccountRole
     * @param TblAccess      $TblAccess
     *
     * @return TblAccountAccessList
     */
    protected function actionAddRoleAccess(
        TblAccountRole $TblAccountRole,
        TblAccess $TblAccess
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountAccessList' )
            ->findOneBy( array(
                TblAccountAccessList::ATTR_TBL_ACCOUNT_ROLE          => $TblAccountRole->getId(),
                TblAccountAccessList::ATTR_SERVICE_GATEKEEPER_ACCESS => $TblAccess->getId()
            ) );
        if (null === $Entity) {
            $Entity = new TblAccountAccessList();
            $Entity->setTblAccountRole( $TblAccountRole );
            $Entity->setServiceGatekeeperAccess( $TblAccess );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblAccountRole $TblAccountRole
     * @param TblAccess      $TblAccess
     *
     * @return bool
     */
    protected function actionRemoveRoleAccess(
        TblAccountRole $TblAccountRole,
        TblAccess $TblAccess
    ) {

        $Manager = $this->getEntityManager();
        /** @var TblAccountAccessList $Entity */
        $Entity = $Manager->getEntity( 'TblAccountAccessList' )
            ->findOneBy( array(
                TblAccountAccessList::ATTR_TBL_ACCOUNT_ROLE          => $TblAccountRole->getId(),
                TblAccountAccessList::ATTR_SERVICE_GATEKEEPER_ACCESS => $TblAccess->getId()
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
