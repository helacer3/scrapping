<?php
	/***************************************************************************/
	function determinaCantidad($cnt,$sku,$skuanterior) {
		return ($sku == $skuanterior) ? $cnt : 1;
	}
	/***************************************************************************/
	function relacionaImagenes($file, $nombre, $contador) {
		($contador == 1) ? $principal = 1 : $principal = 2;
		fwrite($file, $nombre.",".$principal. PHP_EOL);
	}
	/***************************************************************************/
	function url_exists($url)
	{
		$fp=@fopen($url,"r");//Utilizamos fopen para abrir esa url 
		if($fp){//Si fopen abre la url 
			return true; 
		}else{//si no devuelve false 
			return false; 
		} 
		@fclose($fp);//Cerramos la conexion 
	}
	/***************************************************************************/
	function recibe_imagen ($url_origen,$archivo_destino) {  
		$mi_curl = curl_init ($url_origen);  
		$fs_archivo = fopen ($archivo_destino, "w");  
		curl_setopt ($mi_curl, CURLOPT_FILE, $fs_archivo);  
		curl_setopt ($mi_curl, CURLOPT_HEADER, 0);  
		curl_exec ($mi_curl);  
		curl_close ($mi_curl);  
		fclose ($fs_archivo);  
	}
	/***************************************************************************/
	// Conexión con los datos del 'config.ini' 
	$connection = mysqli_connect('localhost','root','','imagenesdaniel'); 

	// Si la conexión falla, aparece el error 
	if($connection === false) { 
		echo 'Ha habido un error <br>'.mysqli_connect_error(); 
	} else {
		echo 'Conectado a la base de datos <br />';
	}
	/***************************************************************************/
	// genero archivo.
	$file = fopen("relacion_imagenes.csv", "w");
	// variables.
	$cnt = 1;
	$skuanterior = "";
	$url = "http://www.mppromocionales.com/images/grandes/";
	$arrLetras = array(2=>'',3=>'a',4=>'b',5=>'c',6=>'d');
	$sql = "SELECT sku, codigo_extrano FROM skus_imagenes";
	if ($result = $connection->query($sql) ){
		if ($result->num_rows > 0 ){
			while($row = $result->fetch_assoc() ){
				$cnt = determinaCantidad($cnt,$row['sku'],$skuanterior);
				$skuanterior = $row['sku'];
				$urlImagen = $url.$row['sku'].".jpg";
				//echo "<br /><b>".$row['sku']. " - ". trim($row['codigo_extrano']).": </b><br />";
				echo "<br />".$urlImagen;
				// valido si existe.
				if (url_exists($urlImagen)) {
					echo "<br />entra 1";
					recibe_imagen (
						$urlImagen,
						"./mppromocionales/".$row['sku']."_".$cnt.".jpg"
					);
					// relaciono archivo.
					relacionaImagenes($file, $row['sku']."_".$cnt.".jpg", $cnt);
					// incremento contador.
					$cnt++;
				}
				// descargo las imágenes adicionales
				foreach ($arrLetras as $letras) {
					$urlImagen = $url.$row['codigo_extrano'].$letras.".jpg";
					//echo "<br />".$urlImagen;
					if (url_exists($urlImagen)) {
						echo "<br />entra 2 :".$urlImagen;
						recibe_imagen (
							$urlImagen,
							"./mppromocionales/".$row['sku']."_".$cnt.".jpg"
						);
						// relaciono archivo.
						relacionaImagenes($file, $row['sku']."_".$cnt.".jpg", 2);
						// incremento contador.
						$cnt++;
					}
				}
				//exit;
			}
			$result->close();
		} else {
			echo "NO se encontró ningún registro que coincida con su busqueda.";
		}
	} else {
		echo "Error: No fue posible ejecutar la consulta $sql ". $connection->error;
	}
	$connection->close();
	// cierro archivo.
	fclose($file);
	/***************************************************************************/
?>