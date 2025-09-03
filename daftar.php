<?php
require_once 'config/db.php';

// Mengambil data pengaturan
$query = "SELECT * FROM pengaturan WHERE id = 1";
$result = $conn->query($query);
$pengaturan = $result->fetch_assoc();

// Cek apakah pendaftaran dibuka
if ($pengaturan['buka_pendaftaran'] == 0) {
    // Jika ditutup, redirect ke halaman utama atau tampilkan pesan
    header('Location: index.php?status=tutup');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - PPDB Online</title>
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/logo_sekolah.png" alt="Logo Sekolah" style="height: 40px; margin-right: 10px;">
                <b>PPDB <?php echo htmlspecialchars($pengaturan['nama_sekolah']); ?></b>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="daftar.php">Daftar</a>
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

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Formulir Pendaftaran Calon Siswa Baru</h3>
            </div>
            <div class="card-body">
                <form action="proses_daftar.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nisn" class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
                        <input type="text" class="form-control" id="nisn" name="nisn" required>
                    </div>
                    <div class="mb-3">
                        <label for="asal_sekolah" class="form-label">Asal Sekolah</label>
                        <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah" required>
                    </div>
                    <div class="mb-3">
                        <label for="nilai_rata_rata" class="form-label">Nilai Rata-Rata Rapor</label>
                        <input type="number" class="form-control" id="nilai_rata_rata" name="nilai_rata_rata" step="0.01" min="0" max="100" required>
                        <div class="form-text">Contoh: 85.75</div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="jalur_pendaftaran" class="form-label">Jalur Pendaftaran</label>
                        <select class="form-select" id="jalur_pendaftaran" name="jalur_pendaftaran" required onchange="showHideFields()">
                            <option selected disabled value="">-- Pilih Jalur --</option>
                            <option value="Prestasi Akademik">Prestasi Akademik</option>
                            <option value="Prestasi Non-Akademik">Prestasi Non-Akademik</option>
                            <option value="Afirmasi">Afirmasi</option>
                        </select>
                    </div>

                    <!-- Field dinamis berdasarkan jalur -->
                    <div id="field_prestasi_akademik" class="mb-3" style="display: none;">
                        <label for="nilai_rapot" class="form-label">Upload Scan Nilai Rapot (PDF/JPG)</label>
                        <input type="file" class="form-control" id="nilai_rapot" name="nilai_rapot">
                    </div>
                    <div id="field_prestasi_nonakademik" class="mb-3" style="display: none;">
                        <label for="prestasi_nonakademik" class="form-label">Upload Scan Sertifikat Lomba (PDF/JPG)</label>
                        <input type="file" class="form-control" id="prestasi_nonakademik" name="prestasi_nonakademik">
                    </div>
                    <div id="field_afirmasi" class="mb-3" style="display: none;">
                        <label for="bukti_afirmasi" class="form-label">Upload Scan Kartu Indonesia Pintar (KIP) (PDF/JPG)</label>
                        <input type="file" class="form-control" id="bukti_afirmasi" name="bukti_afirmasi">
                    </div>

                    <button type="submit" class="btn btn-primary">Daftar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function showHideFields() {
        var jalur = document.getElementById("jalur_pendaftaran").value;
        document.getElementById("field_prestasi_akademik").style.display = (jalur === "Prestasi Akademik") ? "block" : "none";
        document.getElementById("field_prestasi_nonakademik").style.display = (jalur === "Prestasi Non-Akademik") ? "block" : "none";
        document.getElementById("field_afirmasi").style.display = (jalur === "Afirmasi") ? "block" : "none";
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
