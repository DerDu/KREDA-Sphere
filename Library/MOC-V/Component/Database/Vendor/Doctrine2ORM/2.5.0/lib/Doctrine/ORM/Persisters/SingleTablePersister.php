<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\ORM\Persisters;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Persister for entities that participate in a hierarchy mapped with the
 * SINGLE_TABLE strategy.
 *
 * @author Roman Borschel <roman@code-factory.org>
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @author Alexander <iam.asm89@gmail.com>
 * @since  2.0
 * @link   http://martinfowler.com/eaaCatalog/singleTableInheritance.html
 */
class SingleTablePersister extends AbstractEntityInheritancePersister
{

    /**
     * {@inheritdoc}
     */
    protected function getDiscriminatorColumnTableName()
    {

        return $this->class->getTableName();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelectColumnsSQL()
    {

        if ($this->selectColumnListSql !== null) {
            return $this->selectColumnListSql;
        }

        $columnList[] = parent::getSelectColumnsSQL();

        $rootClass = $this->em->getClassMetadata( $this->class->rootEntityName );
        $tableAlias = $this->getSQLTableAlias( $rootClass->name );

        // Append discriminator column
        $discrColumn = $this->class->discriminatorColumn['name'];
        $columnList[] = $tableAlias.'.'.$discrColumn;

        $resultColumnName = $this->platform->getSQLResultCasing( $discrColumn );

        $this->rsm->setDiscriminatorColumn( 'r', $resultColumnName );
        $this->rsm->addMetaResult( 'r', $resultColumnName, $discrColumn );

        // Append subclass columns
        foreach ($this->class->subClasses as $subClassName) {
            $subClass = $this->em->getClassMetadata( $subClassName );

            // Regular columns
            foreach ($subClass->fieldMappings as $fieldName => $mapping) {
                if (isset( $mapping['inherited'] )) {
                    continue;
                }

                $columnList[] = $this->getSelectColumnSQL( $fieldName, $subClass );
            }

            // Foreign key columns
            foreach ($subClass->associationMappings as $assoc) {
                if (!$assoc['isOwningSide']
                    || !( $assoc['type'] & ClassMetadata::TO_ONE )
                    || isset( $assoc['inherited'] )
                ) {
                    continue;
                }

                foreach ($assoc['targetToSourceKeyColumns'] as $srcColumn) {
                    $className = isset( $assoc['inherited'] ) ? $assoc['inherited'] : $this->class->name;
                    $columnList[] = $this->getSelectJoinColumnSQL( $tableAlias, $srcColumn, $className );
                }
            }
        }

        $this->selectColumnListSql = implode( ', ', $columnList );

        return $this->selectColumnListSql;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSQLTableAlias( $className, $assocName = '' )
    {

        return parent::getSQLTableAlias( $this->class->rootEntityName, $assocName );
    }

    /**
     * {@inheritdoc}
     */
    protected function getInsertColumnList()
    {

        $columns = parent::getInsertColumnList();

        // Add discriminator column to the INSERT SQL
        $columns[] = $this->class->discriminatorColumn['name'];

        return $columns;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelectConditionSQL( array $criteria, $assoc = null )
    {

        $conditionSql = parent::getSelectConditionSQL( $criteria, $assoc );

        if ($conditionSql) {
            $conditionSql .= ' AND ';
        }

        return $conditionSql.$this->getSelectConditionDiscriminatorValueSQL();
    }

    /**
     * @return string
     */
    protected function getSelectConditionDiscriminatorValueSQL()
    {

        $values = array();

        if ($this->class->discriminatorValue !== null) { // discriminators can be 0
            $values[] = $this->conn->quote( $this->class->discriminatorValue );
        }

        $discrValues = array_flip( $this->class->discriminatorMap );

        foreach ($this->class->subClasses as $subclassName) {
            $values[] = $this->conn->quote( $discrValues[$subclassName] );
        }

        $values = implode( ', ', $values );
        $discColumn = $this->class->discriminatorColumn['name'];
        $tableAlias = $this->getSQLTableAlias( $this->class->name );

        return $tableAlias.'.'.$discColumn.' IN ('.$values.')';
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelectConditionCriteriaSQL( Criteria $criteria )
    {

        $conditionSql = parent::getSelectConditionCriteriaSQL( $criteria );

        if ($conditionSql) {
            $conditionSql .= ' AND ';
        }

        return $conditionSql.$this->getSelectConditionDiscriminatorValueSQL();
    }

    /**
     * {@inheritdoc}
     */
    protected function generateFilterConditionSQL( ClassMetadata $targetEntity, $targetTableAlias )
    {

        // Ensure that the filters are applied to the root entity of the inheritance tree
        $targetEntity = $this->em->getClassMetadata( $targetEntity->rootEntityName );
        // we don't care about the $targetTableAlias, in a STI there is only one table.

        return parent::generateFilterConditionSQL( $targetEntity, $targetTableAlias );
    }
}
