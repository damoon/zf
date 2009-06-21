<?php
/**
 * Mapper
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * 
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so we can send you a copy immediately.
 *
 * @category   Zend
 * @category   Zend_Entity
 * @copyright  Copyright (c) 2009 Benjamin Eberlei
 * @license    New BSD License
 */

class Zend_Entity_Mapper_Persister_Simple implements Zend_Entity_Mapper_Persister_Interface
{
    /**
     * @var array
     */
    protected $_properties = array();

    /**
     * @var array
     */
    protected $_toOneRelations = array();

    /**
     * @var array
     */
    protected $_toManyCascadeRelations = array();

    /**
     * @var string
     */
    protected $_class;

    /**
     * @var Zend_Entity_Mapper_Definition_PrimaryKey
     */
    protected $_primaryKey;

    /**
     * @var string
     */
    protected $_table;

    /**
     * @var Zend_Entity_Mapper_StateTransformer_Abstract
     */
    protected $_stateTransformer = null;

    /**
     * Initialize is called once on each persister to gather information on how to perform the persist operation.
     *
     * @param  Zend_Entity_Mapper_Definition_Entity $entityDef
     * @param  Zend_Entity_Resource_Interface     $defMap
     * @return void
     */
    public function initialize(Zend_Entity_Mapper_Definition_Entity $entityDef, Zend_Entity_Resource_Interface $defMap)
    {
        $properties = array();
        foreach($entityDef->getProperties() AS $property) {
            if(!($property instanceof Zend_Entity_Mapper_Definition_Formula)) {
                $properties[] = $property;
            }
        }
        $this->_properties       = $properties;
        foreach($entityDef->getRelations() AS $relation) {
            if($relation->isOwning()) {
                $this->_toOneRelations[$relation->getPropertyName()] = $relation;
            }
        }
        foreach($entityDef->getExtensions() AS $collection) {
            if($this->isCascadingToManyCollection($collection)) {
                $this->_toManyCascadeRelations[] = $collection;
            }
        }
        $this->_class            = $entityDef->getClass();
        $this->_primaryKey       = $entityDef->getPrimaryKey();
        $this->_table            = $entityDef->getTable();
        $this->_stateTransformer = $entityDef->getStateTransformer();
    }

    /**
     * Is this is a cascading collection?
     * 
     * @param Zend_Entity_Mapper_Definition_Collection $collection
     * @return boolean
     */
    private function isCascadingToManyCollection(Zend_Entity_Mapper_Definition_Collection $collection)
    {
        return( ($collection->getCollectionType() == Zend_Entity_Mapper_Definition_Collection::COLLECTION_RELATION) &&
            ($collection->getRelation()->getCascade() != Zend_Entity_Mapper_Definition_Property::CASCADE_NONE) );
    }

    /**
     * @ignore
     * @param Zend_Entity_Interface $relatedObject
     * @param Zend_Entity_Mapper_Definition_AbstractRelation $relationDef
     * @param Zend_Entity_Manager_Interface $entityManager
     * @return mixed
     */
    public function evaluateRelatedObject($relatedObject, $relationDef, $entityManager)
    {
        if($relatedObject instanceof Zend_Entity_Mapper_LazyLoad_Entity) {
            $value = $relatedObject->getLazyLoadEntityId();
        } else if($relatedObject instanceof Zend_Entity_Interface) {
            $foreignKeyProperty = $relationDef->getForeignKeyPropertyName();
            $relatedObjectState = $relatedObject->getState();
            $value = $relatedObjectState[$foreignKeyProperty];

            switch($relationDef->getCascade()) {
                case Zend_Entity_Mapper_Definition_Property::CASCADE_ALL:
                case Zend_Entity_Mapper_Definition_Property::CASCADE_SAVE:
                    $entityManager->save($relatedObject);
                    break;
            }
        } else {
            $value = null;
        }

        return $value;
    }

    /**
     * @ignore
     * @param Zend_Entity_Collection_Interface $relatedCollection
     * @param Zend_Entity_Mapper_Definition_Collection $collectionDef
     * @param Zend_Entity_Manager_Interface $entityManager
     */
    public function evaluateRelatedCollection($relatedCollection, $collectionDef, $entityManager)
    {
        if($relatedCollection instanceof Zend_Entity_Collection_Interface && $relatedCollection->wasLoadedFromDatabase() == true) {
            switch($collectionDef->getRelation()->getCascade()) {
                case Zend_Entity_Mapper_Definition_Property::CASCADE_ALL:
                case Zend_Entity_Mapper_Definition_Property::CASCADE_SAVE:
                    foreach($relatedCollection AS $collectionEntity) {
                        $entityManager->save($collectionEntity);
                    }
                    break;
            }

            if($collectionDef->getRelation() instanceof Zend_Entity_Mapper_Definition_ManyToManyRelation) {
                $db = $entityManager->getAdapter();
                
                foreach($relatedCollection->getAdded() AS $relatedEntity) {

                }
                foreach($relatedCollection->getRemoved() AS $relatedEntity) {
                    
                }
            }
        }
    }

