# Dynamic Exception Classes, Usage Example #

```
/**
 * Get file content in a string
 *
 * @param string Full file name
 * @return string Content of the given file
 * @throw FileNotFoundException If a given file is not found
 */
function getFileContent($fileName)
{
    if (!file_exists($fileName)) {
        FaZend_Exception::raise(
            'FileNotFoundException', 
            "File '{$fileName}' doesn't exist",
            'Exception'
        );
    }
    return file_get_contents($fileName);
}

/**
 * Outputs the content of log file
 * 
 * @param string Long line
 * @return void
 */
function echoLog($line)
{
    try {
        echo $this->getFileContent(self::LOGFILE);
    } catch (FileNotFoundException $e) {
        echo "no file";
    }
}
```

In this code snippet you raise an exception without actual class creation.

The benefits you get:
  * You don't need to create special class (and file) for the exception
  * You may create a class, FaZend\_Exception will find it automatically and will use
  * You don't use Exception class for all types of exception