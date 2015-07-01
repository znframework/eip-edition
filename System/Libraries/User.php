<?php
/************************************************************/
/*                        CLASS  USER                       */
/************************************************************/
/*

Author: Ozan UYKUN
Site: http://www.zntr.net
Copyright 2012-2015 zntr.net - Tüm hakları saklıdır.

*/
/******************************************************************************************
* USER                                                                              	  *
*******************************************************************************************
| Sınıfı Kullanırken      :	user:: , $this->user , zn::$use->user , uselib('user')-> 	  |
| 																						  |
| Kütüphanelerin kısa isimlendirmelerle kullanımı için. Config/Libraries.php bakınız.     |
******************************************************************************************/
class User
{
	
	/* Username Değişkeni
	 *  
	 * Kullanıcı adı bilgisini
	 * tutması için oluşturulmuştur.
	 *
	 */
	private static $username;
	
	/* Password Değişkeni
	 *  
	 * Kullanıcı şifre bilgisini
	 * tutması için oluşturulmuştur.
	 *
	 */
	private static $password;
	
	/* Error Değişkeni
	 *  
	 * Kullanıcı işlemlerinde oluşan hata bilgilerini
	 * tutması için oluşturulmuştur.
	 *
	 */
	private static $error;
	
	/* Success Değişkeni
	 *  
	 * Kullanıcı işlemlerin bilgilerini
	 * bilgisini tutması için oluşturulmuştur.
	 *
	 */
	private static $success;
	
	/******************************************************************************************
	* REGISTER                                                                                *
	*******************************************************************************************
	| Genel Kullanım: Kullanıcıyı kaydetmek için kullanılır.		        		          |
	|															                              |
	| Parametreler: 2 parametresi vardır.                                                     |
	| 1. array var @data => Kaydedilecek üye bilgileri anahtar değer çifti içeren bir dizi    |
	| içeriği ile kaydedilir. Dizideki anahtar ifadeler sütun isimlerini değer ifadeleri ise  |
	| bu sütuna kaydedilecek veriyi belirtir.											 	  |
	| 2. string/boolean var @autoLogin => Kayıttan sonra otomatik giriş olsun mu?		      |
	| True: Otomatik giriş olsun															  |
	| False: Otomatik giriş olmasın															  |
	| String Yol: Otomatik giriş olmasın ve belirtilen yola yönlendirilsin.					  |
	| 3. [ string var @activation_return_link ] => Aktivasyon yapılacaksa kayıt yapılırken    |
	| kullanıcıya gönderilen aktivasyon mailinin içerisindeki linke tıkladığında gidilecek	  |
	| sayfa belirtilir. Bu parametre isteğe bağlıdır.                                         |
	|          																				  |
	| Örnek Kullanım: register(array('user' => 'zntr', 'pass' => '1234'));       		      |
	|          																				  |
	******************************************************************************************/
	public static function register($data = array(), $auto_login = false, $activation_return_link = '')
	{
		if( ! is_array($data) ) 
		{
			return false;
		}
		if( ! is_string($activation_return_link) ) 
		{
			$activation_return_link = '';
		}
		
		// ------------------------------------------------------------------------------
		// CONFIG/USER.PHP AYARLARI
		// Config/User.php dosyasında belirtilmiş ayarlar alınıyor.
		// ------------------------------------------------------------------------------
		$user_config		= Config::get("User");		
		$username_column  	= $user_config['usernameColumn'];
		$password_column  	= $user_config['passwordColumn'];
		$email_column  	    = $user_config['emailColumn'];
		$table_name 		= $user_config['tableName'];
		$active_column 		= $user_config['activeColumn'];
		$activation_column 	= $user_config['activationColumn'];
		// ------------------------------------------------------------------------------
		
		// Kullanıcı adı veya şifre sütunu belirtilmemişse 
		// İşlemleri sonlandır.
		if( ! isset($data[$username_column]) ||  ! isset($data[$password_column]) ) 
		{
			return false;
		}
		
		$login_username  = $data[$username_column];
		$login_password  = $data[$password_column];	
		$encode_password = Encode::super($login_password);	
		
		$db = uselib('DB');
		
		$username_control = $db->where($username_column.' =',$login_username)
							   ->get($table_name)
							   ->totalRows();
		
		// Daha önce böyle bir kullanıcı
		// yoksa kullanıcı kaydetme işlemini başlat.
		if( empty($username_control) )
		{
			$data[$password_column] = $encode_password;
			
			if( $db->insert($table_name , $data) )
			{
				self::$error = false;
				self::$success = lang('User', 'registerSuccess');
				
				if( ! empty($activation_column) )
				{
					if( ! isEmail($login_username) )
					{
						$email = $data[$email_column];
					}
					else
					{ 
						$email = '';
					}
					
					self::_activation($login_username, $encode_password, $activation_return_link, $email);				
				}
				else
				{
					if( $auto_login === true )
					{
						self::login($login_username, $login_password);
					}
					elseif( is_string($auto_login) )
					{
						redirect($auto_login);	
					}
				}
				
				return true;
			}
			else
			{
				self::$error = lang('User', 'registerUnknownError');	
				return false;
			}
		}
		else
		{
			self::$error = lang('User', 'registerError');
			return false;
		}
		
	}
	
