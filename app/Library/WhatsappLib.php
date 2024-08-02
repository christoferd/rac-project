<?php

namespace App\Library;

class WhatsappLib
{
    // const WHATSAPP_NEW_LINE = '%0D%0A-%20';
    const WHATSAPP_NEW_LINE = '%0D%0A';

    /**
     * Param 1: the mobile phone number
     * Param 2: the message, url encoded, using special string for new lines
     */
    const WHATSAPP_SEND_MESSAGE_URL = 'https://wa.me/%s?text=%s';

    static function url(string $phoneNumberComplete = '+...', string $message = ''): string
    {
        $message = \str_replace("\r\n", WhatsappLib::WHATSAPP_NEW_LINE, $message);
        $message = \str_replace("\n", WhatsappLib::WHATSAPP_NEW_LINE, $message);
        return sprintf(static::WHATSAPP_SEND_MESSAGE_URL, $phoneNumberComplete, $message);
    }

}
