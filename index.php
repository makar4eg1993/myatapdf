<?php
$start = microtime(true);
require_once('FPDI/src/autoload.php');
require_once('fpdf/fpdf.php');
use setasign\Fpdi\Fpdi;

$pdf = new Fpdi();
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile('invitro.pdf');
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx, 0, 0, 200);

// now write some text above the imported page
$pdf->Image('logo.jpg',0,-5,-300);
$pdf->Output('invitro.pdf','F');
//$pdf->Output();
echo 'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.';