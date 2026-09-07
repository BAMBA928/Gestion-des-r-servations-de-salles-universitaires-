<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

return function (Capsule $capsule): void {
    $capsule->schema()->create('reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('salle_id')->constrained('salles');
        $table->string('responsable', 120);
        $table->string('email');
        $table->string('motif', 255);
        $table->dateTime('date_debut');
        $table->dateTime('date_fin');
        $table->enum('statut', ['confirmée', 'annulée'])->default('confirmée');
        $table->timestamps();
    });
};