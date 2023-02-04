<?php
require("config/config.default.php");
require("config/config.function.php");
require("config/functions.crud.php");
$soalx = $_POST['soal'];
$soalx = dekripsi($soalx);
$decoded = json_decode($soalx, true);
$pengacak = $_POST['pengacak'];
$pengacak = explode(',', $pengacak);
$pengacakpil = $_POST['pengacakpil'];
$pengacakpil = explode(',', $pengacakpil);
$id_siswa = (isset($_SESSION['id_siswa'])) ? $_SESSION['id_siswa'] : 0;
$ujiannya = dekripsi($_POST['ujian']);
$mapel = json_decode($ujiannya, true);
$exec = 'NO';

$pg = @$_POST['pg'];
$ac = $mapel[0]['id_ujian'];
$id = @$_POST['id'];
$audio = array('mp3', 'wav', 'ogg', 'MP3', 'WAV', 'OGG');
$image = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP');
?>
<?php if ($pg == 'soal') { ?>
    <?php
    $no_soal = $_POST['no_soal'];
    $no_prev = $no_soal - 1;
    $no_next = $no_soal + 1;
    $id_mapel = $_POST['id_mapel'];
    $id_siswa = $_POST['id_siswa'];

    // $where = array(
    //     'id_siswa' => $id_siswa,
    //     'id_mapel' => $id_mapel
    // );
    $where2 = array(
        'id_siswa' => $id_siswa,
        'id_mapel' => $id_mapel,
        'id_ujian' => $ac
    );
    //$mapel[0] = fetch($koneksi, 'ujian', array('id_mapel' => $id_mapel, 'id_ujian' => $ac));
    update($koneksi, 'nilai', array('ujian_berlangsung' => $datetime), $where2);
    // $exce = mysqli_query($koneksi, "UPDATE nilai SET ujian_berlangsung='". $datetime ."' WHERE id_siswa=". $id_siswa ." AND id_mapel=". $id_mapel ." AND id_ujian=". $ac);

    $nilai = fetch($koneksi, 'nilai', $where2);
    // $nilai  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM nilai WHERE ujian_berlangsung='". $datetime ."' WHERE id_siswa=". $id_siswa ." AND id_mapel=". $id_mapel ." AND id_ujian=". $ac));

    if ($nilai['ujian_selesai'] <> null) {
        // jump("$homeurl");
        echo "<script>location=('$page');</script>";
        exit();
    }

    $nomor  = $_POST['no_soal'];
    $nosoal = $nomor;

    foreach ($decoded as $soal) {
        if ($soal['id_soal'] == $pengacak[$nosoal]) {
            $jawab = fetch($koneksi, 'jawaban', array('id_siswa' => $id_siswa, 'id_mapel' => $id_mapel, 'id_soal' => $soal['id_soal'], 'id_ujian' => $ac));
            // $jawab = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM jawaban WHERE id_siswa=". $id_siswa ." AND id_mapel=". $id_mapel ." AND id_ujian=". $ac));
    ?>
            <div class='box-body'>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='callout soal'>
                            <div class='soaltanya animated fadeIn'><?= $soal['soal'] ?></div>
                        </div>
                        <div class='col-md-12'>
                            <?php
                            if ($soal['file'] <> '') {
                                $ext = explode(".", $soal['file']);
                                $ext = end($ext);
                                if (in_array($ext, $image)) {
                                    echo "<span  id='zoom' style='display:inline-block'> <img  src='$homeurl/files/$soal[file]' class='img-responsive'/></span>";
                                } elseif (in_array($ext, $audio)) {
                                    echo   "<audio volume='1.0' id='audio-player' onended='audio_ended()'>
                                                <source src='$homeurl/files/$soal[file]' type='audio/$ext' style='width:100%;'/>
                                                Your browser does not support the audio tag.
                                            </audio>
                                            <div style='max-width:350px' id='audio-control'>
                                                <div class='card'>
                                                    <div class='card-body'>
                                                        <input type='hidden' id='audio-player-status' value='0' />
                                                        <input type='hidden' id='audio-player-update' value='0' />
                                                        <a class='btn btn-app' onclick='audio()'>
                                                            <i class='fa fa-play' id='audio-player-judul-logo'></i> <span id='audio-player-judul'>Play</span>
                                                        </a>
                                                        &nbsp;&nbsp;Klik Play untuk memutar Audio
                                                    </div>
                                                </div>
                                            </div>";
                                } else {
                                    echo "File tidak didukung!";
                                }
                            }
                            if ($soal['file1'] <> '') {
                                $ext = explode(".", $soal['file1']);
                                $ext = end($ext);
                                if (in_array($ext, $image)) {
                                    echo "<span  id='zoom1' style='display:inline-block'> <img  src='$homeurl/files/$soal[file1]' class='img-responsive'/></span>";
                                } elseif (in_array($ext, $audio)) {
                                    echo "  <audio volume='1.0' id='audio-player2' onended='audio_ended2()'>
                                                <source src='$homeurl/files/$soal[file1]' type='audio/$ext' style='width:100%;'/>
                                                Your browser does not support the audio tag.
                                            </audio>
                                            <div style='max-width:350px' id='audio-control2'>
                                                <div class='card'>
                                                    <div class='card-body'>
                                                        <input type='hidden' id='audio-player-status2' value='0' />
                                                        <input type='hidden' id='audio-player-update2' value='0' />
                                                        <a class='btn btn-app' onclick='audio2()'>
                                                            <i class='fa fa-play' id='audio-player-judul-logo2'></i> <span id='audio-player-judul2'>Play</span>
                                                        </a>
                                                        &nbsp;&nbsp;Klik Play untuk memutar Audio
                                                    </div>
                                                </div>
                                            </div>";
                                } else {
                                    echo "File tidak didukung!";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php if ($soal['jenis'] == 1) { ?>
                        <div class='col-md-12'>
                            <?php if ($mapel[0]['ulang'] == '1') : ?>
                                <?php
                                if ($mapel[0]['opsi'] == 3) {
                                    $kali = 3;
                                } elseif ($mapel[0]['opsi'] == 4) {
                                    $kali = 4;
                                    $nop4 = $no_soal * $kali + 3;
                                    $pil4 = $pengacakpil[$nop4];
                                    $pilDD = "pil" . $pil4;
                                    $fileDD = "file" . $pil4;
                                } elseif ($mapel[0]['opsi'] == 5) {
                                    $kali = 5;
                                    $nop4 = $no_soal * $kali + 3;
                                    $pil4 = $pengacakpil[$nop4];
                                    $pilDD = "pil" . $pil4;
                                    $fileDD = "file" . $pil4;
                                    $nop5 = $no_soal * $kali + 4;
                                    $pil5 = $pengacakpil[$nop5];
                                    $pilEE = "pil" . $pil5;
                                    $fileEE = "file" . $pil5;
                                }

                                $nop1 = $no_soal * $kali;
                                $nop2 = $no_soal * $kali + 1;
                                $nop3 = $no_soal * $kali + 2;
                                $pil1 = $pengacakpil[$nop1];
                                $pilAA = "pil" . $pil1;
                                $fileAA = "file" . $pil1;
                                $pil2 = $pengacakpil[$nop2];
                                $pilBB = "pil" . $pil2;
                                $fileBB = "file" . $pil2;
                                $pil3 = $pengacakpil[$nop3];
                                $pilCC = "pil" . $pil3;
                                $fileCC = "file" . $pil3;


                                $a = ($jawab['jawabx'] == 'A') ? 'checked' : '';
                                $b = ($jawab['jawabx'] == 'B') ? 'checked' : '';
                                $c = ($jawab['jawabx'] == 'C') ? 'checked' : '';

                                if ($mapel[0]['opsi'] == 4) :
                                    $d = ($jawab['jawabx'] == 'D') ? 'checked' : '';
                                elseif ($mapel[0]['opsi'] == 5) :
                                    $d = ($jawab['jawabx'] == 'D') ? 'checked' : '';
                                    $e = ($jawab['jawabx'] == 'E') ? 'checked' : '';
                                endif;


                                ?>
                                <?php if ($soal['pilA'] == '' and $soal['fileA'] == '' and $soal['pilB'] == '' and $soal['fileB'] == '' and $soal['pilC'] == '' and $soal['fileC'] == '' and $soal['pilD'] == '' and $soal['fileD'] == '') { ?>
                                    <?php
                                    $ax = ($jawab['jawabx'] == 'A') ? 'checked' : '';
                                    $bx = ($jawab['jawabx'] == 'B') ? 'checked' : '';
                                    $cx = ($jawab['jawabx'] == 'C') ? 'checked' : '';
                                    $dx = ($jawab['jawabx'] == 'D') ? 'checked' : '';
                                    if ($mapel[0]['opsi'] == 5) :
                                        $ex = ($jawab['jawabx'] == 'E') ? 'checked' : '';
                                    endif;
                                    ?>
                                    <table class='table'>
                                        <tr>
                                            <td>
                                                <input class='hidden radio-label' type='radio' name='jawab' id='A' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'A','A',1,<?= $ac ?>)" <?= $ax ?> />
                                                <label class='button-label' for='A'>
                                                    <h1>1</h1>
                                                </label>
                                            </td>

                                            <td>
                                                <input class='hidden radio-label' type='radio' name='jawab' id='C' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'C','C',1,<?= $ac ?>)" <?= $cx ?> />
                                                <label class='button-label' for='C'>
                                                    <h1>3</h1>
                                                </label>
                                            </td>
                                            <?php if ($mapel[0]['opsi'] == 5) { ?>
                                                <td>
                                                    <input class='hidden radio-label' type='radio' name='jawab' id='E' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'E','E',1,<?= $ac ?>)" <?= $ex ?> />
                                                    <label class='button-label' for='E'>
                                                        <h1>5</h1>
                                                    </label>

                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input class='hidden radio-label' type='radio' name='jawab' id='B' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'B','B',1,<?= $ac ?>)" <?= $bx ?> />
                                                <label class='button-label' for='B'>
                                                    <h1>2</h1>
                                                </label>
                                            </td>
                                            <?php if ($mapel[0]['opsi'] <> 3) { ?>
                                                <td>
                                                    <input class='hidden radio-label' type='radio' name='jawab' id='D' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'D','D',1,<?= $ac ?>)" <?= $dx ?> />
                                                    <label class='button-label' for='D'>
                                                        <h1>4</h1>
                                                    </label>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                <?php } else { ?>
                                    <table width='100%' class='table table-striped table-hover'>
                                        <tr>
                                            <!-- Opsi A -->
                                            <td width='60'>
                                                <input class='hidden radio-label' type='radio' name='jawab' id='A' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'<?= $pil1 ?>','A',1,<?= $ac ?>)" <?= $a ?> />
                                                <label class='button-label' for='A'>
                                                    <h1>1</h1>
                                                </label>
                                            </td>
                                            <td style='vertical-align:middle;'>
                                                <span class='soal'><?= $soal[$pilAA] ?></span>
                                                <?php if ($soal[$fileAA] <> '') : ?>
                                                    <?php
                                                    $ext = explode(".", $soal[$fileAA]);
                                                    $ext = end($ext);
                                                    if (in_array($ext, $image)) :
                                                        echo "<span  class='lup' style='display:inline-block'><img src='$homeurl/files/$soal[$fileAA]' class='img-responsive' style='width:250px;'/></span>";
                                                    elseif (in_array($ext, $audio)) :
                                                        echo "  <audio volume='1.0' id='audio-player3' onended='audio_ended3()'>
                                                                    <source src='$homeurl/files/$soal[$fileAA]' type='audio/$ext' style='width:100%;'/>
                                                                    Your browser does not support the audio tag.
                                                                </audio>
                                                                <div style='max-width:350px' id='audio-control3'>
                                                                    <div class='card'>
                                                                        <div class='card-body'>
                                                                            <input type='hidden' id='audio-player-status3' value='0' />
                                                                            <input type='hidden' id='audio-player-update3' value='0' />
                                                                            <a class='btn btn-app' onclick='audio3()'>
                                                                                <i class='fa fa-play' id='audio-player-judul-logo3'></i> <span id='audio-player-judul3'>Play</span>
                                                                            </a>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>";
                                                    else :
                                                        echo "File tidak didukung!";
                                                    endif;
                                                    ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <!-- Opsi B -->
                                            <td width='60'>
                                                <input class='hidden radio-label' type='radio' name='jawab' id='B' onclick="jawabsoal(<?= $id_mapel ?>, <?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'<?= $pil2 ?>','B',1, <?= $ac ?>)" <?= $b ?> />
                                                <label class='button-label' for='B'>
                                                    <h1>2</h1>
                                                </label>
                                            </td>
                                            <td style='vertical-align:middle;'>
                                                <span class='soal'><?= $soal[$pilBB] ?></span>
                                                <?php
                                                if ($soal[$fileBB] <> '') {
                                                    $ext = explode(".", $soal[$fileBB]);
                                                    $ext = end($ext);
                                                    if (in_array($ext, $image)) :
                                                        echo "<span  class='lup' style='display:inline-block'><img src='$homeurl/files/$soal[$fileBB]' class='img-responsive' style='width:250px;'/></span>";
                                                    elseif (in_array($ext, $audio)) :
                                                        echo "  <audio volume='1.0' id='audio-player4' onended='audio_ended4()'>
                                                                    <source src='$homeurl/files/$soal[$fileBB]' type='audio/$ext' style='width:100%;'/>
                                                                    Your browser does not support the audio tag.
                                                                </audio>
                                                                <div style='max-width:350px' id='audio-control4'>
                                                                    <div class='card'>
                                                                        <div class='card-body'>
                                                                            <input type='hidden' id='audio-player-status4' value='0' />
                                                                            <input type='hidden' id='audio-player-update4' value='0' />
                                                                            <a class='btn btn-app' onclick='audio4()'>
                                                                                <i class='fa fa-play' id='audio-player-judul-logo4'></i> <span id='audio-player-judul4'>Play</span>
                                                                            </a>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>";
                                                    else :
                                                        echo "File tidak didukung!";
                                                    endif;
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <!-- Opsi C -->
                                            <td>
                                                <input class='hidden radio-label' type='radio' name='jawab' id='C' onclick="jawabsoal(<?= $id_mapel ?>, <?= $id_siswa ?>, <?= $soal['id_soal'] ?>,'<?= $pil3 ?>','C',1,<?= $ac ?>)" <?= $c ?> />
                                                <label class='button-label' for='C'>
                                                    <h1>3</h1>
                                                </label>
                                            </td>
                                            <td style='vertical-align:middle;'>
                                                <span class='soal'><?= $soal[$pilCC] ?></span>
                                                <?php
                                                if ($soal[$fileCC] <> '') {
                                                    $ext = explode(".", $soal[$fileCC]);
                                                    $ext = end($ext);
                                                    if (in_array($ext, $image)) {
                                                        echo "<span  class='lup' style='display:inline-block'><img src='$homeurl/files/$soal[$fileCC]' class='img-responsive' style='width:250px;'/></span>";
                                                    } elseif (in_array($ext, $audio)) {
                                                        echo "  <audio volume='1.0' id='audio-player5' onended='audio_ended5()'>
                                                                    <source src='$homeurl/files/$soal[$fileCC]' type='audio/$ext' style='width:100%;'/>
                                                                    Your browser does not support the audio tag.
                                                                </audio>
                                                                <div style='max-width:350px' id='audio-control5'>
                                                                    <div class='card'>
                                                                        <div class='card-body'>
                                                                            <input type='hidden' id='audio-player-status5' value='0' />
                                                                            <input type='hidden' id='audio-player-update5' value='0' />
                                                                            <a class='btn btn-app' onclick='audio5()'>
                                                                                <i class='fa fa-play' id='audio-player-judul-logo5'></i> <span id='audio-player-judul5'>Play</span>
                                                                            </a>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>";
                                                    } else {
                                                        echo "File tidak didukung!";
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php if ($mapel[0]['opsi'] <> 3) : ?>
                                            <tr>
                                                <td>
                                                    <input class='hidden radio-label' type='radio' name='jawab' id='D' onclick="jawabsoal(<?= $id_mapel ?>, <?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'<?= $pil4 ?>','D',1,<?= $ac ?>)" <?= $d ?> />
                                                    <label class='button-label' for='D'>
                                                        <h1>4</h1>
                                                    </label>
                                                </td>
                                                <td style='vertical-align:middle;'>
                                                    <span class='soal'><?= $soal[$pilDD] ?></span>
                                                    <?php
                                                    if ($soal[$fileDD] <> '') {
                                                        $ext = explode(".", $soal[$fileDD]);
                                                        $ext = end($ext);
                                                        if (in_array($ext, $image)) {
                                                            echo "<span  class='lup' style='display:inline-block'><img src='$homeurl/files/$soal[$fileDD]' class='img-responsive' style='width:250px;'/></span>";
                                                        } elseif (in_array($ext, $audio)) {
                                                            echo "  <audio volume='1.0' id='audio-player6' onended='audio_ended6()'>
                                                                        <source src='$homeurl/files/$soal[$fileDD]' type='audio/$ext' style='width:100%;'/>
                                                                        Your browser does not support the audio tag.
                                                                    </audio>
                                                                    <div style='max-width:350px' id='audio-control6'>
                                                                        <div class='card'>
                                                                            <div class='card-body'>
                                                                                <input type='hidden' id='audio-player-status6' value='0' />
                                                                                <input type='hidden' id='audio-player-update6' value='0' />
                                                                                <a class='btn btn-app' onclick='audio6()'>
                                                                                    <i class='fa fa-play' id='audio-player-judul-logo6'></i> <span id='audio-player-judul6'>Play</span>
                                                                                </a>
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>";
                                                        } else {
                                                            echo "File tidak didukung!";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if ($mapel[0]['opsi'] == 5) : ?>
                                            <tr>
                                                <td>
                                                    <input class='hidden radio-label' type='radio' name='jawab' id='E' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'<?= $pil5 ?>','E',1,<?= $ac ?>)" <?= $e ?> />
                                                    <label class='button-label' for='E'>
                                                        <h1>5</h1>
                                                    </label>
                                                </td>
                                                <td style='vertical-align:middle;'>
                                                    <span class='soal'><?= $soal[$pilEE] ?></span>
                                                    <?php
                                                    if ($soal[$fileEE] <> '') {
                                                        $ext = explode(".", $soal[$fileEE]);
                                                        $ext = end($ext);
                                                        if (in_array($ext, $image)) {
                                                            echo "<span  class='lup' style='display:inline-block'><img src='$homeurl/files/$soal[$fileEE]' class='img-responsive' style='width:250px;'/></span>";
                                                        } elseif (in_array($ext, $audio)) {
                                                            echo "  <audio volume='1.0' id='audio-player7' onended='audio_ended7()'>
                                                                        <source src='$homeurl/files/$soal[$fileEE]' type='audio/$ext' style='width:100%;'/>
                                                                        Your browser does not support the audio tag.
                                                                    </audio>
                                                                    <div style='max-width:350px' id='audio-control7'>
                                                                        <div class='card'>
                                                                            <div class='card-body'>
                                                                                <input type='hidden' id='audio-player-status7' value='0' />
                                                                                <input type='hidden' id='audio-player-update7' value='0' />
                                                                                <a class='btn btn-app' onclick='audio7()'>
                                                                                    <i class='fa fa-play' id='audio-player-judul-logo7'></i> <span id='audio-player-judul7'>Play</span>
                                                                                </a>
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>";
                                                        } else {
                                                            echo "File tidak didukung!";
                                                        }
                                                    } ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                <?php } ?>
                            <?php else : ?>
                                <?php

                                $a = ($jawab['jawabx'] == 'A') ? 'checked' : '';
                                $b = ($jawab['jawabx'] == 'B') ? 'checked' : '';
                                $c = ($jawab['jawabx'] == 'C') ? 'checked' : '';
                                if ($mapel[0]['opsi'] == 4) {
                                    $d = ($jawab['jawabx'] == 'D') ? 'checked' : '';
                                }
                                if ($mapel[0]['opsi'] == 5) {
                                    $d = ($jawab['jawabx'] == 'D') ? 'checked' : '';
                                    $e = ($jawab['jawabx'] == 'E') ? 'checked' : '';
                                }
                                ?>
                                <table width='100%' class='table table-striped table-hover'>
                                    <tr>
                                        <td width='60'>
                                            <input class='hidden radio-label' type='radio' name='jawab' id='A' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'A','A',1,<?= $ac ?>)" <?= $a ?> />
                                            <label class='button-label' for='A'>
                                                <h1>1</h1>
                                            </label>
                                        </td>
                                        <td style='vertical-align:middle;'>
                                            <span class='soal'><?= $soal['pilA'] ?></span>
                                            <?php
                                            if ($soal['fileA'] <> '') {
                                                $ext = explode(".", $soal['fileA']);
                                                $ext = end($ext);
                                                if (in_array($ext, $image)) {
                                                    echo "<img src='$homeurl/files/$soal[fileA]' class='img-responsive' style='max-width:300px;'/>";
                                                } elseif (in_array($ext, $audio)) {
                                                    echo "  <audio volume='1.0' id='audio-player8' onended='audio_ended8()'>
                                                                <source src='$homeurl/files/$soal[fileA]' type='audio/$ext' style='width:100%;'/>
                                                                Your browser does not support the audio tag.
                                                            </audio>
                                                            <div style='max-width:350px' id='audio-control8'>
                                                                <div class='card'>
                                                                    <div class='card-body'>
                                                                        <input type='hidden' id='audio-player-status8' value='0' />
                                                                        <input type='hidden' id='audio-player-update8' value='0' />
                                                                        <a class='btn btn-app' onclick='audio8()'>
                                                                            <i class='fa fa-play' id='audio-player-judul-logo8'></i> <span id='audio-player-judul8'>Play</span>
                                                                        </a>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>";
                                                } else {
                                                    echo "File tidak didukung!";
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class='hidden radio-label' type='radio' name='jawab' id='B' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'B','B',1,<?= $ac ?>)" <?= $b ?> />
                                            <label class='button-label' for='B'>
                                                <h1>2</h1>
                                            </label>
                                        </td>
                                        <td style='vertical-align:middle;'>
                                            <span class='soal'><?= $soal['pilB'] ?></span>
                                            <?php
                                            if ($soal['fileB'] <> '') {
                                                $ext = explode(".", $soal['fileB']);
                                                $ext = end($ext);
                                                if (in_array($ext, $image)) {
                                                    echo "<img src='$homeurl/files/$soal[fileB]' class='img-responsive' style='max-width:300px;'/>";
                                                } elseif (in_array($ext, $audio)) {
                                                    echo "  <audio volume='1.0' id='audio-player9' onended='audio_ended9()'>
                                                                <source src='$homeurl/files/$soal[fileB]' type='audio/$ext' style='width:100%;'/>
                                                                Your browser does not support the audio tag.
                                                            </audio>
                                                            <div style='max-width:350px' id='audio-control9'>
                                                                <div class='card'>
                                                                    <div class='card-body'>
                                                                        <input type='hidden' id='audio-player-status9' value='0' />
                                                                        <input type='hidden' id='audio-player-update9' value='0' />
                                                                        <a class='btn btn-app' onclick='audio9()'>
                                                                            <i class='fa fa-play' id='audio-player-judul-logo9'></i> <span id='audio-player-judul9'>Play</span>
                                                                        </a>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>";
                                                } else {
                                                    echo "File tidak didukung!";
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class='hidden radio-label' type='radio' name='jawab' id='C' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'C','C',1,<?= $ac ?>)" <?= $c ?> />
                                            <label class='button-label' for='C'>
                                                <h1>3</h1>
                                            </label>

                                        </td>
                                        <td style='vertical-align:middle;'>
                                            <span class='soal'><?= $soal['pilC'] ?></span>
                                            <?php
                                            if ($soal['fileC'] <> '') {
                                                $ext = explode(".", $soal['fileC']);
                                                $ext = end($ext);
                                                if (in_array($ext, $image)) {
                                                    echo "<img src='$homeurl/files/$soal[fileC]' class='img-responsive' style='max-width:300px;'/>";
                                                } elseif (in_array($ext, $audio)) {
                                                    echo "  <audio volume='1.0' id='audio-player10' onended='audio_ended10()'>
                                                                <source src='$homeurl/files/$soal[fileC]' type='audio/$ext' style='width:100%;'/>
                                                                Your browser does not support the audio tag.
                                                            </audio>
                                                            <div style='max-width:350px' id='audio-control10'>
                                                                <div class='card'>
                                                                    <div class='card-body'>
                                                                        <input type='hidden' id='audio-player-status10' value='0' />
                                                                        <input type='hidden' id='audio-player-update10' value='0' />
                                                                        <a class='btn btn-app' onclick='audio10()'>
                                                                            <i class='fa fa-play' id='audio-player-judul-logo10'></i> <span id='audio-player-judul10'>Play</span>
                                                                        </a>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>";
                                                } else {
                                                    echo "File tidak didukung!";
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php if ($mapel[0]['opsi'] <> 3) { ?>
                                        <tr>
                                            <td>
                                                <input class='hidden radio-label' type='radio' name='jawab' id='D' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'D','D',1,<?= $ac ?>)" <?= $d ?> />
                                                <label class='button-label' for='D'>
                                                    <h1>4</h1>
                                                </label>
                                            </td>
                                            <td style='vertical-align:middle;'>
                                                <span class='soal'><?= $soal['pilD'] ?></span>
                                                <?php
                                                if ($soal['fileD'] <> '') {
                                                    $ext = explode(".", $soal['fileD']);
                                                    $ext = end($ext);
                                                    if (in_array($ext, $image)) {
                                                        echo "<img src='$homeurl/files/$soal[fileD]' class='img-responsive' style='max-width:300px;'/>";
                                                    } elseif (in_array($ext, $audio)) {
                                                        echo "  <audio volume='1.0' id='audio-player11' onended='audio_ended11()'>
                                                                    <source src='$homeurl/files/$soal[fileD]' type='audio/$ext' style='width:100%;'/>
                                                                    Your browser does not support the audio tag.
                                                                </audio>
                                                                <div style='max-width:350px' id='audio-control11'>
                                                                    <div class='card'>
                                                                        <div class='card-body'>
                                                                            <input type='hidden' id='audio-player-status11' value='0' />
                                                                            <input type='hidden' id='audio-player-update11' value='0' />
                                                                            <a class='btn btn-app' onclick='audio11()'>
                                                                                <i class='fa fa-play' id='audio-player-judul-logo11'></i> <span id='audio-player-judul11'>Play</span>
                                                                            </a>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>";
                                                    } else {
                                                        echo "File tidak didukung!";
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if ($mapel[0]['opsi'] == 5) { ?>
                                        <tr>
                                            <td>
                                                <input class='hidden radio-label' type='radio' name='jawab' id='E' onclick="jawabsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,'E','E',1,<?= $ac ?>)" <?= $e ?> />
                                                <label class='button-label' for='E'>
                                                    <h1>5</h1>
                                                </label>
                                            </td>
                                            <td style='vertical-align:middle;'>
                                                <span class='soal'><?= $soal['pilE'] ?></span>
                                                <?php
                                                if ($soal['fileE'] <> '') {

                                                    $ext = explode(".", $soal['fileE']);
                                                    $ext = end($ext);
                                                    if (in_array($ext, $image)) {
                                                        echo "<img src='$homeurl/files/$soal[fileE]' class='img-responsive' style='max-width:300px;'/>";
                                                    } elseif (in_array($ext, $audio)) {
                                                        echo "  <audio volume='1.0' id='audio-player12' onended='audio_ended12()'>
                                                                    <source src='$homeurl/files/$soal[fileE]' type='audio/$ext' style='width:100%;'/>
                                                                    Your browser does not support the audio tag.
                                                                </audio>
                                                                <div style='max-width:350px' id='audio-control12'>
                                                                    <div class='card'>
                                                                        <div class='card-body'>
                                                                            <input type='hidden' id='audio-player-status12' value='0' />
                                                                            <input type='hidden' id='audio-player-update12' value='0' />
                                                                            <a class='btn btn-app' onclick='audio12()'>
                                                                                <i class='fa fa-play' id='audio-player-judul-logo12'></i> <span id='audio-player-judul12'>Play</span>
                                                                            </a>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>";
                                                    } else {
                                                        echo "File tidak didukung!";
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                    <?php if ($soal['jenis'] == 2) { ?>
                        <div class='col-md-12'>
                            <textarea id='jawabesai' name='textjawab' style='height:200px' class='form-control' onchange="jawabesai(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>,2)"><?= $jawab['esai'] ?></textarea>
                            <br><br>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class='box-footer navbar-fixed-bottom'>
                <table width='100%'>
                    <tr>
                        <td style="text-align:center">
                            <button id='move-prev' class='btn  btn-primary' onclick="loadsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $no_prev ?>)"><i class='fas fa-chevron-circle-left'></i> <span class='hidden-xs'>Soal Sebelumnya</span></button>
                            <i class='fa fa-spin fa-spinner' id='spin-prev' style='display:none;'></i>
                        </td>
                        <?php if ($soal['jenis'] == 1) { ?>
                            <td style="text-align:center">

                                <div id='load-ragu'>
                                    <a href='#' class='btn btn-warning'><input type='checkbox' onclick="radaragu(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $soal['id_soal'] ?>, <?= $ac ?>)" <?= $ragu = ($jawab['ragu'] == 1) ? 'checked' : ''; ?> /> Ragu <span class='hidden-xs'>- Ragu</span></a>
                                </div>

                            </td>
                        <?php } ?>
                        <td style="text-align:center">
                            <?php
                            $jumsoalpg = $mapel[0]['tampil_pg'] + $mapel[0]['tampil_esai'];
                            $cekno_soal = $no_soal + 1;
                            ?>
                            <?php if (($no_soal >= 0) && ($cekno_soal < $jumsoalpg)) { ?>

                                <i class='fa fa-spin fa-spinner' id='spin-next' style='display:none;'></i>
                                <button id='move-next' class='btn  btn-primary' onclick="loadsoal(<?= $id_mapel ?>,<?= $id_siswa ?>,<?= $no_next ?>)"><span class='hidden-xs'>Soal Selanjutnya </span><i class='fas fa-chevron-circle-right'></i></button>

                            <?php } elseif (($no_soal >= 0) && ($cekno_soal = $jumsoalpg) && ($jumsoalesai == 0 || $jumsoalesai == '')) { ?>

                                <input type='submit' name='done' id='selesai-submit' style='display:none;' />
                                <button class='done-btn btn btn-danger'><i class="fas fa-flag-checkered    "></i> <span class='hidden-xs'>TEST </span>SELESAI</button>


                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
    <?php
        }
    }
    ?>
    <script>
        $(document).ready(function() {
            $('#zoom').zoom();
            $('#zoom1').zoom();
            $('.lup').zoom();
            $('.soal img')
                .wrap('<span style="display:inline-block"></span>')
                .css('display', 'block')
                .parent()
                .zoom();
        });
    </script>
    <script>
        $(document).ready(function() {
            Mousetrap.bind('enter', function() {
                loadsoal(<?= $id_mapel . "," . $id_siswa . "," . $no_next ?>);
            });

            Mousetrap.bind('right', function() {
                loadsoal(<?= $id_mapel . "," . $id_siswa . "," . $no_next ?>);
            });

            Mousetrap.bind('left', function() {
                loadsoal(<?= $id_mapel . "," . $id_siswa . "," . $no_prev  ?>);
            });

            Mousetrap.bind('a', function() {
                $('#A').click()
            });

            Mousetrap.bind('b', function() {
                $('#B').click()
            });

            Mousetrap.bind('c', function() {
                $('#C').click()
            });

            Mousetrap.bind('d', function() {
                $('#D').click()
            });

            Mousetrap.bind('e', function() {
                $('#E').click()
            });

            Mousetrap.bind('space', function() {
                $('input[type=checkbox]').click()
                radaragu(<?= $id_mapel . "," . $id_siswa . "," . $soal['id_soal'] ?>, <?= $ac ?>)
            });

        });
    </script>
    <script>
        MathJax.Hub.Typeset()
    </script>
    <script>
        function audio(){
            var audio_player_status = $('#audio-player-status').val();
            var audio_player_update = $('#audio-player-update').val();
            
            if(audio_player_status==0){
                $('#audio-player-status').val('1');
                $('#audio-player').trigger('play');
                $('#audio-player-judul').html('Pause');
                $('#audio-player-judul-logo').removeClass('fa-play');
                $('#audio-player-judul-logo').addClass('fa-pause');
            }else if(audio_player_status==1){
                $('#audio-player-status').val('0');
                $('#audio-player').trigger('pause');
                $('#audio-player-judul').html('Play');
                $('#audio-player-judul-logo').removeClass('fa-pause');
                $('#audio-player-judul-logo').addClass('fa-play');
            }
        }

        function audio_ended(){
                $('#audio-player-status').val('2');
        }

        function audio2(){
            var audio_player_status2 = $('#audio-player-status2').val();
            var audio_player_update2 = $('#audio-player-update2').val();
            
            if(audio_player_status2==0){
                $('#audio-player-status2').val('1');
                $('#audio-player2').trigger('play');
                $('#audio-player-judul2').html('Pause');
                $('#audio-player-judul-logo2').removeClass('fa-play');
                $('#audio-player-judul-logo2').addClass('fa-pause');
            }else if(audio_player_status2==1){
                $('#audio-player-status2').val('0');
                $('#audio-player2').trigger('pause');
                $('#audio-player-judul2').html('Play');
                $('#audio-player-judul-logo2').removeClass('fa-pause');
                $('#audio-player-judul-logo2').addClass('fa-play');
            }
        }

        function audio_ended2(){
                $('#audio-player-status2').val('2');       
        }

        function audio3(){
            var audio_player_status3= $('#audio-player-status3').val();
            var audio_player_update3 = $('#audio-player-update3').val();
            
            if(audio_player_status3==0){
                $('#audio-player-status3').val('1');
                $('#audio-player3').trigger('play');
                $('#audio-player-judul3').html('Pause');
                $('#audio-player-judul-logo3').removeClass('fa-play');
                $('#audio-player-judul-logo3').addClass('fa-pause');
            }else if(audio_player_status3==1){
                $('#audio-player-status3').val('0');
                $('#audio-player3').trigger('pause');
                $('#audio-player-judul3').html('Play');
                $('#audio-player-judul-logo3').removeClass('fa-pause');
                $('#audio-player-judul-logo3').addClass('fa-play');
            }
        }

        function audio_ended3(){
            
                $('#audio-player-status3').val('2');
                
        }

        function audio4(){
            var audio_player_status4 = $('#audio-player-status4').val();
            var audio_player_update4 = $('#audio-player-update4').val();
            
            if(audio_player_status4==0){
                $('#audio-player-status4').val('1');
                $('#audio-player4').trigger('play');
                $('#audio-player-judul4').html('Pause');
                $('#audio-player-judul-logo4').removeClass('fa-play');
                $('#audio-player-judul-logo4').addClass('fa-pause');
            }else if(audio_player_status4==1){
                $('#audio-player-status4').val('0');
                $('#audio-player4').trigger('pause');
                $('#audio-player-judul4').html('Play');
                $('#audio-player-judul-logo4').removeClass('fa-pause');
                $('#audio-player-judul-logo4').addClass('fa-play');
            }
        }

        function audio_ended4(){
            
                $('#audio-player-status4').val('2');
                
        }

        function audio5(){
            var audio_player_status5 = $('#audio-player-status5').val();
            var audio_player_update5 = $('#audio-player-update5').val();
            
            if(audio_player_status5==0){
                $('#audio-player-status5').val('1');
                $('#audio-player5').trigger('play');
                $('#audio-player-judul5').html('Pause');
                $('#audio-player-judul-logo5').removeClass('fa-play');
                $('#audio-player-judul-logo5').addClass('fa-pause');
            }else if(audio_player_status5==1){
                $('#audio-player-status5').val('0');
                $('#audio-player5').trigger('pause');
                $('#audio-player-judul5').html('Play');
                $('#audio-player-judul-logo5').removeClass('fa-pause');
                $('#audio-player-judul-logo5').addClass('fa-play');
            }
        }

        function audio_ended5(){
            
                $('#audio-player-status5').val('2');
                
        }

        function audio6(){
            var audio_player_status6 = $('#audio-player-status6').val();
            var audio_player_update6 = $('#audio-player-update6').val();
            
            if(audio_player_status6==0){
                $('#audio-player-status6').val('1');
                $('#audio-player6').trigger('play');
                $('#audio-player-judul6').html('Pause');
                $('#audio-player-judul-logo6').removeClass('fa-play');
                $('#audio-player-judul-logo6').addClass('fa-pause');
            }else if(audio_player_status6==1){
                $('#audio-player-status6').val('0');
                $('#audio-player6').trigger('pause');
                $('#audio-player-judul6').html('Play');
                $('#audio-player-judul-logo6').removeClass('fa-pause');
                $('#audio-player-judul-logo6').addClass('fa-play');
            }
        }

        function audio_ended6(){
            
                $('#audio-player-status6').val('2');
                
        }

        function audio7(){
            var audio_player_status7 = $('#audio-player-status7').val();
            var audio_player_update7 = $('#audio-player-update7').val();
            
            if(audio_player_status7==0){
                $('#audio-player-status7').val('1');
                $('#audio-player7').trigger('play');
                $('#audio-player-judul7').html('Pause');
                $('#audio-player-judul-logo7').removeClass('fa-play');
                $('#audio-player-judul-logo7').addClass('fa-pause');
            }else if(audio_player_status7==1){
                $('#audio-player-status7').val('0');
                $('#audio-player7').trigger('pause');
                $('#audio-player-judul7').html('Play');
                $('#audio-player-judul-logo7').removeClass('fa-pause');
                $('#audio-player-judul-logo7').addClass('fa-play');
            }
        }

        function audio_ended7(){
           
                $('#audio-player-status7').val('2');
                
        }

        function audio8(){
            var audio_player_status8 = $('#audio-player-status8').val();
            var audio_player_update8 = $('#audio-player-update8').val();
            
            if(audio_player_status8==0){
                $('#audio-player-status8').val('1');
                $('#audio-player8').trigger('play');
                $('#audio-player-judul8').html('Pause');
                $('#audio-player-judul-logo8').removeClass('fa-play');
                $('#audio-player-judul-logo8').addClass('fa-pause');
            }else if(audio_player_status8==1){
                $('#audio-player-status8').val('0');
                $('#audio-player8').trigger('pause');
                $('#audio-player-judul8').html('Play');
                $('#audio-player-judul-logo8').removeClass('fa-pause');
                $('#audio-player-judul-logo8').addClass('fa-play');
            }
        }

        function audio_ended8(){
            
                $('#audio-player-status8').val('2');
                
        }

        function audio9(){
            var audio_player_status9 = $('#audio-player-status9').val();
            var audio_player_update9 = $('#audio-player-update9').val();
            
            if(audio_player_status9==0){
                $('#audio-player-status9').val('1');
                $('#audio-player9').trigger('play');
                $('#audio-player-judul9').html('Pause');
                $('#audio-player-judul-logo9').removeClass('fa-play');
                $('#audio-player-judul-logo9').addClass('fa-pause');
            }else if(audio_player_status9==1){
                $('#audio-player-status9').val('0');
                $('#audio-player9').trigger('pause');
                $('#audio-player-judul9').html('Play');
                $('#audio-player-judul-logo9').removeClass('fa-pause');
                $('#audio-player-judul-logo9').addClass('fa-play');
            }
        }

        function audio_ended9(){
            
                $('#audio-player-status9').val('2');
                
        }

        function audio10(){
            var audio_player_status10 = $('#audio-player-status10').val();
            var audio_player_update10 = $('#audio-player-update10').val();
            
            if(audio_player_status10==0){
                $('#audio-player-status10').val('1');
                $('#audio-player10').trigger('play');
                $('#audio-player-judul10').html('Pause');
                $('#audio-player-judul-logo10').removeClass('fa-play');
                $('#audio-player-judul-logo10').addClass('fa-pause');
            }else if(audio_player_status10==1){
                $('#audio-player-status10').val('0');
                $('#audio-player10').trigger('pause');
                $('#audio-player-judul10').html('Play');
                $('#audio-player-judul-logo10').removeClass('fa-pause');
                $('#audio-player-judul-logo10').addClass('fa-play');
            }
        }

        function audio_ended10(){
            
                $('#audio-player-status10').val('2');
                
        }

        function audio11(){
            var audio_player_status11 = $('#audio-player-status11').val();
            var audio_player_update11 = $('#audio-player-update11').val();
            
            if(audio_player_status11==0){
                $('#audio-player-status11').val('1');
                $('#audio-player11').trigger('play');
                $('#audio-player-judul11').html('Pause');
                $('#audio-player-judul-logo11').removeClass('fa-play');
                $('#audio-player-judul-logo11').addClass('fa-pause');
            }else if(audio_player_status11==1){
                $('#audio-player-status11').val('0');
                $('#audio-player11').trigger('pause');
                $('#audio-player-judul11').html('Play');
                $('#audio-player-judul-logo11').removeClass('fa-pause');
                $('#audio-player-judul-logo11').addClass('fa-play');
            }
        }

        function audio_ended11(){
            
                $('#audio-player-status11').val('2');
                
        }

        function audio12(){
            var audio_player_status12 = $('#audio-player-status12').val();
            var audio_player_update12 = $('#audio-player-update12').val();
            
            if(audio_player_status12==0){
                $('#audio-player-status12').val('1');
                $('#audio-player12').trigger('play');
                $('#audio-player-judul12').html('Pause');
                $('#audio-player-judul-logo12').removeClass('fa-play');
                $('#audio-player-judul-logo12').addClass('fa-pause');
            }else if(audio_player_status12==1){
                $('#audio-player-status12').val('0');
                $('#audio-player12').trigger('pause');
                $('#audio-player-judul12').html('Play');
                $('#audio-player-judul-logo12').removeClass('fa-pause');
                $('#audio-player-judul-logo12').addClass('fa-play');
            }
        }

        function audio_ended12(){
            
                $('#audio-player-status12').val('2');
                
        }
    </script>
<?php } ?>
<?php
if ($pg == 'jawab') {
    $jenis = $_POST['jenis'];
    $dataesai = array(
        'id_ujian' => $_POST['idu'],
        'id_mapel' => $_POST['id_mapel'],
        'id_siswa' => $_POST['id_siswa'],
        'id_soal' => $_POST['id_soal'],
        'jenis' => $_POST['jenis'],
        'esai' => addslashes($_POST['jawaban'])
    );
    $data = array(
        'id_ujian' => $_POST['idu'],
        'id_mapel' => $_POST['id_mapel'],
        'id_siswa' => $_POST['id_siswa'],
        'id_soal' => $_POST['id_soal'],
        'jenis' => $_POST['jenis'],
        'jawaban' => $_POST['jawaban'],
        'jawabx' => $_POST['jawabx']
    );
    $where = array(
        'id_ujian' => $_POST['idu'],
        'id_mapel' => $_POST['id_mapel'],
        'id_siswa' => $_POST['id_siswa'],
        'id_soal' => $_POST['id_soal'],
        'jenis' => $jenis
    );
    $cekjawaban = rowcount($koneksi, 'jawaban', $where);
    // $cekjawaban = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM jawaban WHERE id_ujian=". $_POST['id_ujian'] ." AND id_mapel=". $_POST['id_mapel']. " AND id_siswa=". $_POST['id_siswa'] ." AND id_soal=". $_POST['id_soal'] ." AND jenis=". $jenis));

    if ($jenis == 1) {
        if ($cekjawaban <= 0) {
            $exec = insert($koneksi, 'jawaban', $data);
            // $exec = mysqli_query($koneksi, "INSERT INTO jawaban(id_ujian,id_mapel,id_siswa,id_soal,jenis,jawaban,jawabx) VALUES(". $_POST['idu'] .", ". $_POST['id_mapel'] .", ". $_POST['id_siswa'] .", ". $_POST['id_soal'] .", ". $jenis .", '". $_POST['jawaban'] ."', '". $_POST['jawabx'] ."')");
        } else {
            $exec = update($koneksi, 'jawaban', $data, $where);
            // $exec = mysqli_query($koneksi, "UPDATE jawaban SET jawaban='". $_POST['jawaban'] ."', jawabx='". $_POST['jawabx'] ."' WHERE id_ujian=". $_POST['idu'] ." AND id_mapel=". $_POST['id_mapel'] ." AND id_siswa=". $_POST['id_siswa'] ." AND id_soal=". $_POST['id_soal'] ." AND jenis=". $jenis);
        }
    } else {
        if ($cekjawaban == 0) {
            $exec = insert($koneksi, 'jawaban', $dataesai);
            // $exec = mysqli_query($koneksi, "INSERT INTO jawaban(id_ujian,id_mapel,id_siswa,id_soal,jenis,esai) VALUES(". $_POST['idu'] .", ". $_POST['id_mapel'] .", ". $_POST['id_siswa'] .", ". $_POST['id_soal'] .", ". $jenis .", '". addslashes($_POST['jawaban']) ."')");
        } else {
            $exec = update($koneksi, 'jawaban', $dataesai, $where);
            // $exec = mysqli_query($koneksi, "UPDATE jawaban SET esai='". addslashes($_POST['jawaban']) ."' WHERE id_ujian=". $_POST['idu'] ." AND id_mapel=". $_POST['id_mapel'] ." AND id_siswa=". $_POST['id_siswa'] ." AND id_soal=". $_POST['id_soal'] ." AND jenis=". $jenis);
        }
    }
    echo $exec;
    exit();

} elseif ($pg == 'ragu') {
    $where = array(
        'id_mapel' => $_POST['id_mapel'],
        'id_siswa' => $_POST['id_siswa'],
        'id_soal' => $_POST['id_soal'],
        'id_ujian' => $_POST['id_ujian'],
        'jenis' => 1
    );
    $cekragu = fetch($koneksi, 'jawaban', $where);
    if ($cekragu['ragu'] == 0) {
        $exec = update($koneksi, 'jawaban', array('ragu' => 1), $where);
        // $exec = mysqli_query($koneksi, "UPDATE jawaban SET ragu=1 WHERE id_ujian=". $_POST['id_ujian'] ." AND id_mapel=". $_POST['id_mapel'] ." AND id_siswa=". $_POST['id_siswa'] ." AND id_soal=". $_POST['id_soal'] ." AND jenis=1");
    } else {
        $exec = update($koneksi, 'jawaban', array('ragu' => 0), $where);
        // $exec = mysqli_query($koneksi, "UPDATE jawaban SET ragu=0 WHERE id_ujian=". $_POST['id_ujian'] ." AND id_mapel=". $_POST['id_mapel'] ." AND id_siswa=". $_POST['id_siswa'] ." AND id_soal=". $_POST['id_soal'] ." AND jenis=1");
    }
    echo $exec;
    exit();
}
?>