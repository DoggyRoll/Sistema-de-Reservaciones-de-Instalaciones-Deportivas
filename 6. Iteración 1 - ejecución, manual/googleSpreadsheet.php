<?php
#################################################
//Librerias
#################################################
require __DIR__ . '/vendor/autoload.php';

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

#################################################
//Definir conexion con googlesheets
#################################################
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/client_secret.json');
$client = new Google_Client;
$client->useApplicationDefaultCredentials();

$client->setApplicationName("SRIDTEC");
$client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);

if ($client->isAccessTokenExpired()) {
    $client->refreshTokenWithAssertion();
}

$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
ServiceRequestFactory::setInstance(
    new DefaultServiceRequest($accessToken)
);

#################################################
//Definir el archivo
#################################################
$spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
   ->getSpreadsheetFeed()
   ->getByTitle('Instalaciones 2019');

$worksheets = $spreadsheet->getWorksheetFeed();

#################################################
//Funciones para el manejo del googlesheet
#################################################
function consultarCelda($fila, $columna, $pagina){
	$worksheet = $GLOBALS['worksheets']->getByTitle($pagina);
	$cellFeed = $worksheet->getCellFeed();
	$celda = $cellFeed->getCell($fila, $columna);
	return $celda->getcontent();
}

function insertarCelda($fila, $columna, $pagina, $dato){
	$worksheet = $GLOBALS['worksheets']->getByTitle($pagina);
	$cellFeed = $worksheet->getCellFeed();
	$cellFeed->editCell($fila, $columna, $dato);
}

function borrarCelda($fila, $columna, $pagina){
	$worksheet = $GLOBALS['worksheets']->getByTitle($pagina);
	$cellFeed = $worksheet->getCellFeed();
	$celda = $cellFeed->getCell($fila, $columna);
	$celda->update(null);
}

function actualizarCelda($fila, $columna, $pagina, $dato){
	$worksheet = $GLOBALS['worksheets']->getByTitle($pagina);
	$cellFeed = $worksheet->getCellFeed();
	$celda = $cellFeed->getCell($fila, $columna);
	$celda->update($dato);
}


function reservar($horaInicio, $horaFin, $actividad, $pagina){
	$worksheet = $GLOBALS['worksheets']->getByTitle($pagina);
	$cellFeed = $worksheet->getCellFeed();
	$fila = 4;
	$columna = 3;
	while ($horaInicio!=consultarCelda(1, $columna, $pagina)){
		$columna++;
	}
	while ($horaFin!=consultarCelda(1, $columna, $pagina)){
		$cellFeed->editCell($fila, $columna, $actividad);
		$columna++;
	}
}


//reservar("7:30am", "10:00am", "Futbol", "Cancha Sintética");


?>