    /**
     * Save entity into persistence based on the persisters scope
     *
     * @param  Zend_Entity_Interface $entity
     * @param  Zend_Entity_Manager_Interface $entityManager
     * @return void
     */
    public function save(Zend_Entity_Interface $entity, Zend_Entity_Manager_Interface $entityManager)
    {
        $entityState = $this->_stateTransformer->getState($entity);
        $dbState = $this->transformEntityToDbState($entityState, $entityManager);
        $this->doPerformSave($entity, $dbState, $entityManager);
        $this->updateCollections($entityState, $entityManager);
    }

    /**
     * @ignore
     * @param array $entityState
     * @param Zend_Entity_Manager_Interface $entityManager
     */
    public function updateCollections($entityState, $entityManager)
    {
        foreach($this->_toManyCascadeRelations AS $collectionDef) {
            $relatedCollection = $entityState[$collectionDef->getPropertyName()];

            $this->evaluateRelatedCollection($relatedCollection, $collectionDef, $entityManager);
        }
    }

    /**
     * @ignore
     * @param  array $entityState
     * @param  Zend_Entity_Manager_Interface $entityManager
     * @return array
     */
    public function transformEntityToDbState($entityState, $entityManager)
    {
        $dbState = array();
        foreach($this->_properties AS $property) {
            $propertyName = $property->getPropertyName();
            $columnName   = $property->getColumnName();
            if(!array_key_exists($propertyName, $entityState)) {
                require_once "Zend/Entity/Exception.php";
                throw new Zend_Entity_Exception("Missing property '".$propertyName."' in entity state. Does getState() return this value?");
            }
            $propertyValue = $property->castPropertyToSqlType($entityState[$propertyName]);
            $dbState[$columnName] = $propertyValue;
        }
        foreach($this->_toOneRelations AS $relation) {
            $propertyName = $relation->getPropertyName();
            $relatedObject      = $entityState[$propertyName];
            $dbState[$relation->getColumnName()] = $this->evaluateRelatedObject(
                $relatedObject,
                $relation,
                $entityManager
            );
        }
        return $dbState;
    }

    /**
     * @ignore
     * @param Zend_Entity_Interface $entity
     * @param array $dbState
     * @param Zend_Entity_Manager_Interface $entityManager
     */
    public function doPerformSave($entity, $dbState, $entityManager)
    {
        $dbAdapter = $entityManager->getAdapter();
        $pk        = $this->_primaryKey;
        $tableName = $this->_table;
        $identityMap = $entityManager->getIdentityMap();
        if($identityMap->contains($entity) == false) {
            $dbState = array_merge(
                $dbState,
                $pk->applyNextSequenceId($dbAdapter, $dbState)
            );
            $dbAdapter->insert($tableName, $dbState);
            $key = $pk->lastSequenceId($dbAdapter, $dbState);
            $this->_stateTransformer->setId($entity, $pk->getPropertyName(), $key);

            $identityMap->addObject(
                $this->_class,
                $key,
                $entity
            );
        } else {
            $where = $pk->buildWhereCondition(
                $dbAdapter,
                $tableName,
                $identityMap->getPrimaryKey($entity)
            );
            $dbState = $pk->removeSequenceFromState($dbState);
            $dbAdapter->update($tableName, $dbState, $where);
        }
    }

    /**
     * Remove entity from persistence based on the persisters scope
     *
     * @ignore
     * @param  Zend_Entity_Interface $entity
     * @param  Zend_Entity_Manager_Interface $entityManager
     * @return void
     */
    public function delete(Zend_Entity_Interface $entity, Zend_Entity_Manager_Interface $entityManager)
    {
        $identityMap = $entityManager->getIdentityMap();
        $entityState = $this->_stateTransformer->getState($entity);
        $pk  = $this->_primaryKey;
        if($identityMap->contains($entity) == false) {
            throw new Exception("Cannot update entity with unknown primary identification state into database.");
        }

        $db          = $entityManager->getAdapter();
        $tableName   = $this->_table;
        $whereClause = $pk->buildWhereCondition($db, $tableName, $identityMap->getPrimaryKey($entity));
        $db->delete($tableName, $whereClause);
        $entity->setState($pk->getEmptyKeyProperties());
    }
}