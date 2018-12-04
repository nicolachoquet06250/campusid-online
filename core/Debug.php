<?php

/**
 * DframeFramework
 * Copyright (c) Sławomir Kaleta.
 *
 * @license https://github.com/dframe/dframe/blob/master/LICENCE (MIT)
 */

namespace Dframe\custom;

/**
 * Debug Class.
 *
 * @author Sławomir Kaleta <slaszka@gmail.com>
 */
class Debug extends \Dframe\Debug {
    private $headers = [];

    public function addHeader($headers)
    {
        $this->headers = array_unique(array_merge($this->headers, $headers));

        return $this;
    }

    public function getHeader()
    {
        return $this->headers;
    }
}
