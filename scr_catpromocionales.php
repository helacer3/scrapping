<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);

/***************************************************************************/
function getConexion() {
	// Conexión con los datos del 'config.ini' 
	$connection = mysqli_connect('localhost','root','','imagenesdaniel'); 
	// Si la conexión falla, aparece el error 
	if($connection === false) 
		return null;
	else
		return $connection;
}
/***************************************************************************/
function insertar($con,$sku,$codigo) {
	$sql = "INSERT INTO codigos_catpromocionales (sku,codigo)
		VALUES ('".$sku."',".(int)$codigo.")";
	$con->query($sql);
}
/***************************************************************************/
$con = getConexion();
for ($i=7637;$i<=10000;$i++) {
	$html  = file_get_contents("http://www.catalogospromocionales.com/catalogo/producto/".$i."/71");
	$dom = new DOMDocument();
	$classname="prodRef";

	$dom->loadHTML($html);
	$xpath  = new DOMXPath($dom);
	$results = $xpath->query("//*[@class and contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

	if ($results->length > 0) {
		$sku = $results->item(0)->nodeValue;
		insertar($con,$sku,$i);
	}
}
$con->close();
?>