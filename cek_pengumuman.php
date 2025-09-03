<?php
require_once 'config/db.php';
$query = "SELECT * FROM pengaturan WHERE id = 1";
$result = $conn->query($query);
$pengaturan = $result->fetch_assoc();

$hasil_pencarian = null;
$no_pendaftaran_input = '';

// Ambil no pendaftaran dari POST (saat siswa mengisi form) atau GET (saat redirect)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['no_pendaftaran'])) {
    $no_pendaftaran_input = $_POST['no_pendaftaran'];
} elseif (isset($_GET['no_pendaftaran'])) {
    $no_pendaftaran_input = $_GET['no_pendaftaran'];
}

if (!empty($no_pendaftaran_input)) {
    $no_pendaftaran = $conn->real_escape_string($no_pendaftaran_input);
    $stmt = $conn->prepare("SELECT nama_lengkap, no_pendaftaran, status_pendaftaran, sudah_daftar_ulang FROM calon_siswa WHERE no_pendaftaran = ?");
    $stmt->bind_param("s", $no_pendaftaran);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $hasil_pencarian = $result->fetch_assoc();
    } else {
        $hasil_pencarian = 'tidak_ditemukan';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Pengumuman - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f8ff; }
        .navbar { background-color: #007bff; }
        .navbar-brand, .nav-link { color: #ffffff !important; }
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftar.php">Daftar</a></li>
                    <li class="nav-item"><a class="nav-link active" href="cek_pengumuman.php">Cek Pengumuman</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/">Login Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h3>Cek Status Pendaftaran</h3></div>
                    <div class="card-body">
                        <p>Masukkan nomor pendaftaran Anda untuk melihat status kelulusan.</p>
                        <form action="cek_pengumuman.php" method="POST">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Contoh: PPDB2025-0001" name="no_pendaftaran" required>
                                <button class="btn btn-primary" type="submit">Cek Status</button>
                            </div>
                        </form>

                        <?php if ($hasil_pencarian): ?>
                            <hr>
                            <div class="mt-4">
                                <?php if ($hasil_pencarian == 'tidak_ditemukan'): ?>
                                    <div class="alert alert-danger">Nomor pendaftaran tidak ditemukan. Pastikan Anda memasukkan nomor dengan benar.</div>
                                <?php else: 
                                    $status = $hasil_pencarian['status_pendaftaran'];
                                    $alert_class = 'alert-info';
                                    $pesan = 'Status pendaftaran Anda saat ini adalah:';
                                    if ($status == 'Diterima') {
                                        $alert_class = 'alert-success';
                                        $pesan = 'Selamat! Anda dinyatakan DITERIMA sebagai siswa baru.';
                                    } elseif ($status == 'Ditolak') {
                                        $alert_class = 'alert-danger';
                                        $pesan = 'Mohon maaf, Anda dinyatakan TIDAK DITERIMA.';
                                    } elseif ($status == 'Proses') {
                                        $pesan = 'Data Anda sedang dalam proses verifikasi oleh panitia.';
                                    }
                                ?>
                                    <div class="alert <?php echo $alert_class; ?>">
                                        <h4 class="alert-heading"><?php echo $pesan; ?></h4>
                                        <p>
                                            <strong>Nama Lengkap:</strong> <?php echo htmlspecialchars($hasil_pencarian['nama_lengkap']); ?><br>
                                            <strong>No. Pendaftaran:</strong> <?php echo htmlspecialchars($hasil_pencarian['no_pendaftaran']); ?><br>
                                            <strong>Status:</strong> <strong><?php echo htmlspecialchars($status); ?></strong>
                                        </p>
                                        <?php if ($status == 'Diterima'): ?>
                                            <hr>
                                            <?php if (isset($_GET['daftar_ulang']) && $_GET['daftar_ulang'] == 'sukses'): ?>
                                                <div class="alert alert-light">
                                                    <strong>Terima kasih, konfirmasi daftar ulang Anda telah berhasil kami terima.</strong><br>
                                                    Informasi selanjutnya mengenai jadwal masuk sekolah akan diumumkan di website ini.
                                                </div>
                                            <?php elseif ($hasil_pencarian['sudah_daftar_ulang'] == 1):
                                            ?>
                                                <div class="alert alert-light">
                                                    <strong>Anda sudah melakukan daftar ulang.</strong><br>
                                                    Informasi selanjutnya mengenai jadwal masuk sekolah akan diumumkan di website ini.
                                                </div>
                                            <?php else: ?>
                                                <p>Silakan lakukan konfirmasi daftar ulang sebelum batas waktu yang ditentukan untuk mengamankan slot Anda.</p>
                                                <form action="proses_daftar_ulang.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melakukan daftar ulang?');">
                                                    <input type="hidden" name="no_pendaftaran" value="<?php echo htmlspecialchars($hasil_pencarian['no_pendaftaran']); ?>">
                                                    <button type="submit" class="btn btn-success">Konfirmasi Daftar Ulang Sekarang</button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
