<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblPerson")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblPerson extends AbstractEntity
{

    const ATTR_TBL_PERSON_TYPE = 'tblPersonType';
    const ATTR_TBL_PERSON_GENDER = 'tblPersonGender';
    const ATTR_TBL_PERSON_SALUTATION = 'tblPersonSalutation';

    const ATTR_FIRST_NAME = 'FirstName';
    const ATTR_MIDDLE_NAME = 'MiddleName';
    const ATTR_LAST_NAME = 'LastName';
    const ATTR_BIRTHDAY = 'Birthday';
    const ATTR_BIRTHPLACE = 'Birthplace';
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="string")
     */
    protected $FirstName;
    /**
     * @Column(type="string")
     */
    protected $MiddleName;
    /**
     * @Column(type="string")
     */
    protected $LastName;
    /**
     * @Column(type="date")
     */
    protected $Birthday;
    /**
     * @Column(type="string")
     */
    protected $Birthplace;
    /**
     * @Column(type="string")
     */
    protected $Nationality;
    /**
     * @OneToOne(targetEntity="TblPersonType",fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    protected $tblPersonType;
    /**
     * @OneToOne(targetEntity="TblPersonSalutation",fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    protected $tblPersonSalutation;
    /**
     * @OneToOne(targetEntity="TblPersonGender",fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    protected $tblPersonGender;

    /**
     * @return string
     */
    public function getBirthplace()
    {

        return $this->Birthplace;
    }

    /**
     * @param string $Birthplace
     */
    public function setBirthplace( $Birthplace )
    {

        $this->Birthplace = $Birthplace;
    }

    /**
     * @return string
     */
    public function getNationality()
    {

        return $this->Nationality;
    }

    /**
     * @param string $Nationality
     */
    public function setNationality( $Nationality )
    {

        $this->Nationality = $Nationality;
    }

    /**
     * @return bool|TblPersonType
     */
    public function getTblPersonType()
    {

        if (null === $this->tblPersonType) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonTypeById( $this->tblPersonType );
        }
    }

    /**
     * @param null|TblPersonType $tblPersonType
     */
    public function setTblPersonType( TblPersonType $tblPersonType = null )
    {

        $this->tblPersonType = ( null === $tblPersonType ? null : $tblPersonType->getId() );
    }

    /**
     * @return bool|TblPersonSalutation
     */
    public function getTblPersonSalutation()
    {

        if (null === $this->tblPersonSalutation) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonSalutationById( $this->tblPersonSalutation );
        }
    }

    /**
     * @param null|TblPersonSalutation $tblPersonSalutation
     */
    public function setTblPersonSalutation( TblPersonSalutation $tblPersonSalutation = null )
    {

        $this->tblPersonSalutation = ( null === $tblPersonSalutation ? null : $tblPersonSalutation->getId() );
    }

    /**
     * @return bool|TblPersonGender
     */
    public function getTblPersonGender()
    {

        if (null === $this->tblPersonGender) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonGenderById( $this->tblPersonGender );
        }
    }

    /**
     * @param null|TblPersonGender $tblPersonGender
     */
    public function setTblPersonGender( TblPersonGender $tblPersonGender = null )
    {

        $this->tblPersonGender = ( null === $tblPersonGender ? null : $tblPersonGender->getId() );
    }

    /**
     * @return string
     */
    public function getFullName()
    {

        return $this->getFirstName().( $this->getMiddleName() ? ' '.$this->getMiddleName() : '' ).' '.$this->getLastName();
    }

    /**
     * @return string
     */
    public function getFirstName()
    {

        return $this->FirstName;
    }

    /**
     * @param string $FirstName
     */
    public function setFirstName( $FirstName )
    {

        $this->FirstName = $FirstName;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {

        return $this->MiddleName;
    }

    /**
     * @param string $MiddleName
     */
    public function setMiddleName( $MiddleName )
    {

        $this->MiddleName = $MiddleName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {

        return $this->LastName;
    }

    /**
     * @param string $LastName
     */
    public function setLastName( $LastName )
    {

        $this->LastName = $LastName;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {

        /** @var \DateTime $Birthday */
        $Birthday = $this->Birthday;
        return $Birthday->format( 'd.m.Y' );
    }

    /**
     * @param \DateTime $Birthday
     */
    public function setBirthday( \DateTime $Birthday )
    {

        $this->Birthday = $Birthday;
    }

    /**
     * @return integer
     */
    public function getId()
    {

        return $this->Id;
    }

    /**
     * @param integer $Id
     */
    public function setId( $Id )
    {

        $this->Id = $Id;
    }
}
