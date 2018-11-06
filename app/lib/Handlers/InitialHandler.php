<?php
namespace App\lib;

class InitialHandler
{
    function __construct(
        MVDB $mvdb
    )
    {
        $this->client = $mvdb;
    }

    public function init()
    {
        var_dump('34234fdfl;kdsf');
    }
}