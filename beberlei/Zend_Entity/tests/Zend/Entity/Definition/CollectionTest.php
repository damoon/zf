<?php

class Zend_Entity_Definition_CollectionTest extends Zend_Entity_Definition_TestCase
{
    public function testCreateCollectionPopulatesPropertyName()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);

        $this->assertEquals(self::TEST_PROPERTY, $colDef->getPropertyName());
    }

    public function testSetGetPropertyName()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setPropertyName(self::TEST_PROPERTY2);

        $this->assertEquals(self::TEST_PROPERTY2, $colDef->getPropertyName());
    }

    public function testSetGetTable()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setTable(self::TEST_TABLE);

        $this->assertEquals(self::TEST_TABLE, $colDef->getTable());
    }

    public function testSetGetKey()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setKey(self::TEST_PROPERTY2);

        $this->assertEquals(self::TEST_PROPERTY2, $colDef->getKey());
    }

    public function testSetGetWhereClause()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setWhere("foo");

        $this->assertEquals("foo", $colDef->getWhere());
    }

    public function testCollectionCompileRequiresARelationThrowsExceptionOtherwise()
    {
        $this->setExpectedException("Zend_Entity_Exception");

        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->compile($this->createEntityDefinitionMock(), $this->createEntityResourceMock());
    }

    public function testCollectionCompileSetsTableFromRelationIfNoneIsset()
    {
        $colDef = $this->createCompileableCollection();
        $this->assertNull($colDef->getTable());

        $colDef->compile($this->createEntityDefinitionMock(), $this->createEntityResourceMockWithDefitionByClassNameExpectsGetTable());
        $this->assertEquals(self::TEST_TABLE, $colDef->getTable());
    }

    public function testCollectionCompileRequiresKeyFieldNotNull()
    {
        $this->setExpectedException("Zend_Entity_Exception");

        $colDef = $this->createCompileableCollection();
        $colDef->setKey(null);

        $colDef->compile($this->createEntityDefinitionMock(), $this->createEntityResourceMockWithDefitionByClassNameExpectsGetTable());
    }

    public function testSetGetOrderBy()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setOrderBy("foo");

        $this->assertEquals("foo", $colDef->getOrderBy());
    }

    public function testSetGetMapKey()
    {
        $key = "keyName";

        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setMapKey($key);

        $this->assertEquals($key, $colDef->getMapKey());
    }

    public function testSetGetElement()
    {
        $element = "elementName";

        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setElement($element);

        $this->assertEquals($element, $colDef->getElement());
    }

    public function testCompileElementCollection()
    {
        $colDef = $this->createCompileableElementsCollection();
        $colDef->compile($this->createEntityDefinitionMock(), $this->createEntityResourceMock());
    }

    public function testCompileElementCollection_WithoutTableName_ThrowsException()
    {
        $this->setExpectedException("Zend_Entity_Exception");

        $colDef = $this->createCompileableElementsCollection();
        $colDef->setTable(null);
        $colDef->compile($this->createEntityDefinitionMock(), $this->createEntityResourceMock());
    }

    public function testCompileElementCollection_WithoutElement_ThrowsException()
    {
        $this->setExpectedException("Zend_Entity_Exception");

        $mapKey = "keyName";

        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setTable(self::TEST_TABLE);
        $colDef->setMapKey($mapKey);
        $colDef->setKey(self::TEST_PROPERTY2);

        $colDef->compile($this->createEntityDefinitionMock(), $this->createEntityResourceMock());
    }

    public function testCompileElementCollection_WithoutMapKey_ThrowsException()
    {
        $this->setExpectedException("Zend_Entity_Exception");

        $element = "keyName";

        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setTable(self::TEST_TABLE);
        $colDef->setElement($element);
        $colDef->setKey(self::TEST_PROPERTY2);

        $colDef->compile($this->createEntityDefinitionMock(), $this->createEntityResourceMock());
    }

    public function testGetFetch_Default()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $this->assertNull($colDef->getFetch());
    }

    public function testSetFetch()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setFetch(Zend_Entity_Definition_Property::FETCH_SELECT);

        $this->assertEquals(
            Zend_Entity_Definition_Property::FETCH_SELECT,
            $colDef->getFetch()
        );
    }

    public function testGetDefaultInverse()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);

        $this->assertFalse($colDef->getInverse());
    }

    public function testGetSetInverse()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setInverse(true);

        $this->assertTrue($colDef->getInverse());
    }

    public function testSetMapKey_NonString_ThrowsException()
    {
        $this->setExpectedException("Zend_Entity_Exception");

        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setMapKey(new stdClass());
    }

    public function testSetElement_NonString_ThrowsException()
    {
        $this->setExpectedException("Zend_Entity_Exception");

        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setElement(new stdClass());
    }

    /**
     * @return Zend_Entity_Definition_Collection
     */
    protected function createCompileableCollection()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $relationMock = $this->getMock('Zend_Entity_Definition_AbstractRelation', array(), array("propertyName"));
        $colDef->setRelation($relationMock);
        $colDef->setKey(self::TEST_PROPERTY2);

        return $colDef;
    }

    /**
     * @return Zend_Entity_Definition_Collection
     */
    protected function createCompileableElementsCollection()
    {
        $colDef = new Zend_Entity_Definition_Collection(self::TEST_PROPERTY);
        $colDef->setTable(self::TEST_TABLE);
        $colDef->setMapKey("mapKey");
        $colDef->setElement("element");
        $colDef->setKey(self::TEST_PROPERTY2);

        return $colDef;
    }

    /**
     * @return Zend_Entity_MetadataFactory_Interface
     */
    protected function createEntityResourceMockWithDefitionByClassNameExpectsGetTable()
    {
        $entityDefinitionMock = $this->createEntityDefinitionMock();
        $entityDefinitionMock->expects($this->once())
                             ->method('getTable')
                             ->will($this->returnValue(self::TEST_TABLE));

        $mock = $this->createEntityResourceMock();
        $mock->expects($this->once())
             ->method('getDefinitionByEntityName')
             ->will($this->returnValue($entityDefinitionMock));

        return $mock;
    }
}