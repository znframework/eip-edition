<?php
class __USE_STATIC_ACCESS__Date implements DateTimeCommonInterface
{
	//----------------------------------------------------------------------------------------------------
	// DATE CLASS
	//----------------------------------------------------------------------------------------------------
	//
	// Yazar: Ozan UYKUN <ozanbote@windowslive.com> | <ozanbote@gmail.com>
	// Site: www.zntr.net
	// Lisans: The MIT License
	// Telif Hakkı: Copyright (c) 2012-2016, zntr.net
	//
	// Sınıf Adı: Date
	// Versiyon: 2.0
	// Tanımlanma: Statik
	// Dahil Edilme: Gerektirmez
	// Erişim: Date::, $this->Date, zn::$use->Date, uselib('Date'), new Date
	// Not: Büyük-küçük harf duyarlılığı yoktur.
	//
	//----------------------------------------------------------------------------------------------------
	
	//----------------------------------------------------------------------------------------------------
	// Common
	//----------------------------------------------------------------------------------------------------
	// 
	// $config
	//
	// __construct()
	//
	//----------------------------------------------------------------------------------------------------
	use DateTimeTrait;
	
	//----------------------------------------------------------------------------------------------------
	// Current
	//----------------------------------------------------------------------------------------------------
	// 
	// Aktif saat bilgisini verir.
	//
	// @param  string clock
	// @return string
	//
	//----------------------------------------------------------------------------------------------------
	public function current($clock = NULL)
	{		
		return date("d.m.o");
	}
	
	//----------------------------------------------------------------------------------------------------
	// Calculate
	//----------------------------------------------------------------------------------------------------
	// 
	// Tarihler arasında hesaplama yapmak için kullanılır.
	//
	// @param  string input
	// @param  string calculate
	// @param  string output
	// @return mixed
	//
	//----------------------------------------------------------------------------------------------------
	public function calculate($input = '', $calculate = '', $output = 'Y-m-d', $charType = 'setDateFormatChars')
	{	
		if( preg_match('/^[0-9]/', $input) )
		{
			return date($output, strtotime($calculate, strtotime($input)));
		}
		else
		{	
			$input  = $this->_convert($charType, $input);
			$output = $this->_convert($charType, $output);
		
			return date($output, strtotime($calculate, strtotime(date($input))));
		}
	}

	//----------------------------------------------------------------------------------------------------
	// Convert
	//----------------------------------------------------------------------------------------------------
	// 
	// Tarih bilgisini dönüştürmek için kullanılır.
	//
	// @param  string $date
	// @param  string $format
	// @return string 
	//
	//----------------------------------------------------------------------------------------------------
	public function convert($date = '', $format = 'd-m-Y H:i:s')
	{
		return date($this->_convert('setDateFormatChars', $format), strtotime($date));	
	}

	//----------------------------------------------------------------------------------------------------
	// Standart
	//----------------------------------------------------------------------------------------------------
	// 
	// Standart tarih ve saat bilgisi üretir.
	//
	// @param  void
	// @return string
	//
	//----------------------------------------------------------------------------------------------------
	public function standart()
	{		
		return date("d.F.o l, H:i:s");
	}

	//----------------------------------------------------------------------------------------------------
	// Set
	//----------------------------------------------------------------------------------------------------
	// 
	// Tarih ve saat ayarlamaları yapmak için kullanılır.	
	//
	// @param  string exp
	// @return string
	//
	//----------------------------------------------------------------------------------------------------
	public function set($exp = 'H:i:s')
	{
		if( ! is_string($exp) ) 
		{
			return Error::set('Error', 'stringParameter', 'exp');
		}
		
		return date($this->_convert('setDateFormatChars', $exp));
	}
}