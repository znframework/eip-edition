<?php namespace ZN\Requirements\System;

use Arrays, Json, Crontab;
use ZN\Core\Structure;

class Zerocore
{
    //--------------------------------------------------------------------------------------------------------
    //
    // Author     : Ozan UYKUN <ozanbote@gmail.com>
    // Site       : www.znframework.com
    // License    : The MIT License
    // Copyright  : (c) 2012-2016, znframework.com
    //
    //--------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------
    // Protected Project
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected static $project = DEFAULT_PROJECT;

    //--------------------------------------------------------------------------------------------------------
    // Protected Project
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected static $command;

    //--------------------------------------------------------------------------------------------------------
    // Protected Parameters
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected static $parameters;

    //--------------------------------------------------------------------------------------------------------
    // Protected Default Variables
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public static function commander($commands)
    {
        report('TerminalCommands', implode(' ', $commands), 'TerminalCommands');

        $commands = Arrays::removeFirst($commands);

        if( ($commands[0] ?? NULL) !== 'project-name' )
        {
            $commands = Arrays::addFirst($commands, DEFAULT_PROJECT);
            $commands = Arrays::addFirst($commands, 'project-name');
        }

        self::$project = $commands[1] ?? DEFAULT_PROJECT;
        $command       = $commands[2] ?? NULL;
        self::$command = $commands[3] ?? NULL;

        if( $command === NULL )
        {
            self::_commandList(); exit;
        }

        $parameters = Arrays::removeFirst($commands, 4);

        self::$parameters = Arrays::forceValues($parameters, function($data)
        {
            $return = $data;

            if( Json::check($return) )
            {
                $return = Json::decodeArray($return);
            }

            return $return;
        });

        echo self::_output();

        switch( $command )
        {
            case 'run-uri'              :
            case 'run-controller'       : self::_runController();                       break;
            case 'run-model'            :
            case 'run-class'            : self::_runClass();                            break;
            case 'run-cron'             : self::_runCron();                             break;
            case 'cron-list'            : echo Crontab::list();                         break;
            case 'run-command'          : self::_runClass(PROJECT_COMMANDS_NAMESPACE);  break;
            case 'run-external-command' : self::_runClass(EXTERNAL_COMMANDS_NAMESPACE); break;
            case 'run-function'         : self::_runFunction();                         break;
            case 'command-list'         :
            default                     : self::_commandList();
        }

        echo EOL . self::_line();
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Line
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    protected static function _line()
    {
        return '--------------------------------------------------------------------' . EOL;
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Output
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $message
    //
    //--------------------------------------------------------------------------------------------------------
    protected static function _output($message = 'OUTPUT')
    {
        $str  = self::_line();
        $str .= '| ' . $message . EOL;
        $str .= self::_line();

        return $str;
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Command List
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    protected static function _commandList()
    {
        echo self::_output('COMMAND LIST');

        echo implode
        (EOL, [
            'Command Name            Usage of Example' . EOL,
            'run-uri                 run-uri controller/function/p1/p2/.../pN',
            'run-controller          run-controller controller/function/p1/p2/.../pN',
            'run-model               run-model model:function p1 p2 ... pN',
            'run-class               run-class class:function p1 p2 ... pN',
            'run-cron                run-cron controller/method func param func param ...',
            'run-cron                run-cron command:method func param func param ...',
            'cron-list               Cron Job List',
            'run-command             run-command command:function p1 p2 ...pN',
            'run-external-command    run-command command:function p1 p2 ...pN',
            'run-function            run-function function p1 p2 ... pN'
        ]);

        echo EOL . self::_line();
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Run Controller
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    protected static function _runController()
    {
        $datas      = Structure::data(self::$command);
        $page       = $datas['page'];
        $function   = $datas['function'] ?? 'main';
        $namespace  = $datas['namespace'];
        $parameters = $datas['parameters'];
        $file       = $datas['file'];
        $class      = $namespace . $page;

        import($file);

        self::_result(uselib($class)->$function(...$parameters));
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Run Cron
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    protected static function _runCron()
    {
        $parameters = self::$parameters;

        for( $index = 0, $rindex = 1; $index < count($parameters); $index += 2, $rindex += 2 )
        {
            $func = $parameters[$index]  ?? NULL;
            $prm  = $parameters[$rindex] ?? NULL;
            Crontab::$func($prm);
        }

        if( strstr(self::$command, '/') )
        {
            Crontab::controller(self::$command);
        }
        else
        {
            Crontab::commandFile(self::$command);
        }

        echo Crontab::run();
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Result
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $result
    //
    //--------------------------------------------------------------------------------------------------------
    protected static function _result($result)
    {
        if( $result === true || $result === NULL )
        {
            echo lang('Success', 'success');
        }
        elseif( $result === false )
        {
            echo lang('Error', 'error');
        }
        else
        {
            if( is_array($result) )
            {
                print_r($result);
            }
            else
            {
                echo $result;
            }
        }
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Run Class
    //--------------------------------------------------------------------------------------------------------
    //
    // @param array $parameters
    //
    //--------------------------------------------------------------------------------------------------------
    protected static function _runClass($namespace = NULL)
    {
        self::_classMethod($class, $method);

        $className = $namespace . $class;

        self::_result(uselib($className)->$method(...self::$parameters));
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Run Function
    //--------------------------------------------------------------------------------------------------------
    //
    // @param array $parameters
    //
    //--------------------------------------------------------------------------------------------------------
    protected static function _runFunction()
    {
        $method = self::$command;

        self::_result($method(...self::$parameters));
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Class Method
    //--------------------------------------------------------------------------------------------------------
    //
    // @param array $parameters
    //
    //--------------------------------------------------------------------------------------------------------
    protected static function _classMethod(&$class = NULL, &$method = NULL)
    {
        $commandEx = explode(':', self::$command);
        $class     = $commandEx[0];
        $method    = $commandEx[1] ?? NULL;
    }
}

class_alias('ZN\Requirements\System\Zerocore', 'Zerocore');
