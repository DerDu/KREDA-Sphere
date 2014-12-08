<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity(readOnly=true)
 * @Table(name="viewToken")
 * @Cache(usage="READ_ONLY")
 */
class ViewToken
{

    /**
     * @Id
     * @Column(type="bigint")
     */
    private $tblToken;
    /**
     * @Column(type="string")
     */
    private $TokenIdentifier;

    /**
     * @return string
     */
    public function getTokenIdentifier()
    {

        return $this->TokenIdentifier;
    }

    /**
     * @return integer
     */
    public function getTblToken()
    {

        return $this->tblToken;
    }

    /**
     * @return array
     */
    function __toArray()
    {

        return array(
            'tblToken'        => $this->tblToken,
            'TokenIdentifier' => $this->TokenIdentifier
        );
    }

}