<?php
if (!isset($_GET['no_pendaftaran']) || empty($_GET['no_pendaftaran'])) {
    header('Location: index.php');
    exit();
}
$no_pendaftaran = htmlspecialchars($_GET['no_pendaftaran']);

require_once 'config/db.php';
$query = "SELECT nama_sekolah FROM pengaturan WHERE id = 1";
$result = $conn->query($query);
$pengaturan = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important;
        }
        .success-card {
            margin-top: 50px;
            border: 2px solid #198754; /* Green border */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/logo_sekolah.png" alt="Logo Sekolah" style="height: 40px; margin-right: 10px;">
                <b>PPDB <?php echo htmlspecialchars($pengaturan['nama_sekolah']); ?></b>
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center success-card">
                    <div class="card-header bg-success text-white">
                        <h3>Pendaftaran Berhasil!</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Terima kasih telah melakukan pendaftaran.</h5>
                        <p class="card-text">Data Anda telah berhasil kami simpan. Mohon simpan dan catat Nomor Pendaftaran Anda di bawah ini untuk digunakan saat pengecekan status kelulusan.</p>
                        <div class="alert alert-info">
                            Nomor Pendaftaran Anda adalah:
                            <h2 class="my-2"><b><?php echo $no_pendaftaran; ?></b></h2>
                        </div>
                        <a href="index.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
                    </div>
                    <div class="card-footer text-muted">
                        Panitia PPDB <?php echo htmlspecialchars($pengaturan['nama_sekolah']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