	/******************************************************************************************
	* ACTIVATION COMPLETE                                                                     *
	*******************************************************************************************
	| Genel Kullanım: Register() yönteminde belirtilen dönüş linkinin gösterdiği sayfada      |
	| kullanarak aktrivasyon işleminin tamamlanmasını sağlar.		        		          |
	|															                              |
	| Parametreler: Herhangi bir parametresi yoktur.                                          |
	|          																				  |
	| Örnek Kullanım: activationComplete(); 									              |
	| NOT: Aktivasyon dönüş linkinin belirtiği sayfada kullanılmalıdır                        |
	|          																				  |
	******************************************************************************************/
	public static function activationComplete()
	{
		// ------------------------------------------------------------------------------
		// CONFIG/USER.PHP AYARLARI
		// Config/User.php dosyasında belirtilmiş ayarlar alınıyor.
		// ------------------------------------------------------------------------------
		$user_config		= Config::get("User");	
		$table_name 		= $user_config['tableName'];
		$username_column  	= $user_config['usernameColumn'];
		$password_column  	= $user_config['passwordColumn'];
		$activation_column 	= $user_config['activationColumn'];
		// ------------------------------------------------------------------------------
		
		// Aktivasyon dönüş linkinde yer alan segmentler -------------------------------
		$user = Uri::get('user');
		$pass = Uri::get('pass');
		// ------------------------------------------------------------------------------
		
		if( ! empty($user) && ! empty($pass) )	
		{
			$db = uselib('DB');
			
			$row = $db->where($username_column.' =', $user, 'and')
			          ->where($password_column.' =', $pass)		
			          ->get($table_name)
					  ->row();	
				
			if( ! empty($row) )
			{
				$db->where($username_column.' =', $user)
				   ->update($table_name, array($activation_column => '1'));
				
				self::$success = lang('User', 'activationComplete');
				
				return true;
			}	
			else
			{
				self::$error = lang('User', 'activationCompleteError');
				return false;
			}				
		}
		else
		{
			self::$error = lang('User', 'activationCompleteError');
			return false;
		}
	}
	
	// Aktivasyon işlemi için
	private static function _activation($user = "", $pass = "", $activation_return_link = '', $email = '')
	{
		if( ! isUrl($activation_return_link) )
		{
			$url = baseUrl(suffix($activation_return_link));
		}
		else
		{
			$url = suffix($activation_return_link);
		}
		
		$message = "<a href='".$url."user/".$user."/pass/".$pass."'>".lang('User', 'activation')."</a>";	
		
		$user = ( ! empty($email) ) 
				? $email 
				: $user;
				
		$sendEmail = uselib('Email');
		
		$sendEmail->receiver($user, $user);
		$sendEmail->subject(lang('User', 'activationProcess'));
		$sendEmail->content($message);
		
		if( $sendEmail->send() )
		{
			self::$success = lang('User', 'activationEmail');
			return true;
		}
		else
		{	
			self::$success = false;
			self::$error = lang('User', 'emailError');
			return false;
		}
	}
	
