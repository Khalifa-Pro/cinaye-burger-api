<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'etat',
        'archiver',
        'id_burger',
    ];
    use HasFactory;
}
