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

class FaZend_Pan_Polisher_Fixers_LicenseTest extends AbstractTestCase
{
    
    public static function providerPhpSources()
    {
        $result = "<?php
/**
 * license
 *
 * my two lines
 * license
 *
 * @param test
 */

somecode();
";

        return array(
            array(
                "<?php
/**
 * My project
 * 
 * Some license
 *
 * @param test
 */

somecode();
", $result),

            array(
                "<?php
/**
 * Not enough lines...
 *
 * @param test
 */

somecode();
", $result),

            array(
                "<?php\t
/**\t
 *
 *
 * Some line, which
 * is not correct now
 *\t
 *\t 
 * Another block of text, 
 * which is not correct again
 *\t
 * @param test
 */\t

somecode();
", $result),
        );
    }
    
        public static function providerPhtmlSources()
        {
            $result = "<!--
 *
 * phtml license
 *
 * some license text
 * in two lines
 *
 * @param test
 -->

<p>Test</p>
";

            return array(
                array(
                    "<!--
 *
 * My project
 * 
 * Some license
 *
 * @param test
 -->

<p>Test</p>
", $result),
            );
        }

    /**
     * @dataProvider providerPhpSources
     */
    public function testLicenseCanBeFixedInPhp($origin, $result)
    {
        $fixer = new FaZend_Pan_Polisher_Fixer_License();
        $fixer->setLicense('license', array('my two lines', 'license'));
        
        $fixer->fix($origin, 'php');
        $this->assertEquals($origin, $result, "Failed to process, returned:\n$origin");
    }

    /**
     * @dataProvider providerPhtmlSources
     */
    public function testLicenseCanBeFixedInPhtml($origin, $result)
    {
        $fixer = new FaZend_Pan_Polisher_Fixer_License();
        $fixer->setLicense('phtml license', array('some license text', 'in two lines'));
        
        $fixer->fix($origin, 'phtml');
        $this->assertEquals($origin, $result, "Failed to process, returned:\n$origin");
    }

}
