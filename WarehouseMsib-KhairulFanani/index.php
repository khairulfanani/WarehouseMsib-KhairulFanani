<?php
session_start();


include_once './classes/gudang.php';

$database = new Database();
$db = $database->getConnection();
$gudang = new Gudang($db);

$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 5;
$from_record_num = ($records_per_page * $page) - $records_per_page;

if (!empty($search_term)) {
    $stmt = $gudang->search($search_term, $from_record_num, $records_per_page);
    $total_rows = $gudang->countAll();
} else {
    $stmt = $gudang->readPaging($from_record_num, $records_per_page);
    $total_rows = $gudang->countAll();
}

$total_pages = ceil($total_rows / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Warehouse MSIB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container">
        <h1 class="my-4">List Gudang</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['alert_type']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['message']);
            unset($_SESSION['alert_type']);
            ?>
        <?php endif; ?>

        <a href="./create.php" class="btn btn-primary mb-4">Tambah Gudang</a>

        <form class="d-flex mb-4" method="GET" action="index.php">
            <input class="form-control me-2" type="search" placeholder="Cari Gudang" name="search"
                value="<?= isset($search_term) ? $search_term : ''; ?>" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Cari</button>
        </form>
        <div class="table-responsive mb-2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Jam Buka</th>
                        <th>Jam Tutup</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $from_record_num + 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= $no; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['location']; ?></td>
                            <td><?= $row['capacity']; ?></td>
                            <td>
                                <span class="badge <?= $row['status'] == 'aktif' ? 'bg-primary' : 'bg-warning'; ?>">
                                    <?= $row['status'] == 'aktif' ? 'Aktif' : 'Tidak Aktif'; ?>
                                </span>
                            </td>
                            <td><?= $row['opening_hour']; ?></td>
                            <td><?= $row['closing_hour']; ?></td>
                            <td>
                                <a href="./update.php?id=<?= $row['id']; ?>" class="btn btn-warning mb-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="./delete.php?id=<?= $row['id']; ?>" class="btn btn-danger mb-2"
                                    onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <a href="./status.php?id=<?= $row['id']; ?>&status=<?= $row['status'] == 'aktif' ? 'tidak_aktif' : 'aktif'; ?>"
                                    class="btn btn-info mb-2">
                                    <i class="bi <?= $row['status'] == 'aktif' ? 'bi-x-circle' : 'bi-check-circle'; ?>"></i>
                                </a>
                            </td>

                        </tr>
                        <?php $no++;
                    } ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if ($page > 1) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1; ?>&search=<?= $search_term; ?>">Previous</a>
                    </li>
                <?php } ?>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>&search=<?= $search_term; ?>"><?= $i; ?></a>
                    </li>
                <?php } ?>
                <?php if ($page < $total_pages) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1; ?>&search=<?= $search_term; ?>">Next</a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>