	/******************************************************************************************
	* TOTAL ACTIVE USERS                                                                      *
	*******************************************************************************************
	| Genel Kullanım: Kullanıcılardan aktif olanların sayısını verir.		        		  |
	|															                              |
	| Parametreler: Herhangi bir parametresi yoktur.                                          |
	|          																				  |
	| Örnek Kullanım: totalActiveUsers(); 									              |
	|          																				  |
	******************************************************************************************/
	public static function totalActiveUsers()
	{
		$active_column 	= Config::get("User",'activeColumn');	
		$table_name 	= Config::get("User",'tableName');
		
		if( ! empty($active_column) )
		{
			$db = uselib('DB');
			
			$total_rows = $db->where($active_column.' =', 1)
							 ->get($table_name)
							 ->totalRows();
			
			if( ! empty($total_rows) )
			{
				return $total_rows;
			}
			else
			{
				return 0;		
			}
		}
		
		return false;
	}
	
	/******************************************************************************************
	* TOTAL BANNED USERS                                                                      *
	*******************************************************************************************
	| Genel Kullanım: Kullanıcılardan yasaklı olanların sayısını verir.		        		  |
	|															                              |
	| Parametreler: Herhangi bir parametresi yoktur.                                          |
	|          																				  |
	| Örnek Kullanım: totalBannedUsers(); 									              |
	|          																				  |
	******************************************************************************************/
	public static function totalBannedUsers()
	{
		$banned_column 	= Config::get("User",'bannedColumn');	
		$table_name 	= Config::get("User",'tableName');
		
		if( ! empty($banned_column) )
		{	
			$db = uselib('DB');
			
			$total_rows = $db->where($banned_column.' =', 1)
							 ->get($table_name)
						 	 ->totalRows();
			
			if( ! empty($total_rows) )
			{
				return $total_rows;
			}
			else
			{
				return 0;		
			}
		}
		
		return false;
	}
	
	/******************************************************************************************
	* TOTAL USERS                                                                             *
	*******************************************************************************************
	| Genel Kullanım: Kullanıcıların toplam sayısını verir.		        		              |
	|															                              |
	| Parametreler: Herhangi bir parametresi yoktur.                                          |
	|          																				  |
	| Örnek Kullanım: totalBannedUsers(); 									              |
	|          																				  |
	******************************************************************************************/
	public static function totalUsers()
	{
		$table_name = Config::get("User",'tableName');
		
		$db = uselib('DB');
		
		$total_rows = $db->get($table_name)->totalRows();
		
		if( ! empty($total_rows) )
		{
			return $total_rows;
		}
		else
		{
			return 0;		
		}
	}
	
	/******************************************************************************************
	* LOGIN                                                                                   *
	*******************************************************************************************
	| Genel Kullanım: Kullanıcı girişi yapmak için kullanılır.		        		          |
	|															                              |
	| Parametreler: 3 parametresi vardır.                                                     |
	| 1. string var @username => Kullanıcı adı parametresi.								      |
	| 2. string var @password => Kullanıcı şifre parametresi.								  |
	| 3. boolean var @remember_me => Kullanıcı adı ve şifresi hatırlansın mı?.				  |
	|          																				  |
	| Örnek Kullanım: login('zntr', '1234', true);       		                              |
	|          																				  |
	******************************************************************************************/	
	public static function login($un = 'username', $pw = 'password', $remember_me = false)
	{
		if( ! is_string($un) ) 
		{
			return false;
		}
		
		if( ! is_string($pw) ) 
		{
			return false;
		}
		
		if( ! isValue($remember_me) ) 
		{
			$remember_me = false;
		}

		$username = $un;
		$password = Encode::super($pw);
		
		// ------------------------------------------------------------------------------
		// CONFIG/USER.PHP AYARLARI
		// Config/User.php dosyasında belirtilmiş ayarlar alınıyor.
		// ------------------------------------------------------------------------------
		$user_config		= Config::get("User");	
		$password_column  	= $user_config['passwordColumn'];
		$username_column  	= $user_config['usernameColumn'];
		$email_column  		= $user_config['emailColumn'];
		$table_name 		= $user_config['tableName'];
		$banned_column 		= $user_config['bannedColumn'];
		$active_column 		= $user_config['activeColumn'];
		$activation_column 	= $user_config['activationColumn'];
		// ------------------------------------------------------------------------------
		
		$db = uselib('DB');
		
		$r = $db->where($username_column.' =',$username)
			    ->get($table_name)
				->row();
			
		$password_control   = $r->$password_column;
		$banned_control     = '';
		$activation_control = '';
		
		if( ! empty($banned_column) )
		{
			$banned = $banned_column ;
			$banned_control = $r->$banned ;
		}
		
		if( ! empty($activation_column) )
		{
			$activation_control = $r->$activation_column ;			
		}
		
		if( ! empty($r->$username_column) && $password_control == $password )
		{
			if( ! empty($banned_column) && ! empty($banned_control) )
			{
				self::$error = lang('User', 'bannedError');	
				return false;
			}
			
			if( ! empty($activation_column) && empty($activation_control) )
			{
				self::$error = lang('User', 'activationError');	
				return false;
			}
			
			if( ! isset($_SESSION) ) 
			{
				session_start();
			}
			
			$_SESSION[md5($username_column)] = $username; 
			
			session_regenerate_id();
			
			if( Method::post($remember_me) || ! empty($remember_me) )
			{
				if( Cookie::select(md5($username_column)) != $username )
				{					
					Cookie::insert(md5($username_column),$username);
					Cookie::insert(md5($password_column),$password);
				}
			}
			
			if( ! empty($active_column) )
			{		
				$db->where($username_column.' =', $username)->update($table_name, array($active_column  => 1));
			}
			
			self::$error = false;
			self::$success = lang('User', 'loginSuccess');
			return true;
		}
		else
		{
			self::$error = lang('User', 'loginError');	
			return false;
		}
	}
	
