<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      Sistem Informasi Poliklinik
    </a>
    <button class="navbar-toggler"
    type="button" data-bs-toggle="collapse"
    data-bs-target="#navbarNavDropdown"
    aria-controls="navbarNavDropdown" aria-expanded="false"
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
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" 
          href="periksa.php?page=periksa">
            Periksa
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    
<?php
include'koneksi.php';
?>
<body>
<div class="container">
    </nav><div class="form-group mx-sm-3 mb-2">
    <label for="inputPasien" class="sr-only"><h3>Pasien</h3></label>
    <select class="form-control" name="id_pasien">
        <?php
        $selected = '';
        $pasien = mysqli_query($mysqli, "SELECT * FROM pasien");
        while ($data = mysqli_fetch_array($pasien)) {
            if ($data['id'] == $id_pasien) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
        ?>
            <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
        <?php
        }
        ?>
    </select>
    <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
    <!-- Kode php untuk menghubungkan form dengan database -->
    <?php
    $data = '';
    $data = '';
    $data = '';
    $data = '';
    if (isset($_GET['id'])) {
        $ambil = mysqli_query($mysqli, 
        "SELECT * FROM periksa
        WHERE id='1" . $_GET['id'] . "'");
        while ($row = mysqli_fetch_array($ambil)) {
            $data= $row['nama_pasien'];
            $data = $row['nama_dokter'];
            $data = $row['tgl_periksa'];
            $data = $row['catatan'];
        }
    ?>
        <input type="hidden" name="id" value="<?php echo
        $_GET['id'] ?>">
    <?php
    }
    ?>
    <div class="col">
        <label for="inputNama" class="form-label fw-bold">
            Nama Pasien
        </label>
        <input type="varchar" class="form-control" name="namaPasien" id="inputNama" placeholder="Nama Pasien" value="<?php echo $data ?>">
    </div>
    <div class="col">
        <label for="inputAlamat" class="form-label fw-bold">
            Nama Dokter
        </label>
        <input type="varchar" class="form-control" name="namaDokter" id="inputNama" placeholder="Nama Dokter" value="<?php echo $data ?>">
    </div>
    <div class="col mb-2">
        <label for="inputNo_hp" class="form-label fw-bold">
        Tanggal Periksa
        </label>
        <input type="date" class="form-control" name="tgl_periksa" id="inputTgl_periksa" placeholder="Tanggal Periksa" value="<?php echo $data ?>">
    </div>
    <div class="col mb-2">
        <label for="inputNo_hp" class="form-label fw-bold">
        Catatan
        </label>
        <input type="varchar" class="form-control" name="no_hp" id="inputNo_hp" placeholder="Catatan" value="<?php echo $data ?>">
    </div>
    <div class="col">
        <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
    </div>
    <!-- Table-->
<table class="table table-hover">
    <!--thead atau baris judul-->
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama Pasien</th>
            <th scope="col">Nama Dokter</th>
            <th scope="col">Tanggal Periksa</th>
            <th scope="col">Catatan</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <!--tbody berisi isi tabel sesuai dengan judul atau head-->
    <tbody>
</div>
<form method="post" action="periksa.php">
<?php
$result = mysqli_query($mysqli, "SELECT pr.*,d.nama as 'nama_dokter', p.nama as 'nama_pasien' FROM periksa pr LEFT JOIN dokter d ON (pr.id_dokter=d.id) LEFT JOIN pasien p ON (pr.id_pasien=p.id) ORDER BY pr.tgl_periksa DESC");
$no = 1;
while ($data = mysqli_fetch_array($result)) {
?>
    <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo $data['nama_pasien'] ?></td>
        <td><?php echo $data['nama_dokter'] ?></td>
        <td><?php echo $data['tgl_periksa'] ?></td>
        <td><?php echo $data['catatan'] ?></td>
        <td>
            <a class="btn btn-success rounded-pill px-3" 
            href="periksa.php?page=periksa&id=<?php echo $data['id'] ?>">Ubah</a>
            <a class="btn btn-danger rounded-pill px-3" 
            href="periksa.php?page=periksa&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
        </td>
    </tr>
    <?php
}
?>
</form>
<?php
if (isset($_POST['simpan'])) {
    if (isset($_POST['id'])) {
        $ubah = mysqli_query($mysqli, "UPDATE periksa SET 
                                        nama_pasien = '" . $_POST['nama_pasien'] . "',
                                        nama_dokter = '" . $_POST['nama_dokter'] . "',
                                        tgl_periksa = '" . $_POST['tgl_periksa '] . "'
                                        catatan = '" . $_POST['catatan '] . "'
                                        WHERE
                                        id = '" . $_POST['id'] . "'");
    } else {
      $tambah = mysqli_query($mysqli, "INSERT INTO pasien (nama_pasien,nama_dokter,tgl_periksa, catatan,status) 
                                VALUES (
                                    '" . $_POST['nama_pasien'] . "',
                                    '" . $_POST['nama_dokter'] . "',
                                    '" . $_POST['tgl_periksa'] . "',
                                    '" . $_POST['catatan'] . "',
                                    '0'
                                     )");
    }

    echo "<script> 
            document.location='periksa.php';
            </script>";
}

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM periksa WHERE id = '" . $_GET['id'] . "'");
    } else if ($_GET['aksi'] == 'ubah_status') {
        $ubah_status = mysqli_query($mysqli, "UPDATE periksa SET 
                                        status = '" . $_GET['status'] . "' 
                                        WHERE
                                        id = '" . $_GET['id'] . "'");
    }

    echo "<script> 
            document.location='periksa.php';
            </script>";
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

