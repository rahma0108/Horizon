<?php




include '../controller/eventsc.php';
include '../model/events.php';
$pc=new eventsC();
$p=new event($_POST['title'],$_POST['descreption'],$_POST['sporttype'],$_POST['location'],$_POST['event_date'],$_POST['max_participants'],$_POST['created_by'],$_POST['image']);
$pc->addEvent($p);
header('Location:admin.php');

