<?php
class __USE_STATIC_ACCESS__Compress
{
	/***********************************************************************************/
	/* COMPRESS DRIVER						                   	                       */
	/***********************************************************************************/
	/* Yazar: Ozan UYKUN <ozanbote@windowslive.com> | <ozanbote@gmail.com>
	/* Site: www.zntr.net
	/* Lisans: The MIT License
	/* Telif Hakkı: Copyright (c) 2012-2015, zntr.net
	/*
	/* Sınıf Adı: Compress
	/* Sınıf Versiyon: PHP 4, PHP 5
	/* ZN Versiyon: 2.0 Eylül Güncellemesi
	/* Tanımlanma: Mixed
	/* Dahil Edilme: Gerektirmez
	/* Erişim: Compress::, $this->Compress, zn::$use->Compress, uselib('Compress')
	/* Not: Büyük-küçük harf duyarlılığı yoktur.
	/***********************************************************************************/
	
	/* Compress Değişkeni
	 *  
	 * Compress sürücüsünü
	 * tutmak için oluşturulmuştur.
	 *
	 */ 
	protected $compress;
	
	/******************************************************************************************
	* CONSTRUCT                                                                               *
	*******************************************************************************************
	| Genel Kullanım: Nesne tanımlaması ve sıkıştırma ayarları çalıştırılıyor.				  |
	|          																				  |
	******************************************************************************************/
	public function __construct($driver = '')
	{	
		$this->compress = Driver::run('Compress', $driver);
	}
	
	/******************************************************************************************
	* CALL                                                                                    *
	*******************************************************************************************
	| Genel Kullanım: Geçersiz fonksiyon girildiğinde çağrılması için.						  |
	|          																				  |
	******************************************************************************************/
	public function __call($method = '', $param = '')
	{	
		die(getErrorMessage('Error', 'undefinedFunction', "Compress::$method()"));		
	}
	
	/******************************************************************************************
	* EXTRACT 		                                                                          *
	*******************************************************************************************
	| Genel Kullanım: Sıkıştırılmış dosyaları çıkartır.								     	  |
	|          																				  |
	******************************************************************************************/
	public function extract($source = '', $target = '')
	{
		return $this->compress->extract($source, $target);
	}
	
	/******************************************************************************************
	* WRITE   		                                                                          *
	*******************************************************************************************
	| Genel Kullanım: Veriyi sıkıştırarak dosyaya yazar.							     	  |
	|          																				  |
	******************************************************************************************/
	public function write($file = '', $data = '', $mode = 'w')
	{
		return $this->compress->write($file, $data, $mode);
	}
	
	/******************************************************************************************
	* SIMPLE WRITE                                                                            *
	*******************************************************************************************
	| Genel Kullanım: Veriyi sıkıştırarak dosyaya yazar.							     	  |
	|          																				  |
	******************************************************************************************/
	public function simpleWrite($zp = '', $data = '', $length = 0)
	{
		return $this->compress->simpleWrite($zp, $data, $length);
	}
	
	/******************************************************************************************
	* READ   		                                                                          *
	*******************************************************************************************
	| Genel Kullanım: Sıkıştırılmış veriyi dosyadan okur.							     	  |
	|          																				  |
	******************************************************************************************/
	public function read($file = '', $length = 1024, $mode = 'r')
	{
		return $this->compress->read($file, $length, $mode);
	}
	
	/******************************************************************************************
	* SIMPLE READ   	                                                                      *
	*******************************************************************************************
	| Genel Kullanım: Sıkıştırılmış veriyi dosyadan okur.							     	  |
	|          																				  |
	******************************************************************************************/
	public function simpleRead($zp = '', $length = 1024)
	{
		return $this->compress->simpleRead($zp, $length);
	}
	
	/******************************************************************************************
	* COMPRESS		                                                                          *
	*******************************************************************************************
	| Genel Kullanım: Verilen dizgeyi gz kodlamalı olarak sıkıştırır.				     	  |
	|          																				  |
	******************************************************************************************/
	public function force($data = '', $level = -1, $encoding = ZLIB_ENCODING_DEFLATE)
	{
		return $this->compress->compress($data, $level, $encoding);
	}
	
	/******************************************************************************************
	* UNCOMPRESS	                                                                          *
	*******************************************************************************************
	| Genel Kullanım: Gz ile sıkıştırılmış veriyi açar.								     	  |
	|          																				  |
	******************************************************************************************/
	public function unforce($data = '', $length = 0)
	{
		return $this->compress->uncompress($data, $length);
	}
	
	/******************************************************************************************
	* OPTIMIZED FOR	                                                                          *
	*******************************************************************************************
	| Genel Kullanım: ZIP eklentisinin neye göre en iyilendirildiğini bildirir.		    	  |
	|          																				  |
	******************************************************************************************/
	public function optimizedFor()
	{
		return $this->compress->optimizedFor();
	}
	
	/******************************************************************************************
	* ENCODE		                                                                          *
	*******************************************************************************************
	| Genel Kullanım: Gzipli bir dizge oluşturur.				     						  |
	|          																				  |
	******************************************************************************************/
	public function encode($data = '', $level = -1, $encoding = FORCE_GZIP)
	{
		return $this->compress->encode($data, $level, $encoding);
	}
	
