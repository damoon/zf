<?php

$def = new Zend_Entity_Mapper_Definition_Entity("Clinic_Bed", array("table" => "beds"));

$def->addPrimaryKey("id");
$def->addManyToOne("station", array(
    "class"       => "Clinic_Station",
    "columnName"  => "station_id",
    "propertyRef" => "id",
    "load"        => "directly",
));
$def->addCollection("occupancyPlan", array(
    "key" => "bed_id",
    "relation" => new Zend_Entity_Mapper_Definition_Relation_OneToMany("id", array("class" => "Clinic_Occupancy")),
));

return $def;