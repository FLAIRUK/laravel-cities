<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    |
    | The in-memory lookup API (the Cities facade) works without a database.
    | These settings only apply if you publish the migration and seed the
    | cities into a table, e.g. so other tables can reference them.
    |
    */

    'table' => env('CITIES_TABLE', 'cities'),

    'connection' => env('CITIES_DB_CONNECTION'),

];
