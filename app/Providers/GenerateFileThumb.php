<?php

namespace App\Providers;

use App\Events\FileUploadedEvent;
use App\Library\FileLib;
use Illuminate\Support\Facades\Log;

class GenerateFileThumb
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FileUploadedEvent $event): void
    {
        try {
            $thumbPath = FileLib::generateThumb($event->disk, $event->folder, $event->filename);
            Log::debug(__CLASS__.' generateThumb result: '.($thumbPath??''));
        }
        catch(\Throwable $ex) {
            Log::critical('Operation failed in Class: '.__CLASS__.' | function handle(FileUploadedEvent $event)');
            Log::critical($ex);
        }
    }
}
