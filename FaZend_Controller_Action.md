# Flash redirector #

In your controller:

```
class MyController extends FaZend_Controller_Action {
    function indexAction() { 
        // ...
    }
    function doAction() { 
        // do something
        if ($doneSuccessfully)
            $this->_redirectFlash('successfully done');
    }
}
```

In your layout script:

```
<?=$this->flashMessage()?>
```

In your CSS you should define `p.flash` style.