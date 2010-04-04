<?php
/**
 * @version $Id$
 */

require_once 'AbstractTestCase.php';

class FaZend_Controller_AdmControllerTest extends AbstractTestCase
{

    public function testAllUrlsWork()
    {
        $this->dispatch($this->view->url(array('action'=>'squeeze'), 'adm', true));
    }

    public function testCustomActionWorks()
    {
        $this->dispatch($this->view->url(array('action'=>'custom'), 'adm', true));
        $this->assertQuery('p.ok', "Failed to run custom action");
    }

}
