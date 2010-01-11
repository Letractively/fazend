<?php
/**
 *
 * Copyright (c) FaZend.com
 * All rights reserved.
 *
 * You can use this product "as is" without any warranties from authors.
 * You can change the product only through Google Code repository
 * at http://code.google.com/p/fazend
 * If you have any questions about privacy, please email privacy@fazend.com
 *
 * @copyright Copyright (c) FaZend.com
 * @version $Id$
 * @category FaZend
 */

require_once 'AbstractTestCase.php';

class FaZend_Cli_cli_BaselineTest extends AbstractTestCase
{
    
    public function testWeCanBaselineOurCode()
    {
        chdir(APPLICATION_PATH . '/public');
        $result = shell_exec('php index.php Baseline --email=team@fazend.com --dry-run 2>&1');

        $this->assertNotEquals(false, $result, "Empty result, why?");
        
        logg('Baseline returned: ' . $result);
    }

}
        