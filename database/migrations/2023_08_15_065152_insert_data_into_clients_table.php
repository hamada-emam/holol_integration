<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            DB::table('clients')->insertUsing(
                ['company_code', 'client_code', 'type_code', 'url', 'token'],
                function ($query) {
                    $query->select(['company_code', 'client_code', 'type_code', 'url', 'token'])
                        ->from('settings')
                        ->whereNotNull('type_code')
                        ->whereNotNull('url')
                        ->whereNotNull('token');
                }
            );
        });
        // delete settings table 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // You may need to truncate or delete the inserted data here
    }
};
