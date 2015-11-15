Read more in php|Architect, February 2010, article named "[FaZend: Object Relational Mapping](http://www.phparch.com/magazine/2010/february/)"

# Assumptions #

We assume that:

  * All tables have one-column primary key, or column 'id'
  * Foreign keys are named the same way as referenced tables

# Active Record #

For example, you have two tables in the DB (Sqlite):

```
CREATE TABLE owner (
  id INT NOT NULL PRIMARY KEY AUTOINCREMENT, 
  name VARCHAR(50) NOT NULL
)

CREATE TABLE product (
  id INT NOT NULL PRIMARY KEY AUTOINCREMENT, 
  text VARCHAR(1024) NOT NULL, 
  owner INT NOT NULL CONSTRAINT fk_product_owner REFERENCES owner(id)
)
```

Start with bootstrapping the ORM (in your `Bootstrap.php`):

```
$this->bootstrap('fazend');
$this->bootstrap('fz_orm');
```

You declare two PHP classes (text after `FaZend_Db_Table_ActiveRow_` is the name of your table):

```
class Model_Owner extends FaZend_Db_Table_ActiveRow_owner {}
class Model_Product extends FaZend_Db_Table_ActiveRow_product {}
```

Now, in order to get an active row from the table:

```
$owner = new Model_Owner(13);
```

To work with it, save, update, etc:

```
$product = new Model_Product();
$product->text = 'just test';
$product->owner = $owner;
$product->save();
```

You can get other active rows from this row:

```
$product = new Model_Product(10);
$name = $product->owner->name;
```

Or you can find a new row with `retrieve()` method:

```
$product = Model_Product::retrieve()
    ->where('owner = ?', $owner)
    ->setSilenceIfEmpty() // don't throw exception if not found, just return FALSE
    ->setRowClass('Model_Product') // returned rows will be instances of this class
    ->fetchRow();
```

# List of Active Records #

You can get a row set in a convenient way:

```
$list = Model_Owner::retrieve()
  ->where('name IS NOT NULL')
  ->order('id DESC')
  ->fetchAll();
```

Or, for example:

```
FaZend_Db_ActiveTable_otherTable::retrieve()
  ->table()
  ->update('time = NOW()');
```

More extended example:

```
$list = Model_Owner::retrieve(false) // don't use default FROM clause
    ->from('owner', array('name')) // specify where to select FROM
    ->where('name > ?', new Zend_Db_Expr('NOW()')) // use Zend syntax
    ->setRowClass('Model_OwnerName') // set class name of each returned row
    ->fetchOne(); // select just one column
```

You can use dynamic binding:

```
$list = Model_Owner::retrieve()
    ->where('name LIKE :pattern')
    ->orWhere('id IN :ids')
    ->fetchAll(
        array(
            'pattern'=>'[[:alnum:]]', 
            'ids' => array(1, 3, 45, 98)
        )
    );
```

## Delayed `fetchAll()` ##

Important improvement made to the standard method `fetchAll()`. Now you can do this operation with big amounts of data:

```
$rowset = FaZend_Db_ActiveTable_bigTable::retrieve()->fetchAll();
$count = count($rowset);
```

Even if your `$rowset` is huge, this operation will be done fast and memory-safe. Method `fetchAll()` won't download any data to memory until you actually access them. Method `count()` doesn't work with any particular row in the set, so nothing will be downloaded. Instead, new SQL query will be created on-fly, which will look like:

```
SELECT COUNT(*) FROM (...)
```

Where `...` will be replaced by your original SQL query. All this is done on-fly.

# Class Mapping #

In you bootstrap add this line:

```
FaZend_Db_Table_ActiveRow::addMapping(
    '/^product\.created$/', 
    FaZend_Callback::factory(
        'new Zend_Date(${a1}, Zend_Date::ISO_8601);'
    )
);
```

Now you will get `Zend_Date` directly from DB, i.e.:

```
$product = new Model_Product(23); // get object from DB by primary key ID
echo $product->created->get(Zend_Date::DATE_MEDIUM);
```

# More examples #

There are a few examples of complex queries (we recommend to format them like in the examples):

```
/**
 * Retrieve all messages for the given user.
 *
 * @param Model_User The user to match
 * @return Model_Message[]
 */
public static function retrieveByUser(Model_User $user)
{
    $db = self::retrieve()->table()->getDefaultAdapter();
    $query = self::retrieve()
        ->columns(
            array(
                'attachments' => new Zend_Db_Expr('SUM(attachment.*)')
            )
        )
        ->joinLeft(
            'attachment', 
            $db->quoteInto(
                'attachment.message = message.id AND message.user = ?', $user
            ),
            array()
        )
        ->having('attachments > 0')
        ->setRowClass('Model_Message');
    if ($user->isAdmin()) {
        $query
            ->reset(Zend_Db_Select::HAVING)
            ->where('priority > 3');
    }
    return $query->fetchAll();
```

Another example:

```
/**
 * Retrieve full list of user names, that match the pattern.
 *
 * @param string The pattern to match
 * @return string[]
 */
public static function retrieveNamesByPattern($pattern)
{
    return self::retrieve()
        ->reset(Zend_Db_Select::COLUMNS)
        ->columns('name')
        ->where('name LIKE ?', '%' . $pattern . '%')
        ->limit(5)
        ->fetchCol();
}
```

One more example with a complex JOIN:

```
SELECT 
  entity.*, 
  COUNT(DISTINCT similar.tag) AS shared 
FROM entity 
INNER JOIN 
  (tagged AS this INNER JOIN tagged AS similar USING (tag)) 
  ON similar.entity = entity.id
WHERE this.entity = 123
AND entity.id != this.entity
GROUP BY entity.id
ORDER BY shared DESC
```

should look like this in FaZend:

```
self::retrieve()
    ->columns(array('shared' => new Zend_Db_Expr('COUNT(DISTINCT similar.tag)')))
    ->join(
        array('this' => 'tagged'),
        'similar.entity = entity.id',
        array()
    )
    ->join(
        array('similar' => 'tagged'),
        new Zend_Db_Expr('USING(tag)'),
        array()
    )
    ->where('this.entity = ?', 123)
    ->where('entity.id != this.entity')
    ->group('entity.id')
    ->order('shared DESC');
}
```