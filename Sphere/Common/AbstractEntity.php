<?php
namespace KREDA\Sphere\Common;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * Class AbstractEntity
 *
 * - Id (bigint)
 * - EntityCreate (datetime)
 * - EntityUpdate (datetime)
 *
 * @package KREDA\Sphere\Common
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 */
abstract class AbstractEntity extends AbstractExtension
{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="datetime")
     */
    protected $EntityCreate;
    /**
     * @Column(type="datetime")
     */
    protected $EntityUpdate;

    /**
     * @PrePersist
     */
    final public function lifecycleCreate()
    {

        if (empty( $this->EntityCreate )) {
            $this->EntityCreate = new \DateTime();
        }
    }

    /**
     * @PreUpdate
     */
    final public function lifecycleUpdate()
    {

        $this->EntityUpdate = new \DateTime();
    }

    /**
     * @return integer
     */
    final public function getId()
    {

        return $this->Id;
    }

    /**
     * @param integer $Id
     */
    final public function setId( $Id )
    {

        $this->Id = $Id;
    }

    /**
     * @throws \Exception
     */
    final public function __toArray()
    {

        $Array = get_object_vars( $this );
        array_walk( $Array, function ( &$V ) {

            if (is_object( $V )) {
                if ($V instanceof \DateTime) {
                    $V = $V->format( 'd.m.Y H:i:s' );
                }
            }
        } );

        return $Array;
    }
}
