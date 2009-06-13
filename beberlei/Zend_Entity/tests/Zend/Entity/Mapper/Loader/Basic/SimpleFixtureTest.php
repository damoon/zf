<?php

class Zend_Entity_Mapper_Loader_Basic_SimpleFixtureTest extends Zend_Entity_Mapper_Loader_SimpleFixtureTestCase
{
    /**
     * @param Zend_Entity_Mapper_Definition_Entity $def
     * @return Zend_Entity_Mapper_Loader_Basic
     */
    public function createLoader(Zend_Entity_Mapper_Definition_Entity $def)
    {
        return new Zend_Entity_Mapper_Loader_Basic($def);
    }

    public function testCreateSimpleEntityFromRow()
    {
        $loader = $this->getLoader();

        $row = $this->getDummyDataRow();
        $state = $this->getDummyDataState();

        $entity = $loader->createEntityFromRow($row, $this->createEntityManager());

        $this->assertEquals($state, $entity->getState());
    }

    public function testLoadRowForSimpleEntity()
    {
        $entity = new Zend_TestEntity1;
        $loader = $this->getLoader();
        $row = $this->getDummyDataRow();
        $state = $this->getDummyDataState();

        $loader->loadRow($entity, $row, $this->createEntityManager());

        $this->assertEquals($state, $entity->getState());
    }

    public function testCheckOnIdentityMapIsPerformedBeforeCreatingNewEntityFromRow()
    {
        $loader = $this->getLoader();
        $row = $this->getDummyDataRow();

        $this->identityMap = $this->createIdentityMapMock(0);
        $this->identityMap->expects($this->once())->method('hasObject')->will($this->returnValue(true));
        $this->identityMap->expects($this->once())->method('getObject')->will($this->returnValue('foo'));
        
        $entityManager = $this->createEntityManager();

        $entity = $loader->createEntityFromRow($row, $entityManager);

        $this->assertEquals('foo', $entity);
    }

    public function testProcessResultsetInEntityMode()
    {
        $loader = $this->getLoader();
        $row = $this->getDummyDataRow();
        $state = $this->getDummyDataState();

        $stmt = new Zend_Entity_DbStatementMock();
        $stmt->appendToFetchStack($row);

        $collection = $loader->processResultset($stmt, $this->createEntityManager(), Zend_Entity_Manager::FETCH_ENTITIES);

        $this->assertTrue($collection instanceof Zend_Entity_Collection);
        $this->assertEquals(1, count($collection));

        $entity = $collection[0];
        $this->assertEquals($state, $entity->getState());
    }

    public function testProcessResultsetInArrayMode()
    {
        $loader = $this->getLoader();
        $row = $this->getDummyDataRow();
        $state = $this->getDummyDataState();

        $stmt = new Zend_Entity_DbStatementMock();
        $stmt->appendToFetchStack($row);

        $array = $loader->processResultset($stmt, $this->createEntityManager(), Zend_Entity_Manager::FETCH_ARRAY);

        $this->assertTrue(is_array($array));
        $this->assertEquals(1, count($array));

        $this->assertEquals($state, $array[0]);
    }

    public function testLoadRowWithMissingColumnsThrowsException()
    {
        $this->setExpectedException("Zend_Entity_Exception");

        $entity = new Zend_TestEntity1;
        $loader = $this->getLoader();
        $rowMissingColumn = array(self::TEST_A_ID_COLUMN => 1);

        $loader->loadRow($entity, $rowMissingColumn, $this->createEntityManager());
    }
}