<?php

namespace App\Livewire;

use App\Library\FileLib;
use App\Livewire\Traits\LivewireAlerts;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use \Livewire\WithFileUploads;

class ModelFilesManager extends ObjectiveComponent
{
    use WithFileUploads, LivewireAlerts;

    public int $modelId = 0;
    public string $modelClass = '';
    public bool $allowUpload = false;

    /**
     * Collection relative to db table field `media`.`collection_name`
     *
     * @var string E.g. "files"
     */
    public string $mediaCollection = '';

    /**
     * Result of $model->getMedia($this->mediaCollection);
     *
     * @var null|Collection
     */
    public $media = null;

    protected $listeners = [
        'UploadedMediaFile' => 'handleEvent_UploadedMediaFile',
        // Request to delete media/file
        // - Event from JS in view: components/image-gallery
        'DeleteFile'        => 'handleEvent_DeleteFile',
        // Event fired by this class
        'DeletedFile'       => 'handleEvent_DeletedFile',
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
    #[Validate('file|max:12288', as: 'Archivo', message: 'Tipos de archivos aceptados: png/jpg/jpeg/webp. Tamaño máximo de archivo: 12.000 KB, o 12 MB')]
    public $uploadedFile;

    // protected $rules = [
    //     'uploadedFile' => 'image|max:'.FileLib::FILE_MAX_SIZE_BYTES.'|mimes:'.FileLib::THUMB_SUPPORTED_EXTENSIONS_CSV
    // ];
    // protected $messages = [
    //     'uploadedFile.mimes' => 'Tipos de archivos aceptados: png/jpg/jpeg/webp',
    //     'uploadedFile.image' => 'Tipos de archivos aceptados: png/jpg/jpeg/webp',
    //     'uploadedFile.max'   => 'Tamaño máximo de archivo: 12MB',
    // ];

    public function mount(string $modelClass, int $modelId, string $mediaCollection, int $allowUpload = 0)
    {
        $this->modelId = $modelId;
        $this->modelClass = $modelClass;
        $this->mediaCollection = $mediaCollection;
        $this->allowUpload = boolval($allowUpload);
        $this->loadMedia();
    }

    public function render()
    {
        return view('livewire.model-files-manager');
    }

    function loadMedia(): void
    {
        try {
            $this->reset('media');
            if($this->modelId) {
                $model = $this->modelClass::findOrFail($this->modelId);
                $this->media = $model->getMedia($this->mediaCollection);
            }
        }
        catch(\Throwable $ex) {
            Log::error($ex);
            $this->alertError('Error in loadMedia(): '.$ex->getMessage());
        }
    }

    public function save(): void
    {
        try {
            // Increase memory limit for Spatie Media conversions
            \ini_set('memory_limit', '512M');

            // Validate here or not???
            // Chris D. 11-Apr-2024 Trying to use the inline class validation above the variable look for #[Validate(...
            // $this->validate($this->rules, $this->messages, ['uploadedFile']);

            $clientOriginalName = $this->uploadedFile->getClientOriginalName();
            $clientOriginalExt = $this->uploadedFile->getClientOriginalExtension();
            $name = \substr(FileLib::cleanFilename($clientOriginalName), 0, 254);

            // Name of file to keep in DB
            // - Remove extension
            $name = \str_replace('.'.$clientOriginalExt, '', $name);
            // Name of saved file
            // - impossible for anyone to guess URL
            $secureFileName = FileLib::generateSecureFileName($this->uploadedFile->getFilename(), $clientOriginalExt);

            // Location depends on livewire config
            $pathToFile = \storage_path('app/livewire-tmp/'.$this->uploadedFile->getFilename());

            if(!\file_exists($pathToFile)) {
                $this->alertError('Error - File does not exist!');
                return;
            }

            $model = $this->modelClass::findOrFail($this->modelId);
            $model
                ->addMedia($pathToFile)
                ->setName($name)
                ->setFileName($secureFileName)
                ->toMediaCollection('files');

            // Success
            $this->alertSuccess(__('File uploaded'));
            // Notify other components
            $this->dispatch('UploadedMediaFile', id: $this->modelId);
            // Reset
            $this->uploadedFile = null;
        }
        catch(\Throwable $ex) {
            Log::error($ex);
            $this->alertError('Error - '.$ex->getMessage());
        }
    }

    public function handleEvent_DeleteFile(string $class, int $id, int $mediaIndex)
    {
        // $this->alertDebug("handleEvent_DeleteFile(string $class, int $id, int $mediaIndex)");

        $err = \__tCsv('Error,: ,Failed to,Delete,File');
        try {
            $res = $this->media[$mediaIndex]->delete(); // bool
            if(empty($res)) {
                $this->alertError($err);
            }
        }
        catch(\Throwable $ex) {
            $this->alertError($err.' | '.$ex->getMessage());
        }

        $this->alertSuccess(\__tCsv('File', 'Deleted'));

        // Notify Components
        $this->dispatch('DeletedFile');
    }

    public function handleEvent_UploadedMediaFile(int $id)
    {
        // $this->alertDebug("handleEvent_UploadedMediaFile(int $id) this modelId = ".$this->modelId);
        $this->loadMedia();
    }

    public function handleEvent_DeletedFile()
    {
        // $this->alertDebug(\base_path(__CLASS__).' handleEvent_DeletedFile()');
        $this->loadMedia();
    }

}