	/******************************************************************************************
	* FORGOT PASSWORD                                                                         *
	*******************************************************************************************
	| Genel Kullanım: Şifremi unuttum uygulamasıdır.		        		         		  |
	|															                              |
	| Parametreler: 2 parametresi vardır.                                                     |
	| 1. string var @email => Kullanıcı e-posta adresi veya kullanıcı adı.					  |
	| 2. string var @return_link_path => e-postaya gönderilen linkin dönüş sayfası.			  |
	|          																				  |
	| Örnek Kullanım: forgotPassword('bilgi@zntr.net', 'kullanici/giris');       		      |
	|          																				  |
	******************************************************************************************/	
	public static function forgotPassword($email = "", $return_link_path = "")
	{
		if( ! is_string($email) ) 
		{
			return false;
		}
		
		if( ! is_string($return_link_path) ) 
		{
			$return_link_path = '';
		}

		// ------------------------------------------------------------------------------
		// CONFIG/USER.PHP AYARLARI
		// Config/User.php dosyasında belirtilmiş ayarlar alınıyor.
		// ------------------------------------------------------------------------------
		$user_config		= Config::get("User");	
		$username_column  	= $user_config['usernameColumn'];
		$password_column  	= $user_config['passwordColumn'];				
		$email_column  		= $user_config['emailColumn'];		
		$table_name 		= $user_config['tableName'];	
		// ------------------------------------------------------------------------------
		
		$db = uselib('DB');
		
		if( ! empty($email_column) )
		{
			$db->where($email_column.' =', $email);
		}
		else
		{
			$db->where($username_column.' =', $email);
		}
		
		$row = $db->get($table_name)->row();
		
		$result = "";
		
		if( isset($row->$username_column) ) 
		{
			
			if( ! isUrl($return_link_path) ) 
			{
				$return_link_path = siteUrl($return_link_path);
			}
			
			$new_password    = Encode::create(10);
			$encode_password = Encode::super($new_password);
			$message = "
			<pre>
				".lang('User', 'username').": ".$row->$username_column."

				".lang('User', 'password').": ".$new_password."
				
				<a href='".$return_link_path."'>".lang('User', 'learnNewPassword')."</a>
			</pre>
			";
			
			$sendEmail = uselib('Email');
			
			$sendEmail->receiver($email, $email);
			$sendEmail->subject(lang('User', 'newYourPassword'));
			$sendEmail->content($message);
			
			if( $sendEmail->send() )
			{
				if( ! empty($email_column) )
				{
					$db->where($email_column.' =', $email);
				}
				else
				{
					$db->where($username_column.' =', $email);
				}
				
				$db->update($table_name, array($password_column => $encode_password));

				self::$error = true;	
				self::$success = lang('User', 'forgotPasswordSuccess');
				return false;
			}
			else
			{	
				self::$success = false;
				self::$error = lang('User', 'emailError');
				return false;
			}
		}
		else
		{
			self::$success = false;
			self::$error = lang('User', 'forgotPasswordError');	
			return false;
		}
	}
	
