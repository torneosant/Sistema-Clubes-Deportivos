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
    Schema::create('partido_jugadores', function (Blueprint $table) {

        $table->id();

        $table->foreignId('partido_id')
            ->constrained('partidos')
            ->cascadeOnDelete();

        $table->foreignId('jugador_id')
            ->constrained('jugadores')
            ->cascadeOnDelete();

        // Participación
        $table->boolean('titular')->default(false);

        $table->integer('minutos')->default(0);

        // Estadísticas
        $table->integer('goles')->default(0);

        $table->integer('asistencias')->default(0);

        $table->integer('amarillas')->default(0);

        $table->integer('rojas')->default(0);

        // Reconocimiento
        $table->boolean('figura')->default(false);

        // Observaciones
        $table->text('observaciones')->nullable();

        $table->timestamps();

        $table->unique(['partido_id', 'jugador_id']);
    });
}
        /**
         * Reverse the migrations.
         */
        public function down(): void
    {
        Schema::dropIfExists('partido_jugadores');
    }
    };
