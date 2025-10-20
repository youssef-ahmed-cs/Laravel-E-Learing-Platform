<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePhoneColumnToMobilePhoneInStudentsTable extends Migration{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
           //
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
}
