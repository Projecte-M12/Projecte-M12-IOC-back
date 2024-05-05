<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['service_id']); // Elimina la restricción de clave foránea
            $table->dropColumn('service_id'); // Elimina la columna
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->string('service_id')->after('provider_id'); // Añade la nueva columna de tipo string
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('service_id'); // Elimina la nueva columna
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->after('provider_id'); // Añade la columna original
        });
    }
};
?>
