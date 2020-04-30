<?php
require_once('FPDI/src/autoload.php');
require_once('fpdf/fpdf.php');

use setasign\Fpdi\Fpdi;

define(CHECK_TIMER, 60); // время в минутах
define(DEFAULT_ANALIZES_PATH, 'E:/OSPanel/domains/pdf.creator/Анализы1с/'); // путь к файлам

//
//$start = microtime(true);
//echo $dir = 'E:/OSPanel/domains/pdf.creator/Анализы1с/';
//echo '<br>';
//$i=0;
//if ($handle = opendir($dir)) {
//    while (false !== ($file = readdir($handle))) {
//        if ($file != "." && $file != "..") {
//
////            echo $file . ' ' . date("F d Y H:i:s", filectime($dir . $file))." <br>";
//            $arr[]=['filename'=>$file,'data'=>date("F d Y H:i:s", filectime($dir . $file))];
//            $i++;
//        }
//    }
//} else {
//    echo 'Ошибка открытия директории';
//}
//echo "<br>";
//echo "<br>";
//
//foreach ($arr as $key){
//    if ($key['data']=='April 22 2020 21:32:25'){
//        echo "Существует такая папка! Называеться: ".$key['filename'];
//    }
//}
////var_dump($arr[0]['data']);
////echo $i;
//$test=date('F d Y H:i:s')-time(600);
//echo '<br>'.$test;
//echo '<br>Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.';


function makescandir($dir) //проход по папкам. Возвращает массив папок с датой послед изменений + название
{
    echo $dir;
    echo '<br>';
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {

                $arr[] = ['filename' => $file, 'data' => date("F d Y H:i:s", filemtime($dir . $file))];

            }
        }
    } else {
        echo 'Ошибка открытия директории';
    }

//    var_dump($arr);
    return $arr;
}

//function makescandirin2($dir)
//{
//    echo $dir;
//    echo '<br>';
//    if ($handle = opendir($dir)) {
//        while (false !== ($file = readdir($handle))) {
//            if ($file != "." && $file != "..") {
//
//                $arr[] = ['filename' => $file, 'data' => date("F d Y H:i:s", filemtime($dir . $file))];
//
//
//                }
//
//            }
//        return $arr;
//
//
//
//    } else {
//        echo 'Ошибка открытия директории';
//    }
//}
function setTimer($minute) //преобразует минуты в сек
{
    $timer = time() - $minute * 60;
    return $timer;
}

function isPdf($dir) //проверка является ли файл pdf-ом
{
    if (pathinfo($dir, PATHINFO_EXTENSION) === 'pdf') {
        return true;
    } else {
        return false;
    }

}

function addHeaderToPdf($filename, $headerimage)  // добавляем нашу шапку в pdf файл
{
    $pdf = new Fpdi();
// add a page
    $pdf->AddPage();
// set the source file
    $pdf->setSourceFile($filename);
// import page 1
    $tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx, 0, 0, 200);

// now write some text above the imported page
    $pdf->Image($headerimage, 0, -5, -300);
    $pdf->Output($filename, 'F');
}


//echo pathinfo('E:/OSPanel/domains/pdf.creator/Анализы1с/Новая папка/Новый текстовый документ.txt', PATHINFO_EXTENSION );

//echo "в последний раз файл был изменен: " . date ("F d Y H:i:s.", filemtime('E:/OSPanel/domains/pdf.creator/Анализы1с/test'));

//addHeaderToPdf('E:/OSPanel/domains/pdf.creator/Анализы1с/Новая папка/1.pdf', 'logo.jpg');
//var_dump(makescandirin2(DEFAULT_ANALIZES_PATH));
function thisDirsNeedChange()
{

    foreach (makescandir(DEFAULT_ANALIZES_PATH) as $key) {
        if (strtotime($key['data']) > setTimer(CHECK_TIMER)) {
            $needChange[] = DEFAULT_ANALIZES_PATH . $key['filename'] . '/';
        }

    }
    return $needChange;
}

function countOfNeedChange($arr)
{
    return count($arr);
}

function thisFilesNeedChange($arr)
{
    for ($i = 0; $i < countOfNeedChange(thisDirsNeedChange()); $i++) {
        if ($arr != NULL) {
            foreach (makescandir($arr[$i]) as $key) {
                if (strtotime($key['data']) > setTimer(CHECK_TIMER) && isPdf($key['filename'])===true) {
                    $needChange[] = $key['filename'];

                }


            }

        } else {
            echo "net papok";
        }
    }
    return $needChange;
}


//echo countOfNeedChange(thisDirsNeedChange());
var_dump(thisFilesNeedChange(thisDirsNeedChange()));
//var_dump(thisDirsNeedChange());
