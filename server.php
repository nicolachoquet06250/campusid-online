<?php

/**
 * DframeFramework
 * Copyright (c) Sławomir Kaleta
 *
 * @author Sławomir Kaleta <slaszka@gmail.com>
 * @license https://github.com/dframe/dframe/blob/master/LICENCE (MIT)
 */

/**
 * Usage:
 * php -S localhost:8083 -t web/ server.php
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($uri !== '/' and file_exists(__DIR__ . '/web' . $uri)) {
    return false;
}

require_once __DIR__ . '/web/index.php';


/*
 * Example de reflexion avec une classe
 *
 * $ref_class = new ReflectionClass(stdClass::class);
 * $ref_class->getDocComment();
 */