	/******************************************************************************************
	* UPDATE                                                                                  *
	*******************************************************************************************
	| Genel Kullanım: Kullanıcı bilgilerinin güncellenmesi için kullanılır.		        	  |
	|															                              |
	| Parametreler: 4 parametresi vardır.                                                     |
	| 1. string var @old => Kullanıcının eski şifresi.                   					  |
	| 2. string var @new => Kullanıcının yeni şifresi.                   					  |
	| 3. [ string var @new_again ] => Kullanıcının eski şifresi tekrar. Zorunlu değildir.     |
	| 4. array var @data => Kullanıcının güncellenecek bilgileri.                             |
	|          																				  |
	| Örnek Kullanım: update('eski1234', 'yeni1234', NULL, array('telefon' => 'xxxxx'));      |
	|          																				  |
	******************************************************************************************/	
	public static function update($old = '', $new = '', $new_again = '', $data = array())
	{
		// Bu işlem için kullanıcının
		// oturum açmıl olması gerelidir.
		if( self::isLogin() )
		{
			// Parametreler kontrol ediliyor.--------------------------------------------------
			if( ! is_string($old) || ! is_string($new) || ! is_array($data) ) 
			{
				return false;
			}
				
			if( empty($old) || empty($new) || empty($data) ) 
			{
				return false;
			}
	
			if( ! is_string($new_again) ) 
			{
				$new_again = '';
			}
			// --------------------------------------------------------------------------------
			
				
			// Şifre tekrar parametresi boş ise
			// Şifre tekrar parametresini doğru kabul et.
			if( empty($new_again) ) 
			{
				$new_again = $new;
			}
	
			$user_config = Config::get("User");	
			$pc = $user_config['passwordColumn'];
			$uc = $user_config['usernameColumn'];	
			$tn = $user_config['tableName'];
			
			$old_password = Encode::super($old);
			$new_password = Encode::super($new);
			$new_password_again = Encode::super($new_again);
			
			$username 	  = user::data()->$uc;
			$password 	  = user::data()->$pc;
			$row = "";
					
			if( $old_password != $password )
			{
				self::$error = lang('User', 'oldPasswordError');
				return false;	
			}
			elseif( $new_password != $new_password_again )
			{
				self::$error = lang('User', 'passwordNotMatchError');
				return false;
			}
			else
			{
				$data[$pc] = $new_password;
				$data[$uc] = $username;
				
				$db = uselib('DB');
				
				$db->where($uc.' =', $username);
				
				if( $db->update($tn, $data) )
				{
					self::$error = false;
					self::$success = lang('User', 'updateProcessSuccess');
					return true;
				}
				else
				{
					self::$error = lang('User', 'registerUnknownError');	
					return false;
				}		
			}
			
		}
		else 
		{
			return false;		
		}
	}
	
	/******************************************************************************************
	* IS LOGIN                                                                                *
	*******************************************************************************************
	| Genel Kullanım: Kullanıcının oturum açıp açmadığını kontrol etmek için kullanılır.	  |
	|															                              |
	| Parametreler: Herhangi bir parametresi yoktur.                                          |
	|          																				  |
	| Örnek Kullanım: isLogin();      														  |
	|          																				  |
	******************************************************************************************/	
	public static function isLogin()
	{
		$c_username = Cookie::select(md5(Config::get("User",'usernameColumn')));
		$c_password = Cookie::select(md5(Config::get("User",'passwordColumn')));
		
		$result = '';
		
		if( ! empty($c_username) && ! empty($c_password) )
		{
			$db = uselib('DB');
			$result = $db->where(Config::get("User",'usernameColumn').' =',$c_username, 'and')
						 ->where(Config::get("User",'passwordColumn').' =',$c_password)
						 ->get(Config::get("User",'tableName'))
						 ->totalRows();
		}
		
		$username = Config::get("User",'usernameColumn');
		
		if( isset(self::data()->$username) )
		{
			$is_login = true;
		}
		elseif( ! empty($result) )
		{
			if( ! isset($_SESSION) ) 
			{
				session_start();
			}
			
			$_SESSION[md5(Config::get("User",'usernameColumn'))] = $c_username;
			$is_login = true;	
		}
		else
		{
			$is_login = false;	
		}
				
		return $is_login;
	}
	
