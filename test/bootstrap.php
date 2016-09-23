<?php
include "pylon/pylon.php" ;
XSetting::$logMode   = XSetting::LOG_DEBUG_MODE ;
XSetting::$prjName   = "plato" ;
XSetting::$logTag    = XSetting::ensureEnv("USER") ;
XSetting::$runPath   = XSetting::ensureEnv("RUN_PATH") ;
XSetting::$bootstrap = "pylonstrap.php" ;
XSetting::setupModel();

plato\libs\Git::set_bin($_SERVER['GIT_BIN']);