<?php

$config = require 'config.php';

$pdo = Connection::make($config['database']);

return new QueryBuilder($pdo);  