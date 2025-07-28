<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'street_number')) { 
                $table->string('street_number')->nullable();
            }
            if (!Schema::hasColumn('patients', 'barangay')) { 
                $table->string('barangay')->nullable();
            }
            if (!Schema::hasColumn('patients', 'city')) { 
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('patients', 'province')) { 
                $table->string('province')->nullable();
            }
            if (!Schema::hasColumn('patients', 'zip_code')) { 
                $table->string('zip_code')->nullable();
            }
            if (!Schema::hasColumn('patients', 'country')) { 
                $table->string('country')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'street_number')) {
                $table->dropColumn('street_number');
            }
            if (Schema::hasColumn('patients', 'barangay')) {
                $table->dropColumn('barangay');
            }
            if (Schema::hasColumn('patients', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('patients', 'province')) {
                $table->dropColumn('province');
            }
            if (Schema::hasColumn('patients', 'zip_code')) {
                $table->dropColumn('zip_code');
            }
            if (Schema::hasColumn('patients', 'country')) {
                $table->dropColumn('country');
            }
        });
    }
};
