<?php
	include_once('functions.php');

    // We are starting session
	session_start();

    $err = false;

	if (isset($_POST['username']) && isset($_POST['password'])) {
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		$err = true;

		$response = send_data('POST', 'http://localhost/projects/webservice/controller.php', 'username=' . $username . '&password=' . $password);
        $response = json_decode($response, true);

		if (!isset($response['type'])) {
            $_SESSION = $response;
			header("LOCATION: dashboard.php");
        }
	}

?>

<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/login.css">
<link href="https://fonts.googleapis.com/css?family=Bree+Serif|Pontano+Sans" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<div class="login_container <?php if ($err) echo 'error'; ?>">

    <?php if ($err) echo '<div class="login_error"><span>'.$response['description'].'</span></div>'; ?>
    <div class="login_box">
        <div class="title"><span>Tarım İzleme</span><span>Otomasyonu</span></div>
        <form action="login.php" method="POST">
            <span>Kullanıcı Adı</span>
            <input class="textbox" name="username" type="text" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>"/>
            <span>Şifre</span>
            <input class="textbox" name="password" type="password" <?php if (isset($_POST['username'])) echo 'autofocus'; ?>/>
            <button class="button"><span>Giriş</span></button>
        </form>
    </div>
</div>

