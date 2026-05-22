<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('submodulos')->where('slug', 'matriz-financiera')->delete();
    }

    public function down(): void
    {
        //
    }
};
