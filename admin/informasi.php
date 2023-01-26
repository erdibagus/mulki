<?php
defined('APLIKASI') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>


	<link rel='stylesheet' href='<?= $homeurl ?>/dist/bootstrap/css/bootstrap.min.css' />
	<link rel='stylesheet' href='<?= $homeurl ?>/plugins/fontawesome/css/all.css' />
	<link rel='stylesheet' href='<?= $homeurl ?>/plugins/select2/select2.min.css' />
	<link rel='stylesheet' href='<?= $homeurl ?>/dist/css/AdminLTE.min.css' />
	<link rel='stylesheet' href='<?= $homeurl ?>/plugins/datatables/extensions/Select/css/select.bootstrap.css' />
	<link rel='stylesheet' href='<?= $homeurl ?>/plugins/sweetalert2/dist/sweetalert2.min.css'>
	<link rel='stylesheet' href='<?= $homeurl ?>/dist/css/costum.css' />
<div class='row'>
							<div class="col-md-6">
								<div class="box box-solid">

									<!-- /.box-header -->
									<div class="box-body">
										<div class="chartjs-size-monitor">
											<div class="chartjs-size-monitor-expand">
												<div class=""></div>
											</div>
											<div class="chartjs-size-monitor-shrink">
												<div class=""></div>
											</div>
										</div>
										<p><b>Statistik Sekolah</b></p>
										<p>Data Statistik Sekolah </p>
										<canvas id="chart-sek" class="chartjs-render-monitor" style="display: block; width: 323px; height: 190px;"></canvas>
									</div>
									<!-- /.box-body -->
								</div>
								<!-- /.box -->
							</div>
							<div class="col-md-6">
								<div class="box box-solid">

									<!-- /.box-header -->
									<div class="box-body">
										<div class="chartjs-size-monitor">
											<div class="chartjs-size-monitor-expand">
												<div class=""></div>
											</div>
											<div class="chartjs-size-monitor-shrink">
												<div class=""></div>
											</div>
										</div>
										<p><b>Statistik Ujian</b></p>
										<p>Data Statistik Ujian </p>
										<canvas id="chart-sek2" class="chartjs-render-monitor" style="display: block; width: 323px; height: 190px;"></canvas>
									</div>
									<!-- /.box-body -->
								</div>
								<!-- /.box -->
							</div>

							<!-- <div class="col-md-12">
								<div class="box box-solid"> -->
									<!-- /.box-header -->
									<!-- <div class="box-body">
										<p><b>Kontak Admin</b></p>

										<ul class="list-group">
											<li class="list-group-item">
												<a href="https://wa.me/62895386114985" target="_blank" class="btn btn-success">
													<i class="fab fa-whatsapp"></i> Whatsapp
												</a></li>
											<li class="list-group-item">
												<a href="https://t.me/aplikasi_pendidikan" target="_blank" class="btn btn-primary">
													<i class="fab fa-telegram-plane"></i> Telegram
												</a></li>
											<li class="list-group-item">
												<a href="https://www.youtube.com/channel/UCsiAiiC3LKnkeoHp2brYefA" target="_blank" class="btn btn-danger">
													<i class="fab fa-youtube"></i> Youtube
												</a></li>	
											<li class="list-group-item">
												<a href="mailto:shareaplikasipendidikan@gmail.com" target="_blank" class="btn btn-info">
													<i class="far fa-envelope"></i> Kirim Email
												</a></li>
										</ul>
									</div>
								</div>
							</div> -->