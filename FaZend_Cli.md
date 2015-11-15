# Usage sample #

You have some tasks to be performed from command line (routine operations for example). Now you can easily automate them. Just create a class in `application/cli` directory and inherit it from `FaZend_Cli_Abstract`:

```
class ArchiveFile extends FaZend_Cli_Abstract
{
    /**
     * To be executed from command line
     *
     * @return int exit code
     */
    public function execute()
    {
        $file = $this->_get('file');
        // do some operations
        if ($this->_get('log', false)) {
            echo 'File ' . $file . ' was processed';
        }
        return self::RESULTCODE_OK;
    }
}
```

Now you can do this from command line or from your `crontab`:

```
php /home/project/public_html/public/index.php ArchiveFile --file="test.tgz" --log
```

That's it.