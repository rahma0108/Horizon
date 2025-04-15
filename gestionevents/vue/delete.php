<?php
include '../controller/eventsc.php';
$pc=new eventsC();
$pc->deleteEvent($_GET["id"]);
header('Location:admincontrole.php');