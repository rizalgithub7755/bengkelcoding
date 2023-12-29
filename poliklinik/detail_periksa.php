<?php 


session_start();
include 'koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission to add obat to periksa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_obat'])) {
    $id_periksa = $_POST['id_periksa'];
    $id_obat = $_POST['id_obat'];

    // Insert data into the detail_periksa table
    $query = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES ('$id_periksa', '$id_obat')";
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        echo '<div class="alert alert-success mt-3" role="alert">Obat berhasil ditambahkan!</div>';
    } else {
        echo '<div class="alert alert-danger mt-3" role="alert">Gagal menambahkan obat. Silakan coba lagi.</div>';
    }
}

// Fetch periksa details
$id_periksa = isset($_GET['id']) ? $_GET['id'] : null;
if ($id_periksa) {
    $periksa_query = mysqli_query($mysqli, "SELECT pr.*, d.nama AS 'nama_dokter', p.nama AS 'nama_pasien' FROM periksa pr LEFT JOIN dokter d ON (pr.id_dokter=d.id) LEFT JOIN pasien p ON (pr.id_pasien=p.id) WHERE pr.id = '$id_periksa'");
    if ($periksa_query) {
        $periksa_data = mysqli_fetch_assoc($periksa_query);

        // Check if data is available before accessing it
        $nama_pasien = isset($periksa_data['nama_pasien']) ? $periksa_data['nama_pasien'] : 'N/A';
        $nama_dokter = isset($periksa_data['nama_dokter']) ? $periksa_data['nama_dokter'] : 'N/A';
        $tanggal_periksa = isset($periksa_data['tgl_periksa']) ? $periksa_data['tgl_periksa'] : 'N/A';
        $catatan = isset($periksa_data['catatan']) ? $periksa_data['catatan'] : 'N/A';
    } else {
        // Handle the case where the query fails
        die('Error fetching periksa details: ' . mysqli_error($mysqli));
    }
} else {
    echo "No data found for periksa ID: $id_periksa";
}


// Fetch obat details
$obat_query = mysqli_query($mysqli, "SELECT dp.id_obat, o.nama_obat, o.kemasan, o.harga FROM obat o INNER JOIN detail_periksa dp ON o.id = dp.id_obat WHERE dp.id_periksa = '$id_periksa'");

$obat_list = array();
while ($row = mysqli_fetch_assoc($obat_query)) {
    $obat_list[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Periksa Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            Sistem Informasi Poliklinik
        </a>
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown"
                aria-expanded="false"
                aria-label="Toggle navigation">
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="index.php">
                        Home
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Data Master
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="dokter.php?page=dokter">
                                Dokter
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="pasien.php?page=pasien">
                                Pasien
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="obat.php?page=obat">
                                Obat
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="periksa.php?page=periksa">
                        Periksa
                    </a>
                </li>
            </ul>
        </div>

        <?php
        if (isset($_SESSION['username'])) {
            echo '<a class="btn btn-danger" href="logout.php">Logout</a>';
        }
        ?>
    </div>
</nav>

<div class="container">
<h2>Detail Periksa</h2>
<p><strong>Nama Pasien:</strong> <?php echo $nama_pasien; ?></p>
<p><strong>Nama Dokter:</strong> <?php echo $nama_dokter; ?></p>
<p><strong>Tanggal Periksa:</strong> <?php echo $tanggal_periksa; ?></p>
<p><strong>Catatan:</strong> <?php echo $catatan; ?></p>

    <h3>Obat yang Telah Ditambahkan</h3>
    <ul>
    <?php foreach ($obat_list as $obat): ?>
        <li>
            <?php echo "{$obat['nama_obat']} - {$obat['kemasan']} - Rp. {$obat['harga']}"; ?>
            <form method="POST" action="" style="display:inline;" onsubmit="return confirmDelete(<?php echo $obat['id_obat']; ?>)">
                <input type="hidden" name="delete_id_obat" value="<?php echo $obat['id_obat']; ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
    
       <!-- Display the total cost of drugs -->
<?php
$total_obat_query = "SELECT SUM(o.harga) AS total_obat
                    FROM detail_periksa dp
                    JOIN obat o ON dp.id_obat = o.id
                    WHERE dp.id_periksa = $id_periksa";
$total_obat_result = mysqli_query($mysqli, $total_obat_query);

if ($total_obat_result && mysqli_num_rows($total_obat_result) > 0) {
    $total_obat_data = mysqli_fetch_assoc($total_obat_result);
    $total_obat = $total_obat_data['total_obat'];
    echo '<h3>Total Harga Obat: Rp. ' . ($total_obat !== null ? number_format($total_obat) : 'N/A') . '</h3>';
} else {
    echo '<p>Total Harga Obat: Gagal menghitung total harga obat.</p>';
}
?>

    <!-- Display the examination fee -->
<h3>Biaya Periksa: Rp. 150,000</h3>

<!-- Display the overall total cost -->
<?php
$total_query = "SELECT SUM(o.harga) + 150000 AS total_harga
                FROM detail_periksa dp
                JOIN obat o ON dp.id_obat = o.id
                WHERE dp.id_periksa = $id_periksa";
$total_result = mysqli_query($mysqli, $total_query);

if ($total_result && mysqli_num_rows($total_result) > 0) {
    $total_data = mysqli_fetch_assoc($total_result);
    $total_harga = $total_data['total_harga'];
    echo '<h3>Total Harga: Rp. ' . ($total_harga !== null ? number_format($total_harga) : 'N/A') . '</h3>';
} else {
    echo '<p>Total Harga: Gagal menghitung total harga.</p>';
}
?>

    <h3>Tambah Obat</h3>
    <form class="form" method="POST" action="">
        <input type="hidden" name="id_periksa" value="<?php echo $id_periksa; ?>">
        <div class="mb-2">
            <label for="id_obat" class="form-label fw-bold">Pilih Obat</label>
            <select class="form-control" name="id_obat" id="id_obat" required>
                <?php
                $obat_query = mysqli_query($mysqli, "SELECT * FROM obat");
                while ($obat = mysqli_fetch_array($obat_query)) {
                    echo "<option value='{$obat['id']}'>{$obat['nama_obat']} - {$obat['kemasan']} - Rp. {$obat['harga']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary rounded-pill px-3" name="tambah_obat">Tambah Obat</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
    function confirmDelete(id_obat) {
    console.log('Delete button clicked for ID:', id_obat);
    var confirmed = confirm("Are you sure you want to delete this obat?");
    console.log('Confirmed:', confirmed);

    // Return false to prevent form submission if not confirmed
    return confirmed;
}

</script>



</body>
</html>
