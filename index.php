<?php

include_once 'config/index.php';
include_once 'models/response.model.php';
include_once 'controllers/db.controller.php';
include_once 'controllers/usuario.controller.php';
include_once 'models/usuario.model.php';

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', './php_error_log');

include "routes/index.php";
