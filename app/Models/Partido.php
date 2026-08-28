<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Partido extends Model
    {
        use HasFactory;

        protected $fillable = [
            'club_id',
            'equipo_id',
            'categoria_id',
            'fecha',
            'hora',
            'competencia',
            'rival',
            'lugar',
            'condicion',
            'goles_favor',
            'goles_contra',
            'estado',
            'observaciones',
            'competencia_id',
        ];

        public function club()
        {
            return $this->belongsTo(Club::class);
        }

        public function equipo()
        {
            return $this->belongsTo(Equipo::class);
        }

        public function categoria()
        {
            return $this->belongsTo(Categoria::class);
        }

        public function estadisticasJugadoras()
{
    return $this->hasMany(PartidoJugador::class);
}

public function competencia()
{
    return $this->belongsTo(Competencia::class);
}
    }