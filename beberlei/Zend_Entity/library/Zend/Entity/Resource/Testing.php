<?php

class Zend_Entity_Resource_Testing implements Zend_Entity_Resource_Interface
{
    /**
     * @var array
     */
    protected $_defMap = array();

    /**
     * Add new definition to testing map.
     *
     * @param Zend_Entity_Mapper_Definition_Entity $entityDefinition
     */
    public function addDefinition(Zend_Entity_Mapper_Definition_Entity $entityDefinition)
    {
        $this->_defMap[$entityDefinition->getClass()] = $entityDefinition;
    }

    /**
     * Get an Entity Mapper Definition by the name of the Entity
     *
     * @param  string $entityName
     * @return Zend_Entity_Mapper_Definition_Entity
     */
    public function getDefinitionByEntityName($entityName)
    {
        if(!isset($this->_defMap[$entityName])) {
            require_once "Zend/Entity/Exception.php";
            throw new Zend_Entity_Exception("No Definition for the Entity '".$entityName."' was set.");
        }
        $this->_defMap[$entityName]->compile($this);

        return $this->_defMap[$entityName];
    }
}