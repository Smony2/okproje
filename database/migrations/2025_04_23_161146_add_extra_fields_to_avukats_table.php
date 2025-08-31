<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('avukats', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('avukats', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'tc_no', 'baro_no', 'baro_adi', 'avatar_url', 'adres', 'unvan',
                'aktif_mi', 'dogum_tarihi', 'cinsiyet', 'mezuniyet_universitesi',
                'mezuniyet_yili', 'uzmanlik_alani', 'puan', 'son_giris_at',
                'giris_sayisi', 'blokeli_mi', 'notlar'
            ]);
        });
    }
};
