<?php
//--------------------------------------------------------------------------------------------------
// Low Level
//--------------------------------------------------------------------------------------------------
//
// Author     : Ozan UYKUN <ozanbote@windowslive.com> | <ozanbote@gmail.com>
// Site       : www.znframework.com
// License    : The MIT License
// Copyright  : Copyright (c) 2012-2016, ZN Framework
//
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// Structure Data
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan kontrolcü dosyasının yol bilgisi.
//
//--------------------------------------------------------------------------------------------------
$datas        = ZN\Core\Structure::data();

$parameters   = $datas['parameters'];
$page         = $datas['page'];
$isFile       = $datas['file'];
$function     = $datas['function'];
$openFunction = $datas['openFunction'];
$namespace    = $datas['namespace'];

//--------------------------------------------------------------------------------------------------
// CURRENT_OPEN_PAGE
//--------------------------------------------------------------------------------------------------
//
// CURRENT_OPEN_PAGE
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_COPEN_PAGE', $openFunction);

//--------------------------------------------------------------------------------------------------
// CURRENT_CPARAMETERS
//--------------------------------------------------------------------------------------------------
//
// CURRENT_CPARAMETERS
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CPARAMETERS', $parameters);

//--------------------------------------------------------------------------------------------------
// CURRENT_CFILE
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan kontrolcü dosyasının yol bilgisi.
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CFILE', $isFile);

//--------------------------------------------------------------------------------------------------
// CURRENT_CFUNCTION
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan sayfaya ait fonksiyon bilgisi.
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CFUNCTION', $function);

//--------------------------------------------------------------------------------------------------
// CURRENT_CPAGE
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan sayfaya ait kontrolcü dosyasının ad bilgisini.
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CPAGE', $page . '.php');

//--------------------------------------------------------------------------------------------------
// CURRENT_CONTROLLER
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan sayfaya ait kontrolcü bilgisi.
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CONTROLLER', $page);

//--------------------------------------------------------------------------------------------------
// CURRENT_CNAMESPACE
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan sayfaya ait namespace bilgisi.
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CNAMESPACE', $namespace);

//--------------------------------------------------------------------------------------------------
// CURRENT_CNAMESPACE
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan sayfaya ait namespace bilgisi.
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CCLASS', $namespace . CURRENT_CONTROLLER);

//--------------------------------------------------------------------------------------------------
// CURRENT_CPATH
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan sayfaya ait kontrolcü ve fonksiyon yolu   bilgisi.
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CFPATH', str_replace(CONTROLLERS_DIR, '', CURRENT_CONTROLLER).'/'.CURRENT_CFUNCTION);

//--------------------------------------------------------------------------------------------------
// CURRENT_CFURI
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan sayfaya ait kontrolcü ve fonksiyon yolu   bilgisi.
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CFURI', strtolower(CURRENT_CFPATH));

//--------------------------------------------------------------------------------------------------
// CURRENT_CPATH
//--------------------------------------------------------------------------------------------------
//
// @return Aktif çalıştırılan sayfaya ait kontrolcü ve fonksiyon URL yol bilgisi.
//
//--------------------------------------------------------------------------------------------------
define('CURRENT_CFURL', siteUrl(CURRENT_CFPATH));

//--------------------------------------------------------------------------------------------------
// Usable Project Modes
//--------------------------------------------------------------------------------------------------
$appcon = Config::get('Project');
//--------------------------------------------------------------------------------------------------

if( empty($appcon) )
{
    trace('["Container"] Not Found! Check the [\'containers\'] setting in the [Projects/Projects.php] file.');
}

//--------------------------------------------------------------------------------------------------
// Define Project Mode
//--------------------------------------------------------------------------------------------------
define('PROJECT_MODE', strtolower($appcon['mode']));
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// Select Project Mode
//--------------------------------------------------------------------------------------------------
internalProjectMode(PROJECT_MODE, $appcon['errorReporting']);
//--------------------------------------------------------------------------------------------------
