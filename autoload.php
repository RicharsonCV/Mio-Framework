<?php
/**
 * Automatically loads the specified file.
 *
 * @package Framework
 */
namespace framework;

/**
 * Automatically loads the specified file.
 *
 * Examines the fully qualified class name, separates it into components, then creates
 * a string that represents where the file is loaded on disk.
 *
 * @package framework;
 */
spl_autoload_register(
    function ($filename) {
        
        // First, separate the components of the incoming file.
        $file_path = explode('\\', $filename);
        
        // Get the last index of the array. This is the class we're loading.
        if (isset($file_path[count($file_path) - 1])) {
            $class_file = $file_path[count($file_path) - 1];
            $class_file = "$class_file.php";
        }

        /**
        * Find the fully qualified path to the class file by iterating through the $file_path array.
        * We ignore the first index since it's always the top-level package. The last index is always
        * the file so we append that at the end.
        */
    
        $fully_qualified_path = trailingslashit(__DIR__);
        
        /**
        * We start at the second index of the namespace because our directory
        * structure does not include 'McFarlin/TRP'.
        */
         
        for ( $i = 1; $i < count($file_path) - 1; $i++ ) {
            $dir = $file_path[ $i ];

            $fully_qualified_path .= trailingslashit($dir);
        }

        $fully_qualified_path .= $class_file;
        
        // Now we include the file.
        if (file_exists($fully_qualified_path)) {
            require $fully_qualified_path;
        }
    }
);