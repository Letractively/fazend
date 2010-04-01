<?php
/**
 * @version $Id: TraceabilityTestTest.php 1791 2010-04-01 15:52:35Z yegor256@gmail.com $
 */

require_once 'AbstractTestCase.php';

class FaZend_Cli_cli_PanTest extends AbstractTestCase
{
    
    public function testWeCanGetJSON()
    {
        $cwd = getcwd();
        chdir(APPLICATION_PATH . '/public');
        $result = shell_exec('php index.php Pan --pan=analysis 2>&1');
        chdir($cwd);

        $this->assertNotEquals(false, $result, "Empty result, why?");
        $json = Zend_Json::decode($result);
        
        logg('JSON returned: ' . count($json) . ': ' . cutLongLine($result));
    }

}
        