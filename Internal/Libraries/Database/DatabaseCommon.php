<?php namespace ZN\Database;

class DatabaseCommon extends \Requirements implements DatabaseCommonInterface
{
    //--------------------------------------------------------------------------------------------------------
    //
    // Author     : Ozan UYKUN <ozanbote@gmail.com>
    // Site       : www.znframework.com
    // License    : The MIT License
    // Telif Hakkı: Copyright (c) 2012-2016, znframework.com
    //
    //--------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------
    // Drivers
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var array
    //
    //--------------------------------------------------------------------------------------------------------
    protected $drivers = 
    [
        'odbc',
        'mysqli',
        'pdo',
        'oracle',
        'postgres',
        'sqlite',
        'sqlserver',
        'pdo:mysql',
        'pdo:postgres',
        'pdo:sqlite',
        'pdo:sqlserver',
        'pdo:odbc'
    ];

    //--------------------------------------------------------------------------------------------------------
    // Config
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var array
    //
    //--------------------------------------------------------------------------------------------------------
    protected $config;
    
    //--------------------------------------------------------------------------------------------------------
    // Prefix
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $prefix;
    
    //--------------------------------------------------------------------------------------------------------
    // Secure
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var array
    //
    //--------------------------------------------------------------------------------------------------------
    protected $secure = [];
    
    //--------------------------------------------------------------------------------------------------------
    // Table
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $table;
    
    //--------------------------------------------------------------------------------------------------------
    // Table Name
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $tableName;
    
    //--------------------------------------------------------------------------------------------------------
    // String Query
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $stringQuery;
    
    //--------------------------------------------------------------------------------------------------------
    // Select Functions
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $selectFunctions;
    
    //--------------------------------------------------------------------------------------------------------
    // Column
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var array
    //
    //--------------------------------------------------------------------------------------------------------
    protected $column;

    //--------------------------------------------------------------------------------------------------------
    // Driver
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $driver;
    
    //--------------------------------------------------------------------------------------------------------
    // Construct
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param array $config
    //
    //--------------------------------------------------------------------------------------------------------
    public function __construct(Array $config = [])
    {
        \Requirements::initialize(['config' => 'Database']);
        
        $this->db = $this->_run();

        $this->prefix = $this->config['prefix'];
            
        if( empty($config) ) 
        {
            $config = $this->config;
        }
        
        $this->db->connect($config);
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Variable Types
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param  void
    //
    //--------------------------------------------------------------------------------------------------------
    public function vartypes() : Array
    {
        return $this->db->vartypes();
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Table
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $table
    //
    //--------------------------------------------------------------------------------------------------------
    public function table(String $table) : DatabaseCommon
    {
        $this->table = ' '.$this->prefix.$table.' ';
        $this->tableName = $this->prefix.$table;

        return $this;
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Column
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $col
    // @param mixed  $val
    //
    //--------------------------------------------------------------------------------------------------------
    public function column(String $col, $val) : DatabaseCommon
    {
        $this->column[$col] = $val;
        
        return $this;
    }
    
    //--------------------------------------------------------------------------------------------------------
    // String Query
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function stringQuery() : String
    {
        if( ! empty($this->stringQuery) )
        {
            return $this->stringQuery; 
        }
        
        return false;
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Different Connection
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param mixed $connectName
    //
    //--------------------------------------------------------------------------------------------------------
    public function differentConnection($connectName) : DatabaseCommon
    {   
        $config          = $this->config;
        $configDifferent = $config['differentConnection'];
        
        if( is_string($connectName) && isset($configDifferent[$connectName]) ) 
        {
            $connection = $configDifferent[$connectName];
        }
        elseif( is_array($connectName) )
        {
            $connection = $connectName; 
        }
        else
        {
            return \Exceptions::throws('Error', 'invalidInput', 'connectName'); 
        }
        
        foreach( $config as $key => $val )
        {
            if( $key !== 'differentConnection' )
            {
                if( ! isset($connection[$key]) )
                {
                    $connection[$key] = $val;
                }
            }
        }
        
        return new self($connection);
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Secure
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param array $data
    //
    //--------------------------------------------------------------------------------------------------------
    public function secure(Array $data) : DatabaseCommon
    {
        $this->secure = $data;
        
        return $this;
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Func
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param variadic $args
    //
    //--------------------------------------------------------------------------------------------------------
    public function func(...$args)
    {
        $array = \Arrays::removeFirst($args);
        $math  = $this->_math(isset($args[0]) ? mb_strtoupper($args[0]) : false, $array);
    
        if( $math->return === true )
        {
            return $math->args;
        }
        else
        {
            $this->selectFunctions[] = $math->args;
        
            return $this;
        }
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Error
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function error()
    {
        return $this->db->error(); 
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Close
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function close()
    { 
        return $this->db->close(); 
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Version
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function version()
    {
        return $this->db->version();    
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Desctruct
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function __destruct()
    {
        @$this->db->close();    
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Query Security
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $query
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _querySecurity($query)
    {   
        if( isset($this->secure) ) 
        {
            $secure = $this->secure;
            
            $secureParams = [];
            
            if( is_numeric(key($secure)) )
            {   
                $strex  = explode('?', $query); 
                $newstr = '';
                
                if( ! empty($strex) ) for( $i = 0; $i < count($strex); $i++ )
                {
                    $sec = isset($secure[$i])
                         ? $secure[$i]
                         : NULL;
                              
                    $newstr .= $strex[$i].$this->db->realEscapeString($sec);
                }

                $query = $newstr;
            }
            else
            {
                foreach( $this->secure as $k => $v )
                {
                    $secureParams[$k] = $this->db->realEscapeString($v);
                }
            }
            
            $query = str_replace(array_keys($secureParams), array_values($secureParams), $query);
        }
        
        $this->stringQuery = $query;
        
        $this->secure = [];

        return $query;
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Protected Math
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $type
    // @param array  $args
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _math($type, $args)
    {
        $type    = strtoupper($type);
        $getLast = \Arrays::getLast($args);
        
        $asparam = ' ';
        
        if( $getLast === true )
        {
            $args   = \Arrays::removeLast($args);
            $return = true;
            
            $as     = \Arrays::getLast($args);
            
            if( stripos(trim($as), 'as') === 0 )
            {
                $asparam .= $as;
                $args   = \Arrays::removeLast($args);
            }
        }
        else
        {
            $return = false;    
        }
            
        if( stripos(trim($getLast), 'as') === 0 )
        {
            $asparam .= $getLast;
            $args     = \Arrays::removeLast($args);
        }
        
        $args = $type.'('.rtrim(implode(',', $args), ',').')'.$asparam;
        
        return (object)array
        (
            'args'   => $args,
            'return' => $return
        );
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Run
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _run()
    {   
        $this->driver = explode(':', $this->config['driver'])[0];
    
        return $this->_drvlib('Driver');
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Driver Library
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $driver 
    // @param string $suffix
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _drvlib($suffix = 'Driver')
    {
        \Support::driver($this->drivers, $this->driver);

        return uselib('ZN\Database\Drivers\\'.$this->driver.$suffix);
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Nail Encode
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param  string $data
    //
    //--------------------------------------------------------------------------------------------------------
    protected function nailEncode($data)
    {
        return str_replace(["'", "\&#39;", "\\&#39;"], "&#39;", $data); 
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Run Query
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $query
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _runQuery($query)
    {
        return $this->db->query($this->_querySecurity($query), $this->secure);  
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Protected Run Exec Query
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $query
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _runExecQuery($query)
    {
        return $this->db->exec($this->_querySecurity($query), $this->secure);   
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected P
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param var    $p
    // @param string $name
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _p($p = NULL, $name = 'table')
    {
        if( $name === 'prefix' )
        {
            return $this->$name.$p;
        }

        if( $name === 'table' )
        {
            $p = $this->prefix.$p;
        }

        if( ! empty($this->$name) )
        {
            $data = $this->$name;

            $this->$name = NULL;

            return $data;
        }
        else
        {
            return $p;
        }
    }
}