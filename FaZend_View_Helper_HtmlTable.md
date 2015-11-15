# Sample usage #

Inside your view script:

```
<?=$this->htmlTable()
    ->hideColumn('id')
    ->setColumnTitle('email', 'User email')
    ->addColumnLink('email', 'id', 'id', array('action'=>'edit'))
    ->addConverter('password', create_function('$value, $row', 'return "***";'))
    ->addColumnStyle('email', "width: 120px;")
    ->addConverter('email', 'Model_Email')
    ->allowRawHtml('details')
    ->addOption('del', 'id', 'id', array('action'=>'del'))
    ->skipOption('del', create_function('$row', 'return !$row->isAdmin();'))
    ->setPaginator($this->paginator)
    ?>
```

In HTML the table will look like (In your CSS you should define its proper classes):

```
<table>
  <thead>
    <tr>
      <th>..</th>
    </tr>
  </thead>
  <tbody>
    <tr class='odd'> <!-- or "even" or "highlight" -->
      <td>..</td>
    </tr>
  </tbody>
</table>
```

It's possible to use anonymous functions, as in PHP 5.3+:

```
<?=$this->htmlTable()->addConverter('password', function($value, $row) { return "***"; }) ?>
```

## Setting order of columns ##

To change the order of columns you should ask helper to show them in the right order:

```
<?=$this->htmlTable()->showColumns(array('id', 'name', 'age')) ?>
```



# Options (full reference) #

`setPaginator(Zend_Paginator $paginator)` sets data holder to render.

`hideColumn($column)` hides given column, it won't be displayed in the table. By default the table will show all columns received from the paginator.

`appendOptionToColumn($option, $column)` moves given option to the given column. By default all options are visible at the end of the row, in a special "options" column. Using this method you can move any option to the column.

`addOption($title, $httpVar, $column, array $urlParams)` adds an option to every row. `$title` is a unique option title which will be used later for the reference to this option. `$httpVar` is a name of parameter to be given to the `url()` method when calculating the URL of the option. `$column` is the name of column where to get data for the parameter to be passed to `url()`. `$urlParams` is a list of parameters to be explicitly specified in `url()`.

`addColumn($title, $predecessor)` adds new column, where `$title` is a name of property in the row, `$predecessor` is a name of the column that should precede this one.

`skipOption($title, $callback)` instructs the helper to skip certain option by name `$title` when `$callback` returns `true`. This callback will be executed for every row. It will receive one parameter `$row` and should return boolean.

`addColumnStyle($column, $style)` adds HTML `style` attribute to the given column `$column`.

`setColumnTitle($column, $title)` changes default column title to the given `$title` value. By default column title is equal to the name of field in the row.

`allowRawHtml($column)` disables HTML escaping in the given column. By default the value from the paginator are passed through `escape()` method of the View. Using this method you can disable such an escaping. Be careful with this option.

`setNoDataMessage($msg)` changes the default `no data` message to the custom one. This message is visible when the paginator has no data to render.

`addColumnLink($title, $httpVar, $column, array $urlParams)` adds a link right to the column, using the same style of link specification as `addOption()`. The link will be located on the entire text of the given column (`$title`).

`addConverter($column, $class, $method)` adds new converter to the column value. The converter could be one of the following:

  * a scalar type name: `integer`, `boolean`, `string`, or `float`
  * a `callback`
  * a string starting with `->`, which will be attached to the value to get it's sub-element
  * name of the class
  * name of the class and name of the static method in this class.