	/******************************************************************************************
	* DECODE	                                                      	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli bir dizgenin sıkıştırmasını açar.								  |
	|          																				  |
	******************************************************************************************/
	public function decode($data = '', $length = 0)
	{
		return $this->compress->decode($data, $length);
	}
	
	/******************************************************************************************
	* DEFLATE		                                                                          *
	*******************************************************************************************
	| Genel Kullanım: Bir dizgeyi deflate biçeminde sıkıştırır.								  |
	|          																				  |
	******************************************************************************************/
	public function deflate($data = '', $level = -1, $encoding = ZLIB_ENCODING_RAW)
	{
		return $this->compress->deflate($data, $level, $encoding);
	}
	
	/******************************************************************************************
	* INFLATE	                                                      	                      *
	*******************************************************************************************
	| Genel Kullanım: Deflate bir dizgenin sıkıştırmasını açar.								  |
	|          																				  |
	******************************************************************************************/
	public function inflate($data = '', $length = 0)
	{
		return $this->compress->inflate($data, $length);
	}
	
	/******************************************************************************************
	* CLOSE  	                                                      	                      *
	*******************************************************************************************
	| Genel Kullanım: Bir açık gzipli dosya tanıtıcısını kapar.								  |
	|          																				  |
	******************************************************************************************/
	public function close($zp = '')
	{
		return $this->compress->close($zp);
	}
	
	/******************************************************************************************
	* END OF LINE                                                     	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosya tanıtıcında dosya sonunu sınar.							  |
	|          																				  |
	******************************************************************************************/
	public function eof($zp = '')
	{
		return $this->compress->eof($zp);
	}
	
	/******************************************************************************************
	* FILE                                                          	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosyayı bir dizi içinde döndürür.								  |
	|          																				  |
	******************************************************************************************/
	public function file($zp = '', $includePath = 0)
	{
		return $this->compress->file($zp, $includePath);
	}
	
	/******************************************************************************************
	* GET CHAR                                                        	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosya göstericisindeki karakteri döndürür.						  |
	|          																				  |
	******************************************************************************************/
	public function getChar($zp = '')
	{
		return $this->compress->getChar($zp);
	}
	
	/******************************************************************************************
	* GET LINE                                                        	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosya tanıtıcısından bir satır döndürür.						  |
	|          																				  |
	******************************************************************************************/
	public function getLine($zp = '')
	{
		return $this->compress->getLine($zp);
	}
	
	/******************************************************************************************
	* GET LINE                                                        	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosya tanıtıcısından bir satır okuyup HTML etikelerini siler.	  |
	|          																				  |
	******************************************************************************************/
	public function getCleanLine($zp = '')
	{
		return $this->compress->getCleanLine($zp);
	}
	
	/******************************************************************************************
	* OPEN	                                                        	                      *
	*******************************************************************************************
	| Genel Kullanım: Bir gzipli dosya açar.												  |
	|          																				  |
	******************************************************************************************/
	public function open($filename = '', $mode = '', $includePath = 0)
	{
		return $this->compress->open($filename, $mode, $includePath);
	}
	
	/******************************************************************************************
	* PASS THRU                                                        	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosya tanıtıcısında kalan verinin tamamını çıktılar.			  |
	|          																				  |
	******************************************************************************************/
	public function passThru($zp = '')
	{
		return $this->compress->passThru($zp);
	}
	
	/******************************************************************************************
	* REWIND                                                        	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosya göstericisini dosya başlangıcına taşır.			  |
	|          																				  |
	******************************************************************************************/
	public function rewind($zp = '')
	{
		return $this->compress->rewind($zp);
	}
	
	/******************************************************************************************
	* SEEK	                                                        	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosya göstericisini konumlar.									  |
	|          																				  |
	******************************************************************************************/
	public function seek($zp = '', $offset = 0, $whence = SEEK_SET)
	{
		return $this->compress->seek($zp, $offset, $whence);
	}
	
	/******************************************************************************************
	* TELL                                                        	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosya tanıtıcısının okuma/yazma konumunu döndürür.				  |
	|          																				  |
	******************************************************************************************/
	public function tell($zp = '')
	{
		return $this->compress->tell($zp);
	}
	
	/******************************************************************************************
	* READ FILE                                                        	                      *
	*******************************************************************************************
	| Genel Kullanım: Gzipli dosya tanıtıcısının okuma/yazma konumunu döndürür.				  |
	|          																				  |
	******************************************************************************************/
	public function readFile($fileName = '', $includePath = 0)
	{
		return $this->compress->readFile($fileName, $includePath);
	}
	
	/******************************************************************************************
	* ENCODING TYPE	                                                   	                      *
	*******************************************************************************************
	| Genel Kullanım: Çıktı sıkıştırması için kullanılan kodlama türünü döndürür.			  |
	|          																				  |
	******************************************************************************************/
	public function encodingType()
	{
		return $this->compress->encodingType();
	}
	
	/******************************************************************************************
	* DIFFERENT DRIVER                                                                        *
	*******************************************************************************************
	| Genel Kullanım: Farklı sürücüleri aynı anda kullanmak için kullanılır. 		          |
	|															                              |
	| Parametreler: Tek parametresi vardır.                                                   |
	| 1. string var @driver => Farklı olarak kullanılacak sürücü.							  |
	|																						  |
	| Örnek Kullanım: ->driver('gz');			        				     				  |
	|          																				  |
	******************************************************************************************/
	public function driver($driver = '')
	{
		return new self($driver);	
	}
}