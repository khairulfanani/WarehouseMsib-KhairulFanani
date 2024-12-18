<?php
include_once './classes/Gudang.php';

$database = new Database();
$db = $database->getConnection();
$gudang = new Gudang($db);

if (isset($_GET['id'])) {
    $gudang->id = $_GET['id'];
    $stmt = $gudang->readOne();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}

$errors = [];

if ($_POST) {
    $gudang->id = $_POST['id'];
    $gudang->name = $_POST['name'];
    $gudang->location = $_POST['location'];
    $gudang->capacity = $_POST['capacity'];
    $gudang->opening_hour = $_POST['opening_hour'];
    $gudang->closing_hour = $_POST['closing_hour'];

    if (empty($gudang->name)) {
        $errors[] = "Nama gudang harus diisi.";
    }
    if (empty($gudang->location)) {
        $errors[] = "Lokasi harus diisi.";
    }
    if (empty($gudang->capacity)) {
        $errors[] = "Kapasitas harus diisi.";
    } elseif (!is_numeric($gudang->capacity)) {
        $errors[] = "Kapasitas harus berupa angka.";
    }
    if (empty($gudang->opening_hour)) {
        $errors[] = "Jam buka harus diisi.";
    }
    if (empty($gudang->closing_hour)) {
        $errors[] = "Jam tutup harus diisi.";
    }

    if (empty($errors)) {
        if ($gudang->update()) {
            session_start();
            $_SESSION['message'] = "Data berhasil diupdate!";
            $_SESSION['alert_type'] = "success";
            header("Location: index.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mt-4">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <h1 class="my-4">Edit Gudang</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $data['id']; ?>">
            <div class="form-group">
                <label>Nama Gudang</label>
                <input type="text" name="name" class="form-control" value="<?= $data['name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="location" class="form-control" value="<?= $data['location']; ?>" required>
            </div>
            <div class="form-group">
                <label>Kapasitas</label>
                <input type="number" name="capacity" class="form-control" value="<?= $data['capacity']; ?>" required>
            </div>
            <div class="form-group">
                <label>Jam Buka</label>
                <input type="time" name="opening_hour" class="form-control" value="<?= $data['opening_hour']; ?>"
                    required>
            </div>
            <div class="form-group">
                <label>Jam Tutup</label>
                <input type="time" name="closing_hour" class="form-control" value="<?= $data['closing_hour']; ?>"
                    required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
        <a href="./index.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>