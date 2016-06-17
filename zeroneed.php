<?php
//----------------------------------------------------------------------------------------------------
// ZERONEED PHP WEB FRAMEWORK 
//----------------------------------------------------------------------------------------------------
//
// Author     : Ozan UYKUN <ozanbote@windowslive.com> | <ozanbote@gmail.com>
// Site       : www.znframework.com
// License    : The MIT License
// Copyright  : Copyright (c) 2012-2016, ZN Framework
//
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
//  Uygulama Ayarları
//----------------------------------------------------------------------------------------------------
require_once 'application.php';
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
//  Global Application Variable
//----------------------------------------------------------------------------------------------------
global $application;
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
//  Directory Index
//----------------------------------------------------------------------------------------------------
define('DIRECTORY_INDEX', $application['directoryIndex']);
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
// REAL_BASE_DIR
//----------------------------------------------------------------------------------------------------
define('REAL_BASE_DIR', realpath(__DIR__).DIRECTORY_SEPARATOR);

//----------------------------------------------------------------------------------------------------
//  Uygulama Türü
//----------------------------------------------------------------------------------------------------
define('APPMODE', strtolower($application['mode']));
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
// Kullanılabilir Uygulama Seçenekleri
//----------------------------------------------------------------------------------------------------
switch( APPMODE )
{ 
	//------------------------------------------------------------------------------------------------
	// Publication Yayın Modu
	// Tüm hatalar kapalıdır.
	// Projenin tamamlanmasından sonra bu modun kullanılması önerilir.
	//------------------------------------------------------------------------------------------------
	case 'publication' :
		error_reporting(0); 
	break;
	//------------------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------------------
	// Restoration Onarım Modu
	// Hataların görünümü görecelidir.
	//------------------------------------------------------------------------------------------------
	case 'restoration' :
	//------------------------------------------------------------------------------------------------
	// Development Geliştirme Modu
	// Tüm hatalar açıktır.
	//------------------------------------------------------------------------------------------------
	case 'development' : 
		error_reporting(-1);
	break; 
	//------------------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------------------
	// Farklı bir kullanım hatası
	//------------------------------------------------------------------------------------------------
	default: echo 'Invalid Application Mode! Available Options: development, restoration or publication'; exit;
	//------------------------------------------------------------------------------------------------
}	
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
//  Ön Yüklenenler
//----------------------------------------------------------------------------------------------------
require_once 'System/Core/Preloading.php';
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
//  Uygulama Dizini
//----------------------------------------------------------------------------------------------------
$appdir = $application['directory'];

if( is_array($appdir) )
{
	if( empty($appdir[host()]) )
	{
		echo 'Invalid Application Directory Name or Hostname! Check application.php file located in the directory settings.'; exit;
	}
	
	define('APPDIR', suffix($appdir[host()]));
}
else
{
	define('APPDIR', suffix($appdir));
}

//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
//  Restorasyon Dizini
//----------------------------------------------------------------------------------------------------
$resdir = $application['restoration']['directory'];

if( is_array($resdir) )
{
	if( empty($resdir[host()]) )
	{
		echo 'Invalid Restoration Directory Name or Hostname! Check application.php file located in the Restoration:directory settings.'; exit;
	}
	
	define('APPDIR', suffix($resdir[host()]));
}
else
{
	define('RESDIR', suffix($resdir));
}
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------
// Benchmarking Test
//----------------------------------------------------------------------------------------------------
$benchmark = $application['benchmark'];	
//----------------------------------------------------------------------------------------------------

if( $benchmark === true ) 
{
	//------------------------------------------------------------------------------------------------
	//  Sisteminin Açılış Zamanını Hesaplamayı Başlat
	//------------------------------------------------------------------------------------------------
	$start = microtime();
	//------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------
//  Sistem Hiyerarşisi -- System/Core/Hierarchy.php
//----------------------------------------------------------------------------------------------------
require_once HIERARCHY_DIR; 
//----------------------------------------------------------------------------------------------------

if( $benchmark === true )
{	
	//------------------------------------------------------------------------------------------------
	//  Sistemin Açılış Zamanını Hesaplamayı Bitir
	//------------------------------------------------------------------------------------------------
	$finish         = microtime();
	//------------------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------------------
	//  System Elapsed Time Calculating
	//------------------------------------------------------------------------------------------------
	$elapsedTime    = $finish - $start;
	//------------------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------------------
	//  Sistemin Bellek Kullanımını Hesapla
	//------------------------------------------------------------------------------------------------
	$memoryUsage    = memory_get_usage();
	//------------------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------------------
	//  Sistemin Maksimum Bellek Kullanımını Hesapla
	//------------------------------------------------------------------------------------------------
	$maxMemoryUsage = memory_get_peak_usage();
	//------------------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------------------
	//  Benchmark Performans Sonuç Tablosu
	//------------------------------------------------------------------------------------------------
	$benchmarkData  = array
	(
		'elapsedTime'	 => $elapsedTime,
		'memoryUsage'	 => $memoryUsage,
		'maxMemoryUsage' => $maxMemoryUsage
	);	
	
	$benchResult    = Import::template('BenchmarkTable', $benchmarkData, true);
	//------------------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------------------
	//  Benchmark Performans Sonuç Tablosu Yazdırılıyor
	//------------------------------------------------------------------------------------------------
	echo $benchResult;
	//------------------------------------------------------------------------------------------------
			
	//------------------------------------------------------------------------------------------------
	//  Sistem benchmark performans test sonuçlarını raporla.
	//------------------------------------------------------------------------------------------------
	report('Benchmarking Test Result', $benchResult, 'BenchmarkTestResults');
	//------------------------------------------------------------------------------------------------
}