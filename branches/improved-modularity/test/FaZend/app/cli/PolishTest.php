<?php
/**
 * @version $Id: TraceabilityTestTest.php 1791 2010-04-01 15:52:35Z yegor256@gmail.com $
 */

require_once 'AbstractTestCase.php';

class FaZend_Cli_cli_PolishTest extends AbstractTestCase
{
    
    public function testWeCanMakeDryRun()
    {
        chdir(APPLICATION_PATH . '/public');
        $result = shell_exec('php index.php Polish --dry-run 2>&1');

        $this->assertNotEquals(false, $result, "Empty result, why?");
        logg('Polisher returned: ' . $result);
    }

}
        