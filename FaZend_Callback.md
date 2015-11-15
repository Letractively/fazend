# Usase Sample #

Imagine you need to instruct your class about some behavior that it has to call, when necessary. There are a few options in PHP to do it:

  * pass PHP string to `eval()`
  * create new function with `create_function()` and pass it as parameter
  * instruct to call some method in some class, giving its details as `array()`
  * create lambda function (PHP5.3 only)

All such options are wrapped in one class `FaZend_Callback` in order to abstract the caller from the options selected by the behavior provider. Here we define a `Renderer` class, that is responsible for rendering of an array of some items. This class doesn't know how exactly to render each element, but it relies on the callback injected:

```
class Renderer
{
    protected $_callback;
    public function factory($callback)
    {
        $renderer = new self();
        $renderer->_callback = FaZend_Callback::factory($callback);
        return $renderer;
    }
    public function render(array $items)
    {
        $html = '';
        foreach ($items as $item) {
            $html .= $this->_callback->call($item);
        }
        return $html;
    }
}
```

Now you can use this class in different ways:

```
$dates = array(
    new Zend_Date('10/12/2010'),
    new Zend_Date('3/7/2009'),
    Zend_Date::now(),
);

// using eval()
echo Renderer::factory('${a1}->get(Zend_Date::MONTH)')->render($dates);

// using create_function()
echo Renderer::factory(
    create_function(
        '$date',
        'return $date->get(Zend_Date::MONTH)'
    )
)
->render($dates);

// using class method, will always return now()
echo Renderer::factory(
    array(
        'Zend_Date', // class name, or it could be an object
        'now' // method name, static only
    )
)
->render($dates);

// using lambda function, PHP 5.3+
echo Renderer::factory(
    function ($date)
    {
        return $date->get(Zend_Date::MONTH);
    }
)
->render($dates);
```

As you see, in `Renderer` you don't have to worry about the callback format, you just call it passing some variables into it.

`FaZend_Callback` is used in:

  * FaZend\_View\_Helper\_HtmlTable: for formatters and converters
  * FaZend\_View\_Helper\_Forma: for converters
  * FaZend\_Db\_Table\_ActiveRow: for class mapping

# Injections #

Sometimes you need callback to have links to other objects, not only to the parameters sent to it. For example you want to create a callback, which will convert date to certain format, predefined:

```
echo Renderer::factory(
    FaZend_Callback::factory('${a1}->get(${i1})')
    ->inject(Zend_Date::MONTH)
)
->render($dates);
```

In the example above `${i1}` will be replaced by the first injected parameter (`Zend_Date::MONTH`). This mechanism is very useful if you need your callback to access some "global" variables, and change them, e.g.:

```
$max = null;
echo Renderer::factory(
    FaZend_Callback::factory(
        '
        ${a1}->get(Zend_Date::MONTH);
        if (${a1}->isLater(${i1})) {
            ${i1}->set(${a1});
        }
        '
    )
    ->inject($max)
)
->render($dates);
echo "Maximum date is: {$max}";
```

# Callbacks in `htmlTable()` view helper #

You can use callbacks in `htmlTable()` view helper, in order to change types of cells or formatting of cells/rows:

```
<?=$this->htmlTable()
    ->setIterator($dates)
    ->showColumns(null)
    ->addColumn('get')
    ->setColumnTitle('Month')
    ->addConverter('get', '${a1}->get(Zend_Date::MONTH)')
    ->addFormatter('${a2}->isEarlier(Zend_Date::now())', 'color:red')
    ?>
```

# Callbacks in `forma()` view helper #

You can use callbacks in `forma()` view helper, in order to change types of input values:

```
<?=$this->forma()
    ->addBehavior('nothing')
    ->addField('text', 'a1')
        ->fieldAttrib('maxlength', 20)
        ->fieldAttrib('size', 15)
        ->fieldLabel('Date Of Birth')
        ->fieldConverter('new Zend_Date(${a1})')
    ->addField('submit', 'submit')
        ->fieldValue('Set Date of Birth')
        ->fieldAction(array($user, 'setDateOfBirth'))
    ?>
```

Two callbacks are used: one for field conversion to `Zend_Date` type, and second for `setDateOfBirth()` calling.