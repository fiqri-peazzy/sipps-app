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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('berat_total')->default(0)->after('total_item');
            $table->unsignedBigInteger('province_id')->nullable()->after('provinsi');
            $table->unsignedBigInteger('city_id')->nullable()->after('province_id');
            $table->unsignedBigInteger('district_id')->nullable()->after('city_id');
            $table->unsignedBigInteger('subdistrict_id')->nullable()->after('district_id');
            $table->unsignedBigInteger('kota_id')->nullable()->after('kota');
            $table->unsignedBigInteger('provinsi_id')->nullable()->after('provinsi');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'berat_total',
                'province_id',
                'city_id',
                'district_id',
                'subdistrict_id',
                'kota_id',
                'provinsi_id'
            ]);
        });
    }
};
