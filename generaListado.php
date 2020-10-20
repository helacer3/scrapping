<?php
error_reporting(E_ALL);
function showFiles($path){
	//arreglo de extensiones permitidas
	$arr_exts=array("jpg","gif","png");
	
	//abrimos el directorio
	$dir = opendir($path);

	$file = fopen("listado_mppromocionales.csv", "w");
	//Mostramos las informaciones
	while ($elemento=readdir($dir))
	{
		$ext=substr($elemento,-3);
		if(($elemento!='.') && ($elemento!='..') && in_array($ext,$arr_exts))
		{
			$nomPartes = explode("_",$elemento);
			//echo $elemento;
			fwrite($file, $nomPartes[1].",".$elemento. PHP_EOL);
		}
	}
	fclose($file);

	//Cerramos el directorio
	closedir($dir);
}
//definimos el path de acceso
$path = "mppromocionales/";
showfiles($path);
echo "<br /> Se genero el archivo con el listado de imágenes";
?>