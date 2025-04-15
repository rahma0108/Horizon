<?php
require_once '../../../Controller/CoursController.php';

if (isset($_GET['id'])) {
    $controller = new CoursController();
    $controller->deleteCours($_GET['id']);
}

header('Location: list.php');
exit();
?>
