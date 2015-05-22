<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblCommodity")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblCommodity extends AbstractEntity
{
    /**
     * @Column(type="bigint")
     */
    protected $tblCommodityType;

    /**
     * @Column(type="bigint")
     */
    protected $tblDebtorCommodity;

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @Column(type="string")
     */
    protected $Description;

    /**
     * @return bool|TblCommodityType
     */
    public function getTblCommodityType()
    {
        if (null === $this->tblCommodityType) {
            return false;
        } else {
            return Billing::serviceCommodity()->entityCommodityTypeById( $this->tblCommodityType );
        }
    }

    /**
     * @param null|TblCommodityType $tblCommodityType
     */
    public function setTblCommodityType( TblCommodityType $tblCommodityType = null )
    {
        $this->tblCommodityType = ( null === $tblCommodityType ? null : $tblCommodityType->getId() );
    }

    /**
     * @return bool|TblDebtorCommodity
     */
    public function getTblDebtorCommodity()
    {
        if (null === $this->tblDebtorCommodity) {
            return false;
        } else {
            return Billing::serviceCommodity()->entityDebtorCommodityById( $this->tblDebtorCommodity );
        }
    }

    /**
     * @param null|TblDebtorCommodity $tblDebtorCommodity
     */
    public function setTblDebtorCommodity( TblDebtorCommodity $tblDebtorCommodity = null )
    {
        $this->tblDebtorCommodity = ( null === $tblDebtorCommodity ? null : $tblDebtorCommodity->getId() );
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param string $Description
     */
    public function setDescription( $Description )
    {
        $this->Description = $Description;
    }
}