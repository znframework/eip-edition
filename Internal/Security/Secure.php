<?php namespace ZN\Security;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

ZN\Ability\Serialization;

class Secure
{
    use Serialization;
    
    const serialization = 
    [
        'class' => 'Guard',
        'start' => 'data',
        'end'   => 'get'
    ];
}
