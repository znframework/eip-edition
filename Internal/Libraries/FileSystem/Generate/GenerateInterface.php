<?php namespace ZN\FileSystem;

interface GenerateInterface
{
    //--------------------------------------------------------------------------------------------------------
    // Grand Vision
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param mixed $database = NULL
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function grandVision($database = NULL);

    //--------------------------------------------------------------------------------------------------------
    // Settings
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param array $settings: empty
    //
    //--------------------------------------------------------------------------------------------------------
    public function settings(Array $settings) : InternalGenerate;

    //--------------------------------------------------------------------------------------------------------
    // Model
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $name: empty
    // @param array  $settings: empty
    //
    //--------------------------------------------------------------------------------------------------------
    public function model(String $name, Array $settings = []) : Bool;
    
    //--------------------------------------------------------------------------------------------------------
    // Controller
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $name: empty
    // @param array  $settings: empty
    //
    //--------------------------------------------------------------------------------------------------------
    public function controller(String $name, Array $settings = []) : Bool;
    
    //--------------------------------------------------------------------------------------------------------
    // Library
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $name: empty
    // @param array  $settings: empty
    // @param string $app : empty
    //
    //--------------------------------------------------------------------------------------------------------
    public function library(String $name, Array $settings = []) : Bool;
    
    //--------------------------------------------------------------------------------------------------------
    // Delete
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $name: empty
    // @param string $type: 'controller', 'model', 'library'
    //
    //--------------------------------------------------------------------------------------------------------
    public function delete(String $name, String $type = 'controller', String $app) : Bool;
}