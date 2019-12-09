<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // SQL LOG
        DB::listen(function($query) {
          $sql = $query->sql;
          for ($i = 0; $i < count($query->bindings); $i++) {
            $sql = preg_replace("/\?/", $query->bindings[$i], $sql, 1);
          }

          Log::debug("SQL", ["time" => sprintf("%.2f ms", $query->time), "sql" => $sql]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
