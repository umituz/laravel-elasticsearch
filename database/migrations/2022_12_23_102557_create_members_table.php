<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('isim');
            $table->string('soyad');
            $table->string('cinsiyet');
            $table->string('ulke');
            $table->string('ip_address');
            $table->string('konum');
            $table->string('dogum_tarihi');
            $table->string('yas');
            $table->string('ekleme_tarihi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
