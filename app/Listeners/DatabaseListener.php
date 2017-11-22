<?php

namespace App\Listeners;

use App\Util\Database\SqlFormatter;
use Illuminate\Database\Events\QueryExecuted;

class DatabaseListener
{
    public function __construct()
    {
    }

    public function handle(QueryExecuted $event)
    {
        app('log')->debug("DB-Query: \n" . SqlFormatter::format($event->sql, false));
        app('log')->debug("DB-Binds: ", $event->bindings);
    }
}
