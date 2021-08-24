<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('internal_id')->comment("ID");
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('job_type_id')->nullable();
            $table->bigInteger('staff_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('team_id')->nullable();
            $table->string('staff_code')->nullable()->comment('Người thực hiện');
            $table->string('name')->nullable()->comment("Tên nhiệm vụ");
            $table->string('description')->nullable()->comment("Mô tả nhiệm vụ");
            $table->string('tracking_staff', 2500)->nullable()->comment('Người theo dõi');
            $table->string('label')->nullable()->comment("Nhãn");
            $table->date('complete_time')->nullable()->comment("Thời gian hoàn thành");
            $table->date('last_update')->nullable()->comment("Cập nhật gần nhất");
            $table->string('tokuisakimei')->nullable()->comment("Tokuisakimei得意先名");
            $table->string('bukken_koodo')->nullable()->comment("Bukken koodo物件コード ");
            $table->string('kouji_koodo')->nullable()->comment('Kouji koodo工事コード ');
            $table->string('bukkenmei')->nullable()->comment('Bukkenmei 物件名');
            $table->string('hacchyuumoto')->nullable()->comment("Hacchyuumoto発注元");
            $table->string('kakou_koujou')->nullable()->comment("Kakou koujou加工工場");
            $table->string('shiten')->nullable()->comment("Shiten支店");
            $table->string('chiku')->nullable()->comment("Chiku地区");
            $table->string('tsubosuu')->nullable()->comment("Tsubosuu坪数 ");
            $table->string('hacchyuu_naiyou')->nullable()->comment("Hacchyuu naiyou発注内容");
            $table->string('menseki')->nullable()->comment("Menseki面積");
            $table->string('kaisuu')->nullable()->comment('Kaisuu階数');
            $table->string('shiyou_masuta')->nullable()->comment("Shiyou/ Masuta仕様/マスター ");
            $table->string('kouhou')->nullable()->comment('Kouhou工法');
            $table->string('cad')->nullable()->comment("CAD");
            $table->string('kiso')->nullable()->comment("Kiso基礎");
            $table->string('hagara')->nullable()->comment("Hagara羽柄");
            $table->date('kenzai')->nullable()->comment("Kenzai建材");
            $table->string('ichiji_enerugi')->nullable()->comment("Ichiji enerugi一次エネルギー消費量計算");
            $table->string('kishu')->nullable()->comment("Kishu機種");
            $table->string('tantousha')->nullable()->comment("Tantousha担当者");
            $table->date('uketsuke_hi')->nullable()->comment("Uketsuke hi受付日");
            $table->date('teisei_henkyaku_hi')->nullable()->comment("Teisei Henkyaku hi訂正返却日");
            $table->string('bikou')->nullable()->comment("Bikou備考");
            $table->string('hosanin')->nullable()->comment("Hosanin補佐人");
            $table->string('hosawariai')->nullable()->comment("Hosawariai補佐割合");
            $table->dateTime('kibou_nouki')->nullable()->comment("Kibou nouki希望納期 ");
            $table->date('henkyaku_hi')->nullable()->comment("Henkyaku hi返却日（日本時間）");
            $table->string('rank')->nullable()->comment("Rankランク");
            $table->integer('sagyou_jikan')->nullable()->comment(" Sagyou jikan作業時間 ");
            $table->string('nani_keisuu')->nullable()->comment("Nani keisuu難易係数");
            $table->string('kibo_keisuu')->nullable()->comment("Kibo keisuu規模係数");
            $table->string('naniten')->nullable()->comment("Naniten難易点");
            $table->string('chekkusha')->nullable()->comment("Chekkushaチェック者");
            $table->integer('chekkku_jikan')->nullable()->comment("Chekkku jikanチェック時間");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
