<?php
namespace KREDA\Sphere\Application\Management\Service\Group\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Service\Group;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblGroup")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblGroup extends AbstractEntity
{

    const ATTR_NAME = 'Name';

    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="string")
     */
    protected $Description;
    /**
     * @Column(type="boolean")
     */
    protected $IsEditable;

    /**
     * @param string $Name
     */
    function __construct( $Name )
    {

        $this->IsEditable = true;
        $this->Name = $Name;
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

    /**
     * @return boolean
     */
    public function getIsEditable()
    {

        return $this->IsEditable;
    }

    /**
     * @param boolean $IsEditable
     */
    public function setIsEditable( $IsEditable )
    {

        $this->IsEditable = $IsEditable;
    }
}
