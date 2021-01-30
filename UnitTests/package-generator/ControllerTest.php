<?php namespace ZN\Generator;

use Generate;

class ControllerTest extends Test\GeneratorExtends
{
    public function testController()
    {
        Generate::controller('Example', 
        [
            'extends'    => 'Controller',
            'functions'  => ['main', 'test']
        ]);

        $this->assertFileExists(CONTROLLERS_DIR . 'Example.php');
    }
}