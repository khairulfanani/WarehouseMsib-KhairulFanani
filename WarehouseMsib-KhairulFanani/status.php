<?php
include_once './classes/Gudang.php';

$database = new Database();
$db = $database->getConnection();
$gudang = new Gudang($db);

if (isset($_GET['id']) && isset($_GET['status'])) {
    $gudang->id = $_GET['id'];
    $gudang->status = $_GET['status'];
    if ($gudang->updateStatus()) {
        header("Location: index.php");
    }
}
?>