<?php namespace ZN\ViewObjects\Bootstrap\JSP;

use JQ, JS;

class Statements extends JSPExtends implements StatementsInterface
{
    //--------------------------------------------------------------------------------------------------------
    //
    // Author     : Ozan UYKUN <ozanbote@gmail.com>
    // Site       : www.znframework.com
    // License    : The MIT License
    // Copyright  : (c) 2012-2016, znframework.com
    //
    //--------------------------------------------------------------------------------------------------------

    public function if(String $condition, Callable $callback)
    {
        echo JS::if($condition, $callback);

        return $this;
    }

    public function elseif(String $condition, Callable $callback)
    {
        echo JS::elseIf($condition, $callback);

        return $this;
    }

    public function else(Callable $callback)
    {
        echo JS::else($callback);
    }

    public function switch(String $condition, Callable $callback)
    {
        $this->_statements($condition, $callback, 'switch');
    }

    public function case(String $condition, Callable $callback)
    {
        echo 'case ' . JQ::stringControl($condition) . ' :' . EOL;
        echo $callback() . EOL;

        return $this;
    }

    public function default(Callable $callback)
    {
        echo 'default:' . EOL;
        echo $callback() . EOL;

        return $this;
    }

    public function break()
    {
        echo JS::break();

        return $this;
    }

    public function return(String $data = NULL)
    {
        echo JS::return($data);
    }
}
