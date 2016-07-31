<?php
namespace ZN\Caching;

interface CacheInterface
{
	//----------------------------------------------------------------------------------------------------
	//
	// Yazar      : Ozan UYKUN <ozanbote@windowslive.com> | <ozanbote@gmail.com>
	// Site       : www.zntr.net
	// Lisans     : The MIT License
	// Telif Hakkı: Copyright (c) 2012-2016, zntr.net
	//
	//----------------------------------------------------------------------------------------------------
	
	//----------------------------------------------------------------------------------------------------
	// Select
	//----------------------------------------------------------------------------------------------------
	// 
	// @param  string $key
	// @return mixed
	//
	//----------------------------------------------------------------------------------------------------
	public function select(String $key);
	
	//----------------------------------------------------------------------------------------------------
	// Insert
	//----------------------------------------------------------------------------------------------------
	// 
	// @param  string $key
	// @param  variable $var
	// @param  numeric $time
	// @param  mixed $expressed
	// @return bool
	//
	//----------------------------------------------------------------------------------------------------
	public function insert(String $key, $var, $time, $expressed);
		
	//----------------------------------------------------------------------------------------------------
	// Delete
	//----------------------------------------------------------------------------------------------------
	// 
	// @param  string $key
	// @return mixed
	//
	//----------------------------------------------------------------------------------------------------
	public function delete(String $key);
	
	//----------------------------------------------------------------------------------------------------
	// Increment
	//----------------------------------------------------------------------------------------------------
	// 
	// @param  string  $key
	// @param  numeric $increment
	// @return void
	//
	//----------------------------------------------------------------------------------------------------
	public function increment(String $key, $increment);
	
	//----------------------------------------------------------------------------------------------------
	// Deccrement
	//----------------------------------------------------------------------------------------------------
	// 
	// @param  string  $key
	// @param  numeric $decrement
	// @return void
	//
	//----------------------------------------------------------------------------------------------------
	public function decrement(String $key, $decrement);
	
	//----------------------------------------------------------------------------------------------------
	// Clean
	//----------------------------------------------------------------------------------------------------
	// 
	// @param  void
	// @return void
	//
	//----------------------------------------------------------------------------------------------------
	public function clean();
	
	//----------------------------------------------------------------------------------------------------
	// Info
	//----------------------------------------------------------------------------------------------------
	// 
	// @param  mixed  $info
	// @return mixed
	//
	//----------------------------------------------------------------------------------------------------
	public function info($info);
	
	//----------------------------------------------------------------------------------------------------
	// Get Meta Data
	//----------------------------------------------------------------------------------------------------
	// 
	// @param  string  $key
	// @return mixed
	//
	//----------------------------------------------------------------------------------------------------
	public function getMetaData(String $key);
	
	//----------------------------------------------------------------------------------------------------
	// Is Supported
	//----------------------------------------------------------------------------------------------------
	// 
	// @param  void
	// @return bool
	//
	//----------------------------------------------------------------------------------------------------
	public function isSupported();
}