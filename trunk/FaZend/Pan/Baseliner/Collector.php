<?php
/**
 * FaZend Framework
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available 
 * through the world-wide-web at this URL: http://www.fazend.com/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@fazend.com so we can send you a copy immediately.
 *
 * @copyright Copyright (c) FaZend.com
 * @version $Id$
 * @category FaZend
 */

/**
 * Collector of baseline tags
 *
 * @package Pan
 * @subpackage Baseliner
 */
class FaZend_Pan_Baseliner_Collector
{
    
    /**
     * Email to work with
     *
     * @var string
     **/
    protected $_email;
    
    /**
     * Shall we log all operations to STDOUT?
     *
     * @var boolean
     **/
    protected $_verbose;

    /**
     * Construct the class
     *
     * @param boolean Notify user by ECHO about the progress?
     * @return void
     */
    public function __construct($email, $verbose = true)
    {
        $this->_email = $email;
        $this->_verbose = $verbose;
    }

    /**
     * Collect information and return FaZend_Pan_Baseliner_Map
     *
     * @param string Path to work with
     * @return FaZend_Pan_Baseliner_Map
     */
    public function collect($path)
    {
        $map = new FaZend_Pan_Baseliner_Map($path, $this->_email);
        
        // find all files inside the application path
        foreach (
            new RegexIterator(
            new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)), '/\.php$/i') as $file) {
                
            $this->_log("{$file}:");
            require_once $file;
            
            try {
                $reflector = new Zend_Reflection_File(strval($file));
                $this->_parse($reflector, $map);
            
                foreach ($reflector->getClasses() as $classReflector) {
                    $this->_parse($classReflector, $map);
                    foreach ($classReflector->getMethods() as $methodReflector)
                        $this->_parse($methodReflector, $map);
                    // foreach ($classReflector->getProperties() as $propertyReflector)
                    //     $this->_parse($propertyReflector, $map);
                }
            
                foreach ($reflector->getFunctions() as $functionReflector) {
                    $this->_parse($functionReflector, $map);
                }
            } catch (ReflectionException $e) {
                $this->_log("\t" . get_class($e) . ": {$e->getMessage()}:");
            }
        }
        
        return $map;
    }
    
    /**
     * Parse reflection and add elements to map
     *
     * @return void
     **/
    protected function _parse(Reflector $reflector, FaZend_Pan_Baseliner_Map $map) 
    {
        try {
            $docBlock = $reflector->getDocblock();
        } catch (Zend_Reflection_Exception $e) {
            return;
        }
        
        foreach ($docBlock->getTags() as $tag) {
            // this is not for current email
            if (!preg_match('/^' . preg_quote($this->_email, '/') . '\s(.*)$/', $tag->getDescription(), $matches))
                continue;
                
            // invalid tag
            if (!preg_match('/^([\w\d]+)\s?\((.*?)\)(?:\s(.*))?$/', $matches[1], $matches)) {
                $this->_log("Error: invalid tag '{$tag->getDescription()}'");
                continue;
            }
                
            try {
                $map->add(
                    $reflector, 
                    $matches[1], 
                    explode(',', $matches[2]), 
                    isset($matches[3]) ? $matches[3] : false
                );
                $this->_log("\tadded: @{$tag->getName()} {$tag->getDescription()}");
            } catch (FaZend_Pan_Baseliner_Map_InvalidTag $e) {
                $this->_log("\tfailed: @{$tag->getName()} {$tag->getDescription()}: {$e->getMessage()}");
            }
        }
        
    }
    
    /**
     * Log one message
     *
     * @return void
     **/
    protected function _log($msg) 
    {
        if ($this->_verbose)
            echo $msg . "\n";
    }

}
