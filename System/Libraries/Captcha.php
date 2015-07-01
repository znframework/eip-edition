<?php
/************************************************************/
/*                     LIBRARY CAPTCHA                      */
/************************************************************/
/*

Author: Ozan UYKUN
Site: http://www.zntr.net
Copyright 2012-2015 zntr.net - Tüm hakları saklıdır.

*/
/******************************************************************************************
* CAPTCHA                                                                             	  *
*******************************************************************************************
| Sınıfı Kullanırken : captcha::, $this->captcha, zn::$use->captcha, uselib('captcha')	  |
| 																						  |
| Kütüphanelerin kısa isimlendirmelerle kullanımı için. Config/Namespace.php bakınız.     |
******************************************************************************************/	
class Captcha
{
	/******************************************************************************************
	* CREATE CAPTCHA                                                                          *
	*******************************************************************************************
	| Genel Kullanım: Güvenlik kodu oluşturmak için kullanılır. 							  |
	|																						  |
	| Parametreler: Tek parametresi vardır.                                              	  |
	| 1. boolean var @img => <img> nesnesi oluşturulsun mu?.						     	  |
	|          																				  |
	| Örnek Kullanım:         																  |
	| echo create_captcha(true);															  |
	|																						  |
	| $kod = create_captcha(); 																  |
	| echo '<img src="'.$kod.'" />'; 														  |
	|																						  |
	******************************************************************************************/	
	public static function create($img = false, $configs = array())
	{
		if( ! isset($_SESSION) ) 
		{
			session_start();
		}
		
		if( ! empty($configs) )
		{
			Config::set('Captcha', $configs);
		}
		
		$set = Config::get("Captcha");
		
		$_SESSION[md5('captcha_code')] = substr(md5(rand(0,999999999999999)),-($set['charLength']));	
		
		if( isset($_SESSION[md5('captcha_code')]) )
		{
			if( ! isset($set["width"])) $set["width"] 								= 100;
			if( ! isset($set["height"])) $set["height"] 							= 30;
			if( ! isset($set['textColor'])) $set['textColor'] 						= "0|0|0";
			if( ! isset($set['bgColor'])) $set['bgColor'] 							= "255|255|255";
			if( ! isset($set["border"]))$set["border"] 								= true;
			if( ! isset($set['borderColor'])) $set['borderColor'] 					= "200|200|200";
			if( ! isset($set['imageString']["size"]))$set['imageString']["size"] 	= "5";
			if( ! isset($set['imageString']["x"]))$set['imageString']["x"] 			= "23";
			if( ! isset($set['imageString']["y"]))$set['imageString']["y"] 			= "9";
			if( ! isset($set["grid"]))$set["grid"] 									= false; 
			if( ! isset($set['gridSpace']["x"]))$set['gridSpace']["x"] 				= 12; 
			if( ! isset($set['gridSpace']["y"]))$set['gridSpace']["y"] 				= 4; 
			if( ! isset($set['gridColor']))$set['gridColor']						= "240|240|240";
			if( ! isset($set["background"]))$set["background"]						= array();
			
			// 0-255 arasında değer alacak renk kodları için
			// 0|20|155 gibi bir kullanım için aşağıda
			// explode ile ayırma işlemleri yapılmaktadır.
			
			// SET FONT COLOR
			$set_font_color = explode("|",$set['textColor']);
			
			// SET BG COLOR
			$set_bg_color	= explode("|",$set['bgColor']);
			
			// SET BORDER COLOR
			$set_border_color	= explode("|",$set['borderColor']);
			
			// SET GRID COLOR
			$set_grid_color	= explode("|",$set['gridColor']);
			
			
			$file = @imagecreatetruecolor($set["width"], $set["height"]);	  
				  
			$font_color 	= @imagecolorallocate($file, $set_font_color[0], $set_font_color[1], $set_font_color[2]);
			$color 			= @imagecolorallocate($file, $set_bg_color[0], $set_bg_color[1], $set_bg_color[2]);
			
			// ARKAPLAN RESMI--------------------------------------------------------------------------------------
			if( ! empty($set["background"]) )
			{
				if( is_array($set["background"]) )
				{
					$set["background"] = $set["background"][rand(0, count($set["background"]) - 1)];
				}
				/***************************************************************************/
				// Arkaplan resmi için geçerli olabilecek uzantıların kontrolü yapılıyor.
				/***************************************************************************/	
				if( strtolower(pathinfo($set["background"], PATHINFO_EXTENSION)) === 'png' )
				{
					$file = imagecreatefrompng($set["background"]);
				}
				if( strtolower(pathinfo($set["background"], PATHINFO_EXTENSION)) === 'jpeg' )
				{	
					$file = imagecreatefromjpeg($set["background"]);
				}
				if( strtolower(pathinfo($set["background"], PATHINFO_EXTENSION)) === 'jpg' )
				{	
					$file = imagecreatefromjpeg($set["background"]);
				}
				if( strtolower(pathinfo($set["background"], PATHINFO_EXTENSION)) === 'gif' )
				{	
					$file = imagecreatefromgif($set["background"]);
				}
			}
			else
			{
				// Arkaplan olarak resim belirtilmemiş ise arkaplan rengini ayarlar.
				@imagefill($file, 0, 0, $color);
			}
			//-----------------------------------------------------------------------------------------------------
			
			// Resim üzerinde görüntülenecek kod bilgisi.
			@imagestring($file, $set['imageString']["size"], $set['imageString']["x"], $set['imageString']["y"],  $_SESSION[md5('captcha_code')], $font_color);
			
			// GRID --------------------------------------------------------------------------------------
			if( $set["grid"] === true )
			{
				$grid_interval_x  = $set["width"] / $set['gridSpace']["x"];
				
				if( ! isset($set['gridSpace']["y"]))
				{
					$grid_interval_y  = (($set["height"] / $set['gridSpace']["x"]) * $grid_interval_x / 2);
					
				} else $grid_interval_y  = $set["height"] / $set['gridSpace']["y"];
				
				$grid_color 	= @imagecolorallocate($file, $set_grid_color[0], $set_grid_color[1], $set_grid_color[2]);
				
				for($x = 0 ; $x <= $set["width"] ; $x += $grid_interval_x)
				{
					@imageline($file,$x,0,$x,$set["height"] - 1,$grid_color);
				}
				
				for($y = 0 ; $y <= $set["width"] ; $y += $grid_interval_y)
				{
					@imageline($file,0,$y,$set["width"],$y,$grid_color);
				}
				
			}
			// ---------------------------------------------------------------------------------------------	
			
			// BORDER --------------------------------------------------------------------------------------
			if( $set["border"] === true )
			{
				$border_color 	= @imagecolorallocate($file, $set_border_color[0], $set_border_color[1], $set_border_color[2]);
				
				@imageline($file, 0, 0, $set["width"], 0, $border_color); // UST
				@imageline($file, $set["width"] - 1, 0, $set["width"] - 1, $set["height"], $border_color); // SAG
				@imageline($file, 0, $set["height"] - 1, $set["width"], $set["height"] - 1, $border_color); // ALT
				@imageline($file, 0, 0, 0, $set["height"] - 1, $border_color); // SOL
			}
			// ---------------------------------------------------------------------------------------------
			
			$file_path = FILES_DIR.'capcha';
			
			if( function_exists('imagepng') )
			{
				$extension = '.png';
				imagepng($file, $file_path.$extension);
			}
			elseif( function_exists('imagejpg'))
			{
				$extension = '.jpg';
				imagepng($file, $file_path.$extension);		
			}
			else
			{
				return false;
			}
			
			$file_path .= $extension;
			
			if( $img === true )
			{	
				$captcha = '<img src="'.baseUrl($file_path).'">';
			}
			else
			{
				$captcha = baseUrl($file_path);
			}
			
			imagedestroy($file);
			
			return $captcha;
		}	
	}

	/******************************************************************************************
	* GET CAPTCHA CODE                                                                        *
	*******************************************************************************************
	| Genel Kullanım: Daha önce oluşturulan güvenlik uygulamasının kod bilgini verir. 		  |
	|																						  |
	| Parametreler: Herhangi bir parametresi yoktur.                                          |
	|          																				  |
	| Örnek Kullanım:         																  |
	| echo get_captcha_code(); // Çıktı: 1A4D31 											  |
	|																						  |
	******************************************************************************************/	
	public static function getCode()
	{
		if( ! isset($_SESSION) ) 
		{
			session_start();
		}
		
		return $_SESSION[md5('captcha_code')];
	}	
}