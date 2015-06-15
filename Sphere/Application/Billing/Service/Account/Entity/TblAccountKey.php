<?php
namespace KREDA\Sphere\Application\Billing\Service\Account\Entity;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAccountKey")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblAccountKey extends AbstractEntity
{

    const ATTR_TBL_ACCOUNT_KEY_TYPE = 'tblAccountKeyType';

    /**
     * @Column(type="date")
     */
    protected $ValidFrom;
    /**
     * @Column(type="string")
     */
    protected $Value;
    /**
     * @Column(type="date")
     */
    protected $ValidTo;
    /**
     * @Column(type="string")
     */
    protected $Description;
    /**
     * @Column(type="integer")
     */
    protected $Code;
    /**
     * @Column(type="bigint")
     */
    protected $tblAccountKeyType;

    /**
     * @return string $validFrom
     */
    public function getValidFrom()
    {
        return $this->ValidFrom;
    }

    /**
     * @param \DateTime $ValidFrom
     */
    public function setValidFrom($ValidFrom)
    {

        $this->ValidFrom = $ValidFrom;
    }

    /**
     * @return string $Value
     */
    public function getValue()
    {

        return $this->Value;
    }

    /**
     * @param string $Value
     */
    public function setValue($Value)
    {

        $this->Value = $Value;
    }

    /**
     * @return string $ValidTo
     */
    public function getValidTo()
    {

        return $this->ValidTo;
    }

    /**
     * @param \DateTime $ValidTo
     */
    public function setValidTo($ValidTo)
    {

        $this->ValidTo = $ValidTo;
    }

    /**
     * @return string $Description
     */
    public function getDescription()
    {

        return $this->Description;
    }

    /**
     * @param string $Description
     */
    public function setDescription($Description)
    {

        $this->Description = $Description;
    }

    /**
     * @return integer $Code
     */
    public function getCode()
    {

        return $this->Code;
    }

    /**
     * @param integer $Code
     */
    public function setCode($Code)
    {

        $this->Code = $Code;
    }

    /**
     * @return bool|TblAccountKeyType
     */
    public function getTableAccountKey()
    {

        if (null === $this->tblAccountKeyType ){
            return false;
        } else {
            return Billing::serviceAccount()->entityAccountKeyTypeById( $this->tblAccountKeyType);
        }
    }

    /**
     * @param bool|TblAccountKeyType $tblAccountKeyType
     */
    public function setTableAccountKeyType( tblAccountKeyType $tblAccountKeyType = null )
    {
        $this->tblAccountKeyType = ( null === $tblAccountKeyType ? null : $tblAccountKeyType->getId() );
    }
}