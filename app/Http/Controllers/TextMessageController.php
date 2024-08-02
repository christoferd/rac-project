<?php

namespace App\Http\Controllers;

use App\Library\Phone;
use App\Library\WhatsappLib;
use App\Models\Client;
use App\Models\Rental;
use App\Models\TextMessage;
use App\Models\Vehicle;

class TextMessageController extends Controller
{
    function index()
    {
        if(userCan('access messages')) {
            return view('text_message.index');
        }
        return view('app.unauthorized');
    }

    function select(int $clientId)
    {
        $client = Client::findOrFail($clientId);
        $fullPhoneNumber = Phone::fullPhoneNumber($client->phone_number);
        $validPhoneNumber = Phone::isValidPhoneNumber($fullPhoneNumber);

        /*
         * Key Tags Data
         */
        $keyTagsData = [];

        // Client Data
        $keyTagsData = \array_merge($keyTagsData, $client->getKeyTagsData());

        // Vehicle Data
        $vehicleId = intval(\request('vehicle-id', 0));
        if($vehicleId > 0) {
            $vehicle = Vehicle::withTrashed()->findOrFail($vehicleId);
            $keyTagsData = \array_merge($keyTagsData, $vehicle->getKeyTagsData());
        }

        // Rental Data
        $rentalId = intval(\request('rental-id', 0));
        if($rentalId > 0) {
            $rental = Rental::withTrashed()->findOrFail($rentalId);
            $keyTagsData = \array_merge($keyTagsData, $rental->getKeyTagsData());
        }

        /*
         * Prep message content
         */
        $messages = TextMessage::all();
        foreach($messages as &$message) {
            $message->message_content = \str_replace(array_keys($keyTagsData), \array_values($keyTagsData), $message->message_content);

            // Url a whatsapp page that automatically opens the Whatsapp Windows App with the correct contact and message injected
            // (else you can press the big green button to do it)
            $message->setAttribute('whatsapp_url', WhatsappLib::url($fullPhoneNumber, $message->message_content));
        }

        return view('text_message.select', compact('client', 'messages', 'fullPhoneNumber', 'validPhoneNumber'));
    }

}
