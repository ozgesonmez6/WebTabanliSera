<?php

include_once('connect.php');
if(isset($_GET['sensor']))
{
	$data=$_GET['sensor'];
	$tarih="12.12.12";

$stmt = $conn->prepare("INSERT INTO cihaz_verileri (data, tarih_saat) 
    VALUES (:data, :tarih)");
 $stmt->bindParam(':data', $data);
    $stmt->bindParam(':tarih', $tarih);
    $stmt->execute();
	$databaseErrors = $stmt->errorInfo();

if( !empty($databaseErrors) ){  
    $errorInfo = print_r($databaseErrors, true); # true flag returns val rather than print
    $errorLogMsg = "error info: $errorInfo"; # do what you wish with this var, write to log file etc...         
} else {
    print_r("kayıt basarili.");
}
$conn = null;
	
}




if(isset($_POST['data']))
{
		$data=$_POST['data'];
	$tarih="12.12.12";

$stmt = $conn->prepare("INSERT INTO cihaz_verileri (data, tarih_saat) 
    VALUES (:data, :tarih)");
 $stmt->bindParam(':data', $data);
    $stmt->bindParam(':tarih', $tarih);
    $stmt->execute();

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn = null;
}

?>