<?php
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan bersihkan data dari form
    $nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);
    $tempat_lahir = $conn->real_escape_string($_POST['tempat_lahir']);
    $tanggal_lahir = $conn->real_escape_string($_POST['tanggal_lahir']);
    $nisn = $conn->real_escape_string($_POST['nisn']);
    $asal_sekolah = $conn->real_escape_string($_POST['asal_sekolah']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $nilai_rata_rata = $conn->real_escape_string($_POST['nilai_rata_rata']);
    $jalur_pendaftaran = $conn->real_escape_string($_POST['jalur_pendaftaran']);

    // Generate Nomor Pendaftaran Unik (Contoh: PPDB2025-XXXX)
    $prefix = 'PPDB' . date('Y') . '-';
    $query_max_id = "SELECT MAX(CAST(SUBSTRING(no_pendaftaran, 10) AS UNSIGNED)) as max_id FROM calon_siswa WHERE no_pendaftaran LIKE '$prefix%'";
    $result = $conn->query($query_max_id);
    $row = $result->fetch_assoc();
    $next_id = ($row['max_id'] ?? 0) + 1;
    $no_pendaftaran = $prefix . str_pad($next_id, 4, '0', STR_PAD_LEFT);

    // Proses upload file
    $nama_file_db = NULL;
    $upload_dir = 'uploads/';
    $file_field_name = '';

    switch ($jalur_pendaftaran) {
        case 'Prestasi Akademik':
            $file_field_name = 'nilai_rapot';
            break;
        case 'Prestasi Non-Akademik':
            $file_field_name = 'prestasi_nonakademik';
            break;
        case 'Afirmasi':
            $file_field_name = 'bukti_afirmasi';
            break;
    }

    if (!empty($_FILES[$file_field_name]['name'])) {
        $file_tmp = $_FILES[$file_field_name]['tmp_name'];
        $file_name = basename($_FILES[$file_field_name]['name']);
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = $no_pendaftaran . '_' . $file_field_name . '.' . $file_ext;
        $target_file = $upload_dir . $new_file_name;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($file_tmp, $target_file)) {
            $nama_file_db = $target_file;
        } else {
            die("Error saat mengupload file.");
        }
    }

    // Menyiapkan kolom spesifik jalur
    $nilai_rapot_db = ($jalur_pendaftaran == 'Prestasi Akademik') ? $nama_file_db : NULL;
    $prestasi_nonakademik_db = ($jalur_pendaftaran == 'Prestasi Non-Akademik') ? $nama_file_db : NULL;
    $bukti_afirmasi_db = ($jalur_pendaftaran == 'Afirmasi') ? $nama_file_db : NULL;

    // Query untuk memasukkan data ke database
    $stmt = $conn->prepare("INSERT INTO calon_siswa (no_pendaftaran, nama_lengkap, tempat_lahir, tanggal_lahir, nisn, asal_sekolah, alamat, nilai_rata_rata, jalur_pendaftaran, nilai_rapot, prestasi_nonakademik, bukti_afirmasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssdssss", $no_pendaftaran, $nama_lengkap, $tempat_lahir, $tanggal_lahir, $nisn, $asal_sekolah, $alamat, $nilai_rata_rata, $jalur_pendaftaran, $nilai_rapot_db, $prestasi_nonakademik_db, $bukti_afirmasi_db);

    if ($stmt->execute()) {
        // Jika berhasil, redirect ke halaman sukses dengan nomor pendaftaran
        header("Location: sukses_daftar.php?no_pendaftaran=" . urlencode($no_pendaftaran));
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    // Jika halaman diakses tanpa POST, redirect ke halaman daftar
    header('Location: daftar.php');
    exit();
}
?>