	/******************************************************************************************
	* DATA                                                                                    *
	*******************************************************************************************
	| Genel Kullanım: Oturum açmış kullanıcın veritabanı bilgilerine erişimek için kullanılır.|
	| Çıktı olarak object türünde veri dizisi döndürür.										  |
	|          																				  |
	| Parametreler: Herhangi bir parametresi yoktur.                                          |
	|          																				  |
	| Örnek Kullanım: $data = data();      													  |
	|          																				  |
	| $data->sutun_adi          															  |
	|          																				  |
	******************************************************************************************/	
	public static function data()
	{
		if( ! isset($_SESSION) ) 
		{
			session_start();
		}

		if( isset($_SESSION[md5(Config::get("User",'usernameColumn'))]) )
		{
			$data = array();
			self::$username = $_SESSION[md5(Config::get("User",'usernameColumn'))];
			
			$db = uselib('DB');
			
			$r = $db->where(Config::get("User",'usernameColumn').' =',self::$username)
				    ->get(Config::get("User",'tableName'))
					->row();
			
			return (object)$r;
		}
		else return false;
	}
	
	/******************************************************************************************
	* LOGOUT                                                                                  *
	*******************************************************************************************
	| Genel Kullanım: Oturumu sonlandırmak için kullanılır.									  |
	|          																				  |
	| Parametreler: 2 parametresi vardır.                                                     |
	| 1. string var @redirect_url => Çıkış sonrası yönlendirilecek sayfa.                     |
	| 1. numeric var @time => çıkış yapıldıktan sonra yönlendirme için bekleme süresi.        |
	|          																				  |
	| Örnek Kullanım: logout('kullanici/giris');      									      |
	|          																				  |
	******************************************************************************************/
	public static function logout($redirect_url = '', $time = 0)
	{	
		if( ! is_string($redirect_url) ) 
		{
			$redirect_url = '';
		}
		
		if( ! is_numeric($time) ) 
		{
			$time = 0;
		}

		$username = Config::get("User",'usernameColumn');
		
		if( isset(self::data()->$username) )
		{
			if( ! isset($_SESSION) ) 
			{
				session_start();
			}
			
			if( Config::get("User",'activeColumn') )
			{	
				$db = uselib('DB');
				
				$db->where(Config::get("User",'usernameColumn').' =', self::data()->$username)
				   ->update(Config::get("User",'tableName'), array(Config::get("User",'activeColumn') => 0));
			}
			
			Cookie::delete(md5(Config::get("User",'usernameColumn')));
			Cookie::delete(md5(Config::get("User",'passwordColumn')));	
			
			if( isset($_SESSION[md5(Config::get("User",'usernameColumn'))]) ) 
			{
				unset($_SESSION[md5(Config::get("User",'usernameColumn'))]);
			}
			
			redirect($redirect_url, $time);
		}
		
	}
	
	/******************************************************************************************
	* ERROR                                                                                   *
	*******************************************************************************************
	| Genel Kullanım: Kullanıcı işlemlerinde oluşan hata bilgilerini tutması içindir.         |
	|     														                              |
	| Parametreler: Herhangi bir parametresi yoktur.                                          |
	|     														                              |
	******************************************************************************************/
	public static function error()
	{
		if( ! empty(self::$error) ) 
		{
			return self::$error; 
		}
		else 
		{
			return false;	
		}
	}
	
	/******************************************************************************************
	* SUCCESS                                                                                 *
	*******************************************************************************************
	| Genel Kullanım: Kullanıcı işlemlerinde başarı bilgilerini tutması içindir.              |
	|     														                              |
	| Parametreler: Herhangi bir parametresi yoktur.                                          |
	|     														                              |
	******************************************************************************************/
	public static function success()
	{
		if( ! empty(self::$success) ) 
		{
			return self::$success; 
		}
		else 
		{
			return false;
		}
	}
}