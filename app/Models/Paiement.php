<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        "montant",
        "id_ligne_commande",
    ];
    use HasFactory;
}
