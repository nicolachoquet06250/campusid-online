<?php

/**
 * DframeFramework
 * Copyright (c) Sławomir Kaleta.
 *
 * @license https://github.com/dframe/dframe/blob/master/LICENCE (MIT)
 */

namespace Dframe\custom\Asset\Exceptions;

/**
 * AsseticException Class.
 *
 * @author Sławomir Kaleta <slaszka@gmail.com>
 */
class AsseticException extends \Exception
{
    public function __construct($messages = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($messages, $code, $previous);
    }
}
