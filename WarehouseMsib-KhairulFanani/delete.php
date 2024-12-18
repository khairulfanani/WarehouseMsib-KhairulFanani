<?php
include_once './classes/Gudang.php';

$database = new Database();
$db = $database->getConnection();
$gudang = new Gudang($db);

if (isset($_GET['id'])) {
    $gudang->id = $_GET['id'];
    if ($gudang->delete()) {
        session_start();
        $_SESSION['message'] = "Data berhasil dihapus!";
        $_SESSION['alert_type'] = "success";
        header("Location: index.php");
    }
}
?>