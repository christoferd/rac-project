<?php

namespace App\Livewire;

use App\Library\FileLib;
use App\Livewire\Traits\LivewireAlerts;
use App\Models\Client;
use App\Models\ClientImage;
use App\Events\FileUploadedEvent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Storage;

class ClientImagesUpload extends Component
{
    /*
     * Not Used // Chris D. 11-Apr-2024
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     */
    use WithFileUploads, LivewireAlerts;

    public int $client_id = 0;

    protected $listeners = [
        'ManageImagesForClient'     => 'handleEvent_ManageImagesForClient',
        // --
        'Closed_ClientImageManager' => 'reset',
    ];

    /**
     * Livewire TemporaryUploadedFile
     * Example:
     * -test: false
     * -originalName: "Q9l0XFPcoFY8yWhvQbfSOej65...
     * -mimeType: "application/octet-stream"
     * #path: "
     * livewire-tmp/Q9l0XFPco...
     * path: "C:\www\Rent_a_Car\app\storage\app\livewire-tmp"
     * filename: "Q9l0XFPco...
     * basename: "phpE552.tmp"
     * pathname: "C:\www\Rent_a_Car\app\storage\app\livewire-tmp/Q9l0XFPco...
     * extension: "tmp"
     * realPath: "C:\www\Rent_a_Car\app\storage\app\livewire-tmp/Q9l0XFPco...
     * aTime: 2024-03-09 18:09:20
     * mTime: 2024-03-09 18:09:20
     * cTime: 2024-03-09 18:09:20
     * inode: 26740122787548660
     * size: 90746
     * writable: false
     * readable: false
     * executable: false
     * file: false
     * dir: false
     * link: false
     *
     * @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
     */
    public $uploadedFile;

    protected $messages = [
        'uploadedFile.*' => 'Foto debe ser tipo: jpeg/png/jpg'
    ];

    public function mount(int $clientId): void
    {
        $this->client_id = $clientId;
    }

    public function save(): void
    {
        // try {
        //     $this->validate([
        //                         'uploadedFile' => 'image|max:'.FileLib::FILE_MAX_SIZE_BYTES.'|mimes:'.FileLib::THUMB_SUPPORTED_EXTENSIONS_CSV,
        //                     ]);
        //     $disk = 'admin';
        //     $filenamePathRelativeToDisk = Storage::disk($disk)->putFile('clients', $this->uploadedFile);
        //     // Returns: "clients/7Y3GBujOFvRWlijgVuGJWByCbEn4Mjd2gvSeOO31.png"
        //     // !!! NOTE: it returns the filename, PREFIXED! with the folder or relative path it is stored in!!!
        //     if(empty($filenamePathRelativeToDisk)) {
        //         $this->alertError('Error');
        //         return;
        //     }
        //     // Event
        //     // FileUploadedEvent::dispatch($disk, '', $filenamePathRelativeToDisk);
        //     try {
        //         list($folder, $filename) = FileLib::cleanUniformFolderFilename('', $filenamePathRelativeToDisk);
        //         $thumbPath = FileLib::generateThumb($disk, $folder, $filename);
        //         Log::debug(__CLASS__.' generateThumb result: '.($thumbPath ?? ''));
        //     }
        //     catch(\Throwable $ex) {
        //         Log::critical('Operation failed in Class: '.__CLASS__.' | function handle(FileUploadedEvent $event)');
        //         Log::critical($ex);
        //         $this->alertError('Error');
        //         return;
        //     }
        //
        //     // Store record in DB table
        //     ClientImage::create(['client_id' => $this->client_id, 'filename' => $filenamePathRelativeToDisk]);
        //     $this->alertSuccess('Foto guardado');
        //     $this->dispatch('UploadedClientImage', id: $this->client_id);
        //     $this->uploadedFile = null;
        // }
        // catch(\Throwable $ex) {
        //     $this->alertError('Error');
        //     Log::error('Failed to save uploaded file for record ID #'.$this->client_id,
        //                [
        //                    'orig-filename'    => $this->uploadedFile->getClientOriginalName(),
        //                    'filename'         => $this->uploadedFile->getFilename(),
        //                    'mime-type'        => $this->uploadedFile->getMimeType(),
        //                    'mime-type-client' => $this->uploadedFile->getClientMimeType(),
        //                    'extension'        => $this->uploadedFile->getExtension(),
        //                    'orig-extension'   => $this->uploadedFile->getClientOriginalExtension(),
        //                ]);
        //     Log::error($ex);
        //     $this->alertError('Error');
        // }
        \ini_set('memory_limit', '512M');
        try {
            // $this->validate([
            //                     'uploadedFile' => 'image|max:'.FileLib::FILE_MAX_SIZE_BYTES.'|mimes:'.FileLib::THUMB_SUPPORTED_EXTENSIONS_CSV,
            //                 ]);

            $clientOriginalName = $this->uploadedFile->getClientOriginalName();
            $clientOriginalExt = $this->uploadedFile->getClientOriginalExtension();
            $name = \substr(FileLib::cleanFilename($clientOriginalName), 0, 254);

            // Name of file to keep in DB
            // - Remove extension
            $name = \str_replace('.'.$clientOriginalExt, '', $name);
            // Name of saved file
            // - impossible for anyone to guess URL
            $secureFileName = FileLib::generateSecureFileName($this->uploadedFile->getFilename(), $clientOriginalExt);

            $pathToFile = \storage_path('app/livewire-tmp/'.$this->uploadedFile->getFilename());

            $this->alertDebug('pathToFile = '.$pathToFile);
            if(!\file_exists($pathToFile)) {
                $this->alertError('Error - File does not exist!');
                return;
            }

            $client = Client::findOrFail($this->client_id);
            $client
                ->addMedia($pathToFile)
                ->setName($name)
                ->setFileName($secureFileName)
                ->toMediaCollection('files');

            // Success
            $this->alertSuccess(__('File uploaded'));
            // Notify other components
            $this->dispatch('UploadedClientImage', id: $this->client_id);
            // Reset
            $this->uploadedFile = null;
        }
        catch(\Throwable $ex) {
            Log::error($ex);
            $this->alertError('Error - '.$ex->getMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.client-images-upload');
    }

    public function handleEvent_ManageImagesForClient(int $client_id): void
    {
        if(empty($client_id)) {
            $this->alertError('handleEvent_ManageImagesForClient: empty client id');
        }
        $this->client_id = $client_id;
    }

}
