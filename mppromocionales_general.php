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
	$antProducto = "";
	$url         = "http://www.mppromocionales.com/detallesvar.php?idprod=";
	$urlImagen   = "http://www.mppromocionales.com/";
	//25540-32163
	for($id=32163;$id<33000;$id++) {
		echo "<br />Producto: <b>".$id."</b>";
		$product  = new DOMDocument;
		@$product->loadHTMLFile($url.$id);
		$xpath    = new DOMXPath($product);
		$imgs     = $xpath->query('//img');
		$longdesc = "";
		$contador = 0;
		echo "<br /> Enlace producto: ".$id;
		foreach($imgs as $cod => $image){
			$source = $image->getAttribute("src");
			if(strpos($source,'images/grandes') !== false) {
				if ($contador <= 10) {
					if($longdesc == "") 
						$longdesc = $image->getAttribute("longdesc");
					// valida producto Repetido.
					echo "<br /> compara ".$antProducto ." con ".$longdesc;
					if($contador == 0 && $antProducto == $longdesc) {
						break;
					}
					// genero nombre
					$nombre      = $longdesc."_".$id."_".$contador.".jpg";
					$antProducto = $longdesc;
					// mppromocionales
					recibe_imagen($urlImagen.$source,"./mppromocionales/".$nombre);
					echo "<br /> Imagen: ".$longdesc.": ".$contador." - ".$source;
				}
				// incremento contador.
				$contador++;
			}
		}
	}
	/***************************************************************************/
?>