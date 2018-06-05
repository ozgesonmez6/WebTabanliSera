<?php
	// Veritabanı config dosyasını include ediyoruz.
	include_once('config.php');

	// İçerik tipini json olarak ayarlıyoruz. Çünkü sunucu yanıt mesajı bu tipte gönderilecek.
	header('Content-Type: application/json');

	// Global değişkenlerimiz
	$method = $_SERVER['REQUEST_METHOD']; // İstek metodunu çekiyoruz. (GET ya da POST)
	$current_datetime = date('m.d.Y h:i:s', time()); // Şu anki tarih ve zaman bilgisini alıyoruz.
	$response_message = [];

	// Eğer metod POST ise bu veritabanına veri eklemek istediğimiz anlamına geliyor
	if ($method == 'POST' && isset($_POST['database_number']) && isset($_POST['mac_address']) && isset($_POST['data'])
						  && !empty($_POST['database_number']) && !empty($_POST['mac_address']) && !empty($_POST['data'])) {

		// Hangi veritabanına eklenecekse ona göre bağlantı sağlayacağız. (Her kullanıcının farklı veritabanı var)
		$database_name = 'db_' . $_POST['database_number'];

		// Veritabanına bağlantı sağlıyoruz
		@$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, $database_name);
		if (!$mysqli->connect_errno) {
			$mysqli->query("SET NAMES UTF8");

			$query = "SELECT id
					  FROM cihazlar
					  WHERE mac_adresi = '" . $mysqli->real_escape_string($_POST['mac_address']) . "'";

			// Mac adresine göre cihaz id'sini çekiyoruz
			if ($result = $mysqli->query($query)) {
				if ($result->num_rows > 0) {
					$device_id = intval($result->fetch_assoc()['id']);

					// Gelen json'ı parse ediyoruz
					if ($data = json_decode($_POST['data'], TRUE)) {
						$err = false;
						foreach ($data as $id => $row) {
							
							$query = "INSERT INTO veriler (cihaz_id, sensor_turu_id, veri, tarih_saat) VALUES (" . $device_id . ", " . $mysqli->real_escape_string($row['sensor_type_id']) . ", " . $mysqli->real_escape_string($row['value']) . ", now())";

							// Parse ettiğimiz veriyi veritabanına kaydediyoruz
							if (!$mysqli->query($query)) {
								$err = true;
							}
						}
						
						if (!$err) {
							$response_message = ['type' => 'success', 'title' => 'Request is successful', 'description' => 'New data has inserted to database!'];
							die(json_encode($response_message));
						}
					}
				}
			}
		}

	// Eğer POST metoduyla kullanıcı adı ve şifre yollanmış ve başarılıysa kullanıcı hakkında gerekli bilgileri geri döndüreceğiz
	} elseif ($method == 'POST' && isset($_POST['username']) && isset($_POST['password'])
								&& !empty($_POST['username']) && !empty($_POST['password'])) {

		// Öncelikle veritabanına bağlanalım
		@$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (!$mysqli->connect_errno) {
			$mysqli->query("SET NAMES UTF8");

			$query = "SELECT ad first_name, soyad last_name
					  FROM kullanicilar
					  WHERE kullanici_adi = '" . $mysqli->real_escape_string($_POST['username']) . "' AND sifre = '" . $mysqli->real_escape_string($_POST['password']) . "'";

			if ($result = $mysqli->query($query)) {
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$row['database_number'] = intval($row['database_number']);

					die(json_encode($row));
				} else {
					$response_message = ['type' => 'error', 'title' => 'Login failed', 'description' => 'Username or password is wrong'];
					die(json_encode($response_message));
				}
			}
		}

	// Eğer metod GET ise veritabanından veri çekeceğiz
	} elseif ($method == 'GET' && isset($_GET['database_number']) && isset($_GET['mac_address']) && isset($_GET['sensor_type']) && isset($_GET['sorting']) && (isset($_GET['limit']) || (isset($_GET['first_date']) && isset($_GET['last_date'])))
		&& !empty($_GET['database_number']) && !empty($_GET['mac_address']) && $_GET['sensor_type'] != "" && $_GET['sorting'] != "" && (!empty($_GET['limit']) || (!empty($_GET['first_date']) && !empty($_GET['last_date'])))) {
		$database_name = 'db_' . $_GET['database_number'];

		@$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, $database_name);
		if (!$mysqli->connect_errno) {

			$query = "SELECT id
					  FROM cihazlar
					  WHERE mac_adresi = '" . $mysqli->real_escape_string($_GET['mac_address']) . "'";

			// Mac adresine göre cihaz id'sini çekiyoruz
			if ($result = $mysqli->query($query)) {
				if ($result->num_rows > 0) {
					$device_id = intval($result->fetch_assoc()['id']);

					$_GET['sensor_type'] = intval($_GET['sensor_type']);
					$_GET['sorting'] = intval($_GET['sorting']);
					$queue = " WHERE cihaz_id = '" . $device_id . "'";
					$data = [];

					if ($_GET['sensor_type']) {
						$queue .= " AND sensor_turu_id = " . $mysqli->real_escape_string($_GET['sensor_type']);
						$con = true;
					}

					if (isset($_GET['first_date']) && isset($_GET['last_date'])) {
						$queue .= " AND tarih_saat >= '" . $mysqli->real_escape_string($_GET['first_date']) . "' AND tarih_saat <= '" . $mysqli->real_escape_string($_GET['last_date']) . "'";
					}

					if (!$_GET['sorting']) {
						$queue .= ' ORDER BY tarih_saat ASC';
					} else {
						$queue .= ' ORDER BY tarih_saat DESC';
					}

					if (isset($_GET['limit'])) {
						$queue .= ' LIMIT ' . $mysqli->real_escape_string($_GET['limit']);
					}

					$query = 'SELECT cihaz_id device_id, sensor_turu_id sensor_type_id, veri data, tarih_saat datetime
							  FROM veriler' . $queue;

					if ($result = $mysqli->query($query)) {
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$row['device_id'] = intval($row['device_id']);
								$row['sensor_type_id'] = intval($row['sensor_type_id']);
								$row['data'] = intval($row['data']);
								$data = array_merge($data, [0 => $row]);
							}

							die(json_encode($data));
						} else {
							$response_message = ['type' => 'info', 'title' => 'Empty request', 'description' => 'There is no data available'];
							die(json_encode($response_message));
						}
					}
				}
			}
		}

	} elseif ($method == 'GET' && isset($_GET['database_number']) && !empty($_GET['database_number'])) {
		$database_name = 'db_' . $_GET['database_number'];

		@$main_mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		@$user_mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, $database_name);

		if (!$main_mysqli->connect_errno && !$user_mysqli->connect_errno) {
			$user_mysqli->query("SET NAMES UTF8");

			$query = 'SELECT a.id, mac_adresi mac_address, tur_id type_id, durum status, konum_adi location_name, konum_aciklamasi location_description
				      FROM cihazlar a
				      INNER JOIN cihaz_konumlari b
				      ON b.id = a.konum_id';

			if ($device_result = $user_mysqli->query($query)) {
				if ($device_result->num_rows > 0) {
					$main_mysqli->query("SET NAMES UTF8");
					$devices = [];

					while ($device_row = $device_result->fetch_assoc()) {
						$type_id = intval($device_row['type_id']);

						$query = "SELECT cihaz_turu_adi, cihaz_turu_aciklamasi
								  FROM cihaz_turleri
								  WHERE id = " . $type_id;

						if ($type_result = $main_mysqli->query($query)) {
							if ($type_result->num_rows > 0) {
								unset($device_row['type_id']);
								$type_row = $type_result->fetch_assoc();

								$type_array = ['type_name' => $type_row['cihaz_turu_adi'], 'type_description' => $type_row['cihaz_turu_aciklamasi']];
								$location_array = array_splice($device_row, 3, 2);

								$device_row = array_splice($device_row, 0, 3);
								$device_row += $type_array + $location_array;
								$device_row['id'] = intval($device_row['id']);
								$device_row['status'] = intval($device_row['status']);

								$devices = array_merge($devices, [0 => $device_row]);
							}
						}
					}

					die(json_encode($devices));
				}
			}
		} else {
			$response_message = ['type' => 'error', 'title' => 'Connection failed', 'description' => 'Unable to connect database'];
			die(json_encode($response_message));
		}
	}

	$response_message = ['type' => 'error', 'title' => 'Request failed', 'description' => 'An error has occurred on request'];
	die(json_encode($response_message));