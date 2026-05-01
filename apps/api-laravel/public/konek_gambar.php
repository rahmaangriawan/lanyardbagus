<?php

/**
 * Script untuk membuat symbolic link folder storage di hosting cPanel.
 */

// Folder asal (tempat file asli berada)
$target = __DIR__ . '/../storage/app/public';

// Folder tujuan (link yang akan diakses publik)
$link = __DIR__ . '/storage';

echo "<h2>Laravel Storage Linker</h2>";
echo "Target: <code>$target</code><br>";
echo "Link: <code>$link</code><br><br>";

if (file_exists($link)) {
    if (is_link($link)) {
        echo "<b style='color:orange'>INFO: Link sudah ada dan benar.</b>";
    } else {
        echo "<b style='color:red'>ERROR: Folder 'public/storage' sudah ada sebagai folder fisik.</b><br>";
        echo "Silakan hapus folder <code>public/storage</code> manual lewat File Manager cPanel, lalu jalankan script ini lagi.";
    }
} else {
    if (symlink($target, $link)) {
        echo "<b style='color:green'>BERHASIL! Symbolic link berhasil dibuat. Gambar sekarang harusnya sudah muncul.</b>";
    } else {
        echo "<b style='color:red'>GAGAL! Tidak bisa membuat symbolic link. Pastikan fungsi 'symlink' tidak dideaktivasi oleh penyedia hosting Anda.</b>";
    }
}

echo "<br><br><a href='/'>Kembali ke Beranda</a>";
