<?php
	include_once('connect.php');

    // We are starting session
	session_start();

    $err = false;

	if (isset($_POST['username']) && isset($_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$err = true;
		$message;
		print_r("giris okasd");
		try
       {
// WHERE kullanici_adi='{$username}' AND sifre='{$password}' LIMIT 1        
		$stmt = $conn->prepare("SELECT * FROM kullanicilar where kullanici_adi=:kulad and sifre=:sifre");
		$stmt->bindParam(':kulad', $username);
		$stmt->bindParam(':sifre', $password);
        $stmt->execute();
		$total = $stmt->rowCount();
        if($total>0)
		{
			header('Location: http://localhost/webservice/dashboard.php');
			
		}
		else
		{
			$message="Giriş Başarısız!";
		}
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
	}

?>

<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/login.css">
<link href="https://fonts.googleapis.com/css?family=Bree+Serif|Pontano+Sans" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<div class="login_container <?php if ($err) echo 'error'; ?>">

    <?php if ($err) echo '<div class="login_error"><span>'.$message.'</span></div>'; ?>
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

