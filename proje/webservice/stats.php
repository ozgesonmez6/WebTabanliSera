<?php
	session_start();

	if (!isset($_SESSION["first_name"]) || !isset($_SESSION["last_name"]) || !isset($_SESSION["database_number"])) {
		header("LOCATION: login.php");
	}

	include_once('functions.php');

	// Global variables
	$page = 'stats';
    $devices = json_decode(send_data('GET','http://localhost/projects/webservice/controller.php', 'database_number=' . $_SESSION['database_number']), true);
    $data = [];

	include_once('header.php');

	if (isset($_GET['mac_address'])) {
		$mac_address = $_GET['mac_address'];

		foreach($devices as $id => $device) {
		    if ($device['mac_address'] == $mac_address) {
		        $device_id = $device['id'];
		        break;
            }
        }
    }
?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        İstatistikler <?php echo '<small>Cihaz Ayrıntıları</small>'; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <?php
                            if (isset($_GET['mac_address'])) {
                                echo '<li>
                                        <i class="fa fa-bar-chart-o"></i>  <a href="stats.php">İstatistikler</a>
                                      </li>
                                      <li class="active">
                                        <i class="glyphicon glyphicon-phone"></i> Cihaz [Mac Adresi: '.$mac_address.']
                                      </li>';
                            } else {
                               echo '<li class="active">
                                        <i class="fa fa-bar-chart-o"></i> İstatistikler
                                     </li>';
                            }
                        ?>
                    </ol>
                </div>
            </div>
            <!-- /.row -->

            <?php
                if (isset($_GET['mac_address'])) {
            ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Veri Grafiği</h3>
                                </div>
                                <div class="panel-body">
                                    <div id="device-data-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="glyphicon glyphicon-list-alt"></i> Veri Tablosu</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                                            <thead>
                                                <tr>
                                                    <th>Sensör Türü</th>
                                                    <th>Veri</th>
                                                    <th>Tarih - Saat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $data = json_decode(send_data('GET','http://localhost/projects/webservice/controller.php', 'database_number='.$_SESSION['database_number'].'&mac_address='.$_GET['mac_address'].'&sorting=1&sensor_type=0&limit=50'), true);

                                                if (!isset($data['type'])) {
                                                    foreach ($data as $id => $row) {
														echo '<tr>';
														echo '<td>'.$row['sensor_type_id'].'</td>';
														echo '<td>'.$row['data'].'</td>';
														echo '<td>'.$row['datetime'].'</td>';
														echo '</tr>';
													}
                                                } else {
													echo '<tr>';
													echo '<td colspan="3" style="text-align: center;">Gösterilecek herhangi bir veri yok!</td>';
													echo '</tr>';
												}
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

            <?php
                } else {
            ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="page-header">Cihazlar</h2>
                            <p class="lead">Cihazlarınızı ve konumlarını bu alanda görebilirsiniz.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Cihaz Listesi
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Mac Adresi</th>
                                            <th>Türü</th>
                                            <th>Durumu</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            foreach ($devices as $id => $device) {
                                                $mac_address = $device['mac_address'];
                                                $type = $device['type_name'];
                                                $status = $device['status'];

                                                if ($status) {
                                                    $status = '<span class="glyphicon glyphicon-ok" style="color: #008000;"></span> Çalışıyor';
                                                } elseif (!$status) {
                                                    $status = '<span class="glyphicon glyphicon-pause" style="color: #f1c40f;"></span> Duraklatıldı';
                                                }

                                                echo '<tr>';
                                                echo '<td>' . $mac_address . '</td>';
                                                echo '<td>' . $type . '</td>';
                                                echo '<td>' . $status . '</td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="glyphicon glyphicon-flag"></i> Cihaz Konumları
                                    </h3>
                                </div>
                                <div class="panel-body">

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flot Charts -->
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="page-header">Veriler</h2>
                            <p class="lead">Cihazlarınızdan gelen verileri bu alanda görebilirsiniz.</p>
                        </div>
                    </div>
                    <!-- /.row -->

                    <?php
                    $count = 0;

                    foreach ($devices as $id => $device) {
                        $mac_address = $device['mac_address'];
                        $status = $device['status'];

                        if (!($count & 1)) {
                            echo '<div class="row">';
                        }

                        echo '<div class="col-lg-6">';
                        echo '<div class="panel panel-' . ($status == 1 ? 'green' : 'yellow') . '">';
                        echo '<div class="panel-heading">';
                        echo '<h3 class="panel-title"><i class="glyphicon glyphicon-phone"></i> Mac Adresi: ' . $mac_address . ' <span class="' . ($status == 1 ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-pause') . ' pull-right"></span></h3>';
                        echo '</div>';
                        echo '<div class="panel-body">';
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-bordered table-hover">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Sensör Türü</th>';
                        echo '<th>Veri</th>';
                        echo '<th>Tarih - Saat</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

						$data = json_decode(send_data('GET','http://localhost/projects/webservice/controller.php', 'database_number='.$_SESSION['database_number'].'&mac_address='.$mac_address.'&sorting=1&sensor_type=0&limit=10'), true);

						if (!isset($data['type'])) {
                            foreach ($data as $id => $row) {
                                echo '<tr>';
                                echo '<td>' . $row['sensor_type_id'] . '</td>';
                                echo '<td>' . $row['data'] . '</td>';
                                echo '<td>' . $row['datetime'] . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr>';
                            echo '<td colspan="3" style="text-align: center;">Gösterilecek herhangi bir veri yok!</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        echo '<div class="text-right">';
                        echo '<a href="?mac_address=' . $mac_address . '">Ayrıntıları Gör <i class="fa fa-arrow-circle-right"></i></a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';

                        if ($count & 1) {
                            echo '</div>';
                        }

                        $count++;
                    }
                    ?>
                    <!-- /.row -->
            <?php
				}
            ?>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

<?php
	include_once('footer.php');
?>