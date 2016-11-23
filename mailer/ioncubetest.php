<?php

//
// ionCube Run Time Loading Compatibility Tester v1.0
//
// Copyright (c) ionCube.com 2002
//

$nl = ((php_sapi_name() == 'cli') ? "\n" : "<br>");

if (extension_loaded('ionCube Loader')) {
  echo "The ionCube loader is already installed and run time loading is unnecessary.$nl";
}

if (ini_get('safe_mode')) {
  echo "The server has PHP safe mode enabled. Run time loading will not be possible.$nl";
} elseif (!is_dir(realpath(ini_get('extension_dir')))) {
  echo "The setting of extension_dir in the php.ini file is not a directory. Run time loading will not be possible.$nl";
} else {
  echo "Run time loading is possible with your system.$nl";
}

?>
