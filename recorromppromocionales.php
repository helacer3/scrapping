<?php
$directorio = "./mppromocionales/";
$gestor_dir = opendir($directorio);
$cont = 1;
while (false !== ($nombre_fichero = readdir($gestor_dir))) {
    $ficheros[] = $nombre_fichero;
	echo "<br />".$nombre_fichero;
	$cont++;
}
?>