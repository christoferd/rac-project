<?php

namespace Database\Seeders;

use App\Models\TextMessage;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TextMessageSeeder extends Seeder
{
    public function run(): void
    {
        $nl = "\n";
        $message = new TextMessage();
        $message->message_title = 'Auto Está Pronto';
        $message->message_notes = 'Un mensaje para enviar al cliente a primera hora del día para recordarle su alquiler y confirmar que su coche '.
                                  'está listo.';
        $message->message_content = 'Hola {cliente.nombre}, sólo para confirmar - el coche {vehículo.marca}, {vehículo.modelo} que vas a retirar hoy está listo.'.$nl.
                                    'Hora de Retiro: {alquiler.hora_retiro}'.$nl.
                                    '- Rent a Car';
        $message->save();

        echo 'Created new TextMessage ('.$message->message_title.') ID#'.$message->id.$nl;

        $message = new TextMessage();
        $message->message_title = 'Confirmar Reserva';
        $message->message_notes = 'Confirmar la reserva del alquiler.';
        $message->message_content = 'Hola {cliente.nombre}, confirmamos - el coche {vehículo.marca}, {vehículo.modelo} está reservado para retirar en la fecha: {alquiler.fecha_retiro}'.$nl.
                                    'Hora de Retiro: {alquiler.hora_retiro}'.$nl.
                                    '- Rent a Car';
        $message->save();

        echo 'Created new TextMessage ('.$message->message_title.') ID#'.$message->id.$nl;
    }
}
