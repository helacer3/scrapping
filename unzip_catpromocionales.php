<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/***************************************************************************/
function relacionaImagenes($file, $nombre, $contador) {
	($contador == 1) ? $principal = 1 : $principal = 2;
	fwrite($file, $nombre.",".$principal. PHP_EOL);
}
/***************************************************************************/
function unzip($path,$zipfile){
	$zip = new ZipArchive;
	if ($zip->open($path.$zipfile) === TRUE) {
		$zip->extractTo($path."/unzip/");
		$zip->close();
		echo '<br />ok '.$sku;
	} else {
		echo '<br />failed '.$sku;
	}
}
/***************************************************************************/
function getFiles($path){
    $dir = opendir($path);
    $files = array();
    while ($current = readdir($dir)){
        if( $current != "." && $current != "..") {
            echo "<br />  Archivo: ".$path.$current;
			unzip($path,$current);
        }
    }
}
/***************************************************************************/
function recorroDirectorios($file,$path,$sku){
    $dir = opendir($path);
    $files = array();
	$cnt = 2;
    while ($current = readdir($dir)){
        if( $current != "." && $current != "..") {
            if(is_dir($path.$current)) {
                recorroDirectorios($file,$path.$current.'/',$current);
            }
            else {
                rename(
					$path.$current,
					$path.$sku."_".$cnt.".jpg"
				);
				echo "<br />renombró imagen a: ".$sku."_".$cnt.".jpg";
				relacionaImagenes($file, $sku."_".$cnt.".jpg", $cnt);
				$cnt++;
            }
        }
    }
}
/***************************************************************************/

// Descomprime los zips.
// getFiles("catpromocionales/comprimidos/");
// Abro archivo.
$file = fopen("relacion_imagenescat.csv", "a+");
// Renombro archivos.
recorroDirectorios($file,"catpromocionales/comprimidos/unzip/","");
// cierro archivo.
fclose($file);
?>