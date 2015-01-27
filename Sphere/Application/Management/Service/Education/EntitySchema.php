<?php
namespace KREDA\Sphere\Application\Management\Service\Education;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\Education
 */
abstract class EntitySchema extends AbstractService
{

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema( $Simulate = true )
    {

        /**
         * Table
         */
        $Schema = clone $this->getDatabaseHandler()->getSchema();
        $this->setTableSubject( $Schema );
        $tblLevel = $this->setTableLevel( $Schema );
        $this->setTableGroup( $Schema, $tblLevel );
        /**
         * Migration
         */
        $Statement = $this->getDatabaseHandler()->getSchema()->getMigrateToSql( $Schema,
            $this->getDatabaseHandler()->getDatabasePlatform()
        );
        $this->getDatabaseHandler()->addProtocol( __CLASS__ );
        if (!empty( $Statement )) {
            foreach ((array)$Statement as $Query) {
                $this->getDatabaseHandler()->addProtocol( $Query );
                if (!$Simulate) {
                    $this->getDatabaseHandler()->setStatement( $Query );
                }
            }
        }
        /**
         * Protocol
         */
        return $this->getDatabaseHandler()->getProtocol( $Simulate );
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableSubject( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblSubject' )) {
            $Table = $Schema->createTable( 'tblSubject' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblSubject' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblSubject', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblSubject', 'Acronym' )) {
            $Table->addColumn( 'Acronym', 'string' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTableLevel( Schema &$Schema )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblLevel' )) {
            $Table = $Schema->createTable( 'tblLevel' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblLevel' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblLevel', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblLevel
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTableGroup( Schema &$Schema, Table $tblLevel )
    {

        /**
         * Install
         */
        if (!$this->getDatabaseHandler()->hasTable( 'tblGroup' )) {
            $Table = $Schema->createTable( 'tblGroup' );
            $Column = $Table->addColumn( 'Id', 'bigint' );
            $Column->setAutoincrement( true );
            $Table->setPrimaryKey( array( 'Id' ) );
        }
        /**
         * Fetch
         */
        $Table = $Schema->getTable( 'tblGroup' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblGroup', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblGroup', 'tblLevel' )) {
            $Table->addColumn( 'tblLevel', 'bigint', array( 'notnull' => false ) );
            if ($this->getDatabaseHandler()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
                $Table->addForeignKeyConstraint( $tblLevel, array( 'tblLevel' ), array( 'Id' ) );
            }
        }
        return $Table;
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableSubject()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblSubject' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableLevel()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblLevel' );
    }

    /**
     * @return Table
     * @throws SchemaException
     */
    protected function getTableGroup()
    {

        return $this->getDatabaseHandler()->getSchema()->getTable( 'tblGroup' );
    }
}
