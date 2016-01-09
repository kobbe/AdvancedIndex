# advancedIndex
Modification of the simple index (sindex) to handle other useful functions, ex linus tail command.

If you get an 500 internal error because of php_flag engine off, add AllowOverride Options to your apache settings

## Absolute Index
The absolute index takes a absolute path as input and shows the content of that path. 
This have the advantage that the index file can be located anywhere on the webserver and it is only the
.htaccess file that uses the index file. 

The .htaccess file works for the current folder and all subfolders. It is completely safe even despite the use of
_GET since it is the webserver that writes the content of the _GET (even if a user spacified a own path).

## Original Index

## Advanced Index
