<?php namespace ZN\ViewObjects\View\BS;

interface ButtonInterface
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
    // Button Link
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $url   = NULL
    // @param string $value = NULL
    //
    //--------------------------------------------------------------------------------------------------------
    public function buttonLink(String $url = NULL, String $value = NULL) : String;

    //--------------------------------------------------------------------------------------------------------
    // Button
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $name  = NULL
    // @param string $value = NULL
    //
    //--------------------------------------------------------------------------------------------------------
    public function button(String $name = NULL, String $value = NULL) : String;

    //--------------------------------------------------------------------------------------------------------
    // Submit
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $name  = NULL
    // @param string $value = NULL
    //
    //--------------------------------------------------------------------------------------------------------
    public function submit(String $name = NULL, String $value = NULL) : String;
}
