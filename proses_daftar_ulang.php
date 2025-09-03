<?php
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['no_pendaftaran']) && !empty($_POST['no_pendaftaran'])) {
        
        $no_pendaftaran = $conn->real_escape_string($_POST['no_pendaftaran']);

        // Update status daftar ulang di database
        $stmt = $conn->prepare("UPDATE calon_siswa SET sudah_daftar_ulang = 1 WHERE no_pendaftaran = ? AND status_pendaftaran = 'Diterima'");
        $stmt->bind_param("s", $no_pendaftaran);
        
        if ($stmt->execute()) {
            // Jika berhasil, redirect kembali ke halaman cek pengumuman dengan status sukses
            // Redirect ini akan memicu form di halaman cek pengumuman untuk menampilkan data lagi
            header("Location: cek_pengumuman.php?no_pendaftaran=" . urlencode($no_pendaftaran) . "&daftar_ulang=sukses");
            exit();
        } else {
            die("Error: Gagal memperbarui status daftar ulang.");
        }
        $stmt->close();
    } else {
        die("Error: Nomor pendaftaran tidak valid.");
    }
} else {
    // Jika diakses selain dengan POST, redirect ke halaman utama
    header('Location: index.php');
    exit();
}

$conn->close();
?>