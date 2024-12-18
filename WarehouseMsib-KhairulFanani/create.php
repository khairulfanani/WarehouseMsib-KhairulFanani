<?php
include_once './classes/Gudang.php';

$errors = [];
if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();
    $gudang = new Gudang($db);

    if (empty($_POST['name'])) {
        $errors[] = "Nama Gudang wajib diisi!";
    }

    if (empty($_POST['location'])) {
        $errors[] = "Lokasi wajib diisi!";
    }

    if (empty($_POST['capacity']) || !is_numeric($_POST['capacity']) || $_POST['capacity'] <= 0) {
        $errors[] = "Kapasitas harus berupa angka!";
    }

    if (empty($_POST['opening_hour'])) {
        $errors[] = "Jam buka wajib diisi!";
    }

    if (empty($_POST['closing_hour'])) {
        $errors[] = "Jam tutup wajib diisi!";
    }

    if (empty($errors)) {
        $gudang->name = $_POST['name'];
        $gudang->location = $_POST['location'];
        $gudang->capacity = $_POST['capacity'];
        $gudang->opening_hour = $_POST['opening_hour'];
        $gudang->closing_hour = $_POST['closing_hour'];

        if ($gudang->create()) {
            session_start();
            $_SESSION['message'] = "Data berhasil disimpan!";
            $_SESSION['alert_type'] = "success";
            header("Location: index.php");
        } else {
            $errors[] = "Gagal menyimpan data!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Gudang</title>
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

        <h1 class="my-4">Tambah Gudang</h1>
        <form method="POST">
            <div class="form-group">
                <label>Nama Gudang</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="location" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kapasitas</label>
                <input type="number" name="capacity" class="form-control" required min="1">
            </div>
            <div class="form-group">
                <label>Jam Buka</label>
                <input type="time" name="opening_hour" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jam Tutup</label>
                <input type="time" name="closing_hour" class="form-control" required>
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