<?php
// Memasukkan file koneksi database
require_once 'config/db.php';

// Mengambil data pengaturan dari database
$query = "SELECT * FROM pengaturan WHERE id = 1";
$result = $conn->query($query);
$pengaturan = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Online <?php echo htmlspecialchars($pengaturan['nama_sekolah']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f0f8ff; /* Light blue background */
        }
        .navbar {
            background-color: #007bff; /* Primary blue */
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important;
        }
        .hero-section {
            padding: 60px 0;
            background-color: #ffffff;
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .footer {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="daftar.php">Daftar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cek_pengumuman.php">Cek Pengumuman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/">Login Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="hero-section my-4 rounded">
                <h1>Selamat Datang di PPDB Online</h1>
                <h2><?php echo htmlspecialchars($pengaturan['nama_sekolah']); ?></h2>
                <p class="lead"><?php echo htmlspecialchars($pengaturan['info_pendaftaran']); ?></p>
                <?php if ($pengaturan['buka_pendaftaran'] == 1): ?>
                    <a href="daftar.php" class="btn btn-primary btn-lg">Daftar Sekarang!</a>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg" disabled>Pendaftaran Ditutup</button>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Informasi Penting
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Jadwal Pendaftaran</h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($pengaturan['jadwal_pendaftaran'])); ?></p>
                            <h5 class="card-title mt-4">Alur Pendaftaran</h5>
                            <ol>
                                <li>Calon siswa membuka website, membaca syarat, lalu klik "Daftar Sekarang".</li>
                                <li>Mengisi formulir pendaftaran dengan data yang benar dan valid.</li>
                                <li>Mengunggah dokumen yang diperlukan sesuai jalur pendaftaran.</li>
                                <li>Mendapatkan nomor pendaftaran setelah berhasil submit formulir.</li>
                                <li>Admin melakukan verifikasi data dan dokumen.</li>
                                <li>Calon siswa dapat melihat hasil pengumuman di halaman "Cek Pengumuman" menggunakan nomor pendaftaran.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <footer class="footer mt-auto py-3">
        <div class="container">
            <span>&copy; <?php echo date("Y"); ?> <?php echo htmlspecialchars($pengaturan['nama_sekolah']); ?>. All rights reserved.</span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
