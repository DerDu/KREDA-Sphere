<?php
namespace KREDA\Sphere\Application\Management\Service\Person;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Common\AbstractService;

/**
 * Class EntitySchema
 *
 * @package KREDA\Sphere\Application\Management\Service\Person
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
        $tblPersonType = $this->setTablePersonType( $Schema );
        $this->setTablePerson( $Schema, $tblPersonType );
        /**
         * Migration & Protocol
         */
        $this->getDatabaseHandler()->addProtocol( __CLASS__ );
        $this->schemaMigration( $Schema, $Simulate );
        return $this->getDatabaseHandler()->getProtocol( $Simulate );
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     * @throws SchemaException
     */
    private function setTablePersonType( Schema &$Schema )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblPersonType' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPersonType', 'Name' )) {
            $Table->addColumn( 'Name', 'string' );
        }
    }

    /**
     * @param Schema $Schema
     * @param Table  $tblPersonType
     *
     * @throws SchemaException
     * @return Table
     */
    private function setTablePerson( Schema &$Schema, Table $tblPersonType )
    {

        /**
         * Install
         */
        $Table = $this->schemaTableCreate( $Schema, 'tblPerson' );
        /**
         * Upgrade
         */
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'Salutation' )) {
            $Table->addColumn( 'Salutation', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'FirstName' )) {
            $Table->addColumn( 'FirstName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'MiddleName' )) {
            $Table->addColumn( 'MiddleName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'LastName' )) {
            $Table->addColumn( 'LastName', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'Gender' )) {
            $Table->addColumn( 'Gender', 'string' );
        }
        if (!$this->getDatabaseHandler()->hasColumn( 'tblPerson', 'Birthday' )) {
            $Table->addColumn( 'Birthday', 'string' );
        }
        $this->schemaTableAddForeignKey( $Table, $tblPersonType );

        return $Table;
    }
}
