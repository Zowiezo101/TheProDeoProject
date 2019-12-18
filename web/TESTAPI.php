
<?php

$URL = "https://prodeo.000webhostapp.com/web/api/database_api.php/?table=locations&id=-1";
$Handle = curl_init($URL);

curl_setopt($Handle, CURLOPT_HEADER, 0);
curl_setopt($Handle, CURLOPT_RETURNTRANSFER , 1);
curl_setopt($Handle, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($Handle);
curl_close($Handle);

echo $result;
?>
