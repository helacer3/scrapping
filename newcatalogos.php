<?php
libxml_use_internal_errors(true);

function defineProductReference($idProduct = 0) {
	// create Empty Var
	$prdReference = "";
	// load Html
	$html  = file_get_contents("http://www.catalogospromocionales.com/catalogo/producto/{$idProduct}/71");
	$dom   = new DOMDocument('1.0', 'utf-8');
	$dom->loadHTML($html);
	$className ="prodRef";
	$finder   = new DomXPath($dom);
	$spaner   = $finder->query("//*[contains(@class, '$className')]");
	if($spaner->length > 0) {
	  	$node = $spaner->item(0);
	  	if (is_object($node)) {
			$prdReference  = $node->nodeValue;
		}
	}
	// default Return
	return $prdReference;
}

/**
* validate Url Image
*/
function validateUrlImage( $url = NULL ) {
	// default Var
	$booValidate = false;
	try {
	    if( empty( $url ) ){
	        return false;
	    }

	    stream_context_set_default(
	        array(
	            'http' => array(
	                'method' => 'HEAD'
	             )
	        )
	    );

	    $headers = @get_headers( $url );
	    sscanf( $headers[0], 'HTTP/%*d.%*d %d', $httpcode );

	    // Aceptar solo respuesta 200 (Ok), 301 (redirección permanente) o 302 (redirección temporal)
	    $accepted_response = array( 200, 301, 302 );
	    if( in_array( $httpcode, $accepted_response ) ) {
	        $booValidate = true;
	    } 
    } catch (\Exception $ex) {

    }
    // default Return
    return $booValidate;
}


$arrImages    = array();
$prdReference = defineProductReference(7356);
// product Reference Validate
if ($prdReference != "") {
	// oterate Images
	for ($i=0; $i<5; $i++) {
		$urlImage = "https://catalogospromocionales.com/images/galeria/{$prdReference}/{$prdReference}-{$i}.jpg?V=3.jpg";
		// validate Url
		if (validateUrlImage($urlImage)) {
			// add To Array Image
			array_push($arrImages, $urlImage);
		}
	}
	//echo "<pre>";print_r($arrImages);echo "</pre>";

	/*echo "<br />La referencia es: ".$prdReference;
	echo "<br />La imagen principal es: <a href='https://catalogospromocionales.com/images/productos/7356.jpg'>Imagen0</a>";
	echo "<br />La imagen secundaria 1 es: <a href='https://catalogospromocionales.com/images/galeria/{$prdReference}/{$prdReference}-1.jpg?V=3.jpg'>Imagen1</a>";
	echo "<br />La imagen secundaria 2 es: <a href='https://catalogospromocionales.com/images/galeria/{$prdReference}/{$prdReference}-2.jpg?V=3.jpg'>Imagen2</a>";
	echo "<br />La imagen secundaria 3 es: <a href='https://catalogospromocionales.com/images/galeria/{$prdReference}/{$prdReference}-3.jpg?V=3.jpg'>Imagen3</a>";
	echo "<br />La imagen secundaria 4 es: <a href='https://catalogospromocionales.com/images/galeria/{$prdReference}/{$prdReference}-4.jpg?V=3.jpg'>Imagen4</a>";
	echo "<br />La imagen secundaria 5 es: <a href='https://catalogospromocionales.com/images/galeria/{$prdReference}/{$prdReference}-5.jpg?V=3.jpg'>Imagen5</a>";*/
}




?>