<?php	
interface FiltersInterface
{
	//----------------------------------------------------------------------------------------------------
	//
	// Yazar      : Ozan UYKUN <ozanbote@windowslive.com> | <ozanbote@gmail.com>
	// Site       : www.zntr.net
	// Lisans     : The MIT License
	// Telif Hakkı: Copyright (c) 2012-2016, zntr.net
	//
	//----------------------------------------------------------------------------------------------------	
	
	/******************************************************************************************
	* GET VAR                                                                                 *
	*******************************************************************************************
	| Genel Kullanım: GET değişkeninin tanımlı olup olmadına bakar.				 	          |
	|          																				  |
	******************************************************************************************/
	public function getVar($varName);	
	
	/******************************************************************************************
	* POST VAR                                                                                *
	*******************************************************************************************
	| Genel Kullanım: POST değişkeninin tanımlı olup olmadına bakar.			 	          |
	|          																				  |
	******************************************************************************************/
	public function postVar($varName);
	
	/******************************************************************************************
	* COOKIE VAR                                                                              *
	*******************************************************************************************
	| Genel Kullanım: COOKIE değişkeninin tanımlı olup olmadına bakar.			 	          |
	|          																				  |
	******************************************************************************************/
	public function cookieVar($varName);
	
	/******************************************************************************************
	* ENV VAR                                                                  		          *
	*******************************************************************************************
	| Genel Kullanım: ENV değişkeninin tanımlı olup olmadına bakar.				 	          |
	|          																				  |
	******************************************************************************************/
	public function envVar($varName);
	
	/******************************************************************************************
	* SERVER VAR                                                                              *
	*******************************************************************************************
	| Genel Kullanım: SERVER değişkeninin tanımlı olup olmadına bakar.			 	          |
	|          																				  |
	******************************************************************************************/
	public function serverVar($varName);
	
	/******************************************************************************************
	* ID		                                                                              *
	*******************************************************************************************
	| Genel Kullanım: Filter nesnesinin idsini döndürür.						 	          |
	|          																				  |
	******************************************************************************************/
	public function id($filterName);
	
	/******************************************************************************************
	* GET LIST                                                                                *
	*******************************************************************************************
	| Genel Kullanım: Filtre nesnelerinin listesini döndürür.					 	          |
	|          																				  |
	******************************************************************************************/
	public function getList();	
	
	/******************************************************************************************
	* INPUT ARRAY                                                                             *
	*******************************************************************************************
	| Genel Kullanım: Harici değişkenleri alır ve isteğe bağlı olarak filtreler.		      |
	|          																				  |
	******************************************************************************************/
	public function inputArray($type, $definition, $addEmpty);
	
	/******************************************************************************************
	* VAR ARRAY                                                                               *
	*******************************************************************************************
	| Genel Kullanım: Harici değişkenleri alır ve isteğe bağlı olarak filtreler.		      |
	|          																				  |
	******************************************************************************************/
	public function varArray($data, $definition, $addEmpty);
	
	/******************************************************************************************
	* INPUT 	                                                                              *
	*******************************************************************************************
	| Genel Kullanım: Harici değişkenleri alır ve isteğe bağlı olarak filtreler.		      |
	|          																				  |
	******************************************************************************************/
	public function input($var, $type, $filter, $options);
	
	/******************************************************************************************
	* VAR    	                                                                              *
	*******************************************************************************************
	| Genel Kullanım: Birden çok değişken alır ve isteğe bağlı olarak bunları filtreler.	  |
	|          																				  |
	******************************************************************************************/
	public function vars($var, $filter, $options);
}