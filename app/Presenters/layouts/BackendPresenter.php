<?php

namespace App\Presenters\Layouts;

class BackendPresenter
{
    public function __construct()
    {
        benchmark()->checkpoint();
    }

    public function elapsedTime()
    {
        return benchmark()->getElapsedTime()->f;
    }

    public function ramUsage()
    {
        config(['benchmark.format_ram_usage'=> true]);
        return benchmark()->getPeakRamUsage();
    }
}
