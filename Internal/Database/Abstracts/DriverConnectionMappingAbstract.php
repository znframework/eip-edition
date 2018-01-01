<?php namespace ZN\Database\Abstracts;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

abstract class DriverConnectionMappingAbstract
{
    //--------------------------------------------------------------------------------------------------------
    // Config
    //--------------------------------------------------------------------------------------------------------
    //
    // @var array
    //
    //--------------------------------------------------------------------------------------------------------
    protected $config;

    //--------------------------------------------------------------------------------------------------------
    // Connect
    //--------------------------------------------------------------------------------------------------------
    //
    // @var callable
    //
    //--------------------------------------------------------------------------------------------------------
    protected $connect;

    //--------------------------------------------------------------------------------------------------------
    // Query
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $query;

    //--------------------------------------------------------------------------------------------------------
    // Connect
    //--------------------------------------------------------------------------------------------------------
    //
    // @param array $config
    //
    //--------------------------------------------------------------------------------------------------------
    abstract public function connect($config);

    //--------------------------------------------------------------------------------------------------------
    // Exec
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $query
    // @param array  $security
    //
    //--------------------------------------------------------------------------------------------------------
    abstract public function exec($query, $security);

    //--------------------------------------------------------------------------------------------------------
    // Query
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $query
    // @param array  $security
    //
    //--------------------------------------------------------------------------------------------------------
    abstract public function query($query, $security);

    //--------------------------------------------------------------------------------------------------------
    // Exec Query
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $query
    // @param array  $security
    //
    //--------------------------------------------------------------------------------------------------------
    public function multiQuery($query, $security){}

    //--------------------------------------------------------------------------------------------------------
    // Trans Start
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function transStart(){}

    //--------------------------------------------------------------------------------------------------------
    // Trans Roll Back
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function transRollback(){}

    //--------------------------------------------------------------------------------------------------------
    // Trans Commit
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function transCommit(){}

    //--------------------------------------------------------------------------------------------------------
    // Insert ID
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  void
    //
    //--------------------------------------------------------------------------------------------------------
    public function insertID(){}

    //--------------------------------------------------------------------------------------------------------
    // Column Data
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $column
    //
    //--------------------------------------------------------------------------------------------------------
    public function columnData($column){}

    //--------------------------------------------------------------------------------------------------------
    // Num Rows
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function numRows(){}

    //--------------------------------------------------------------------------------------------------------
    // Columns
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function columns(){}

    //--------------------------------------------------------------------------------------------------------
    // Num Fields
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function numFields(){}

    //--------------------------------------------------------------------------------------------------------
    // Real Escape String
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $data
    //
    //--------------------------------------------------------------------------------------------------------
    public function realEscapeString($data){}

    //--------------------------------------------------------------------------------------------------------
    // Error
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function error(){}

    //--------------------------------------------------------------------------------------------------------
    // Fetch Array
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function fetchArray(){}

    //--------------------------------------------------------------------------------------------------------
    // Fetch Assoc
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function fetchAssoc(){}

    //--------------------------------------------------------------------------------------------------------
    // Fetch Row
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function fetchRow(){}

    //--------------------------------------------------------------------------------------------------------
    // Affected Rows
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function affectedRows(){}

    //--------------------------------------------------------------------------------------------------------
    // Close
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function close(){}

    //--------------------------------------------------------------------------------------------------------
    // Version
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function version(){}

    //--------------------------------------------------------------------------------------------------------
    // Result
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $type
    //
    //--------------------------------------------------------------------------------------------------------
    public function result($type = 'object')
    {
        if( empty($this->query) )
        {
            return [];
        }

        $rows = [];

        while( $data = $this->fetchAssoc() )
        {
            if( $type === 'object' )
            {
                $data = (object) $data;
            }

            $rows[] = $data;
        }

        return $rows;
    }

    //--------------------------------------------------------------------------------------------------------
    // Result Array
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function resultArray()
    {
        return $this->result('array');
    }

    //--------------------------------------------------------------------------------------------------------
    // Row
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function row()
    {
        if( ! empty($this->query) )
        {
            $data = $this->fetchAssoc();

            return (object) $data;
        }
        else
        {
            return false;
        }
    }

    //--------------------------------------------------------------------------------------------------------
    // Variable Types
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  void
    //
    //--------------------------------------------------------------------------------------------------------
    public function vartypes()
    {
        return $this->variableTypes;
    }

    //--------------------------------------------------------------------------------------------------------
    // Var Type
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $type
    // @param mixed  $len
    // @param bool   $output: true -> $len(id), false -> $len id
    //
    //--------------------------------------------------------------------------------------------------------
    private function cvartype($type = NULL, $len = NULL, $output = true)
    {
        if( empty($len) )
        {
            return " $type ";
        }
        elseif( $output === true )
        {
            return " $type($len) ";
        }
        else
        {
            return " $type $len ";
        }
    }

    //--------------------------------------------------------------------------------------------------------
    // Operator
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $operator
    //
    //--------------------------------------------------------------------------------------------------------
    public function operator($operator = 'like')
    {
        $operator = strtolower($operator);

        return $this->operators[$operator] ?? false;
    }

    //--------------------------------------------------------------------------------------------------------
    // Statements
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $state
    // @param mixed  $len
    // @param bool   $output: true -> $len(id), false -> $len id
    //
    //--------------------------------------------------------------------------------------------------------
    public function statements($state = NULL, $len = NULL, $type = true)
    {
        $state = strtolower($state);

        if( $isstate = ($this->statements[$state] ?? NULL) )
        {
            if( strstr($isstate, '%') )
            {
                $vartype = str_replace('%', $len, $isstate);

                return $this->cvartype($vartype);
            }

            return $this->cvartype($isstate, $len, $type);
        }
        else
        {
            return false;
        }
    }

    //--------------------------------------------------------------------------------------------------------
    // Var Type
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $vartype
    // @param mixed  $len
    // @param bool   $output: true -> $len(id), false -> $len id
    //
    //--------------------------------------------------------------------------------------------------------
    public function variableTypes($vartype = NULL, $len = NULL, $type = true)
    {
        $vartype = strtolower($vartype);

        return   ! empty( $isvartype = ($this->variableTypes[$vartype] ?? NULL) )
                 ? $this->cvartype($isvartype, $len, $type)
                 : false;
    }

    //--------------------------------------------------------------------------------------------------------
    // End Connection
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function closeConnection()
    {
        $this->query   = NULL;
        $this->connect = NULL;
    }
}
