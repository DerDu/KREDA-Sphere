<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblConsumer")
 * @Cache(usage="READ_ONLY")
 */
class TblConsumer extends AbstractEntity
{

    const ATTR_NAME = 'Name';
    const ATTR_SUFFIX = 'DatabaseSuffix';

    /**
     * @OneToOne(fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    public $serviceManagement_Address;
    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="string")
     */
    protected $TableSuffix;
    /**
     * @Column(type="string")
     */
    protected $DatabaseSuffix;

    /**
     * @param string $Suffix
     */
    function __construct( $Suffix )
    {

        $this->TableSuffix = $Suffix;
        $this->DatabaseSuffix = $Suffix;
    }

    /**
     * @return string
     */
    public function getDatabaseSuffix()
    {

        return $this->DatabaseSuffix;
    }

    /**
     * @param string $DatabaseSuffix
     */
    public function setDatabaseSuffix( $DatabaseSuffix )
    {

        $this->DatabaseSuffix = $DatabaseSuffix;
    }

    /**
     * @return string
     */
    public function getTableSuffix()
    {

        return $this->TableSuffix;
    }

    /**
     * @param string $TableSuffix
     */
    public function setTableSuffix( $TableSuffix )
    {

        $this->TableSuffix = $TableSuffix;
    }

    /**
     * @return bool|TblAddress
     */
    public function getServiceManagementAddress()
    {

        if (null === $this->serviceManagement_Address) {
            return false;
        } else {
            return Management::serviceAddress( $this )->entityAddressById( $this->serviceManagement_Address );
        }
    }

    /**
     * @param null|TblAddress $tblAddress
     */
    public function setServiceManagementAddress( TblAddress $tblAddress = null )
    {

        $this->serviceManagement_Address = ( null === $tblAddress ? null : $tblAddress->getId() );
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->Name;
    }

    /**
     * @param string $Name
     */
    public function setName( $Name )
    {

        $this->Name = $Name;
    }
}
