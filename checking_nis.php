<?php
require("config/config.default.php");
require("config/config.cbtx.php");

$query = mysqli_query($koneksi, "SELECT * FROM siswa
                   WHERE nis = '".$_POST['nis']."'");
$ketemu = mysqli_num_rows($sql);

echo $ketemu;
?>
