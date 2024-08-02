<?php

namespace App\Livewire;

use App\Events\FileDeletedEvent;
use App\Models\Client;
use App\Models\ClientImage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use App\Livewire\Traits\LivewireAlerts;

// use App\Traits\HasClientImagesTrait;

class ClientImagesDisplay extends Component
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
     *
     */
    use LivewireAlerts;

    // use HasClientImagesTrait;

    public int $client_id = 0;
    public bool $showDeleteButton = false;
    public bool $allowOpenImage = false;

    // Media
    public string $mediaCollection = 'files';
    public $media = null;
    // public array $clientImages = [];

    // filesystem disk
    public string $disk = 'admin';

    protected $listeners = [
        'ClientImagesDisplayReset'  => 'reset',
        'UploadedClientImage'       => 'handleEvent_UploadedClientImage',
        'ManageImagesForClient'     => 'handleEvent_ManageImagesForClient',
        'DeleteClientImage'         => 'handleEvent_DeleteClientImage',
        // --
        'Closed_ClientImageManager' => 'resetValues',
    ];

    public function mount(int $clientId, int $allowOpenImage = 0, int $showDeleteButton = 0): void
    {
        $this->client_id = $clientId;
        $this->allowOpenImage = \boolval($allowOpenImage);
        $this->showDeleteButton = \boolval($showDeleteButton);
        $this->loadClientImages($clientId);
    }

    function loadClientImages(int $client_id): void
    {
        if($client_id) {
            $model = Client::findOrFail($client_id);
            $this->media = $model->getMedia($this->mediaCollection);
        }
    }

    public function render(): View
    {
        return view('livewire.client-images-display');
    }

    public function handleEvent_ManageImagesForClient(int $client_id): void
    {
        if(empty($client_id)) {
            $this->alertError('handleEvent_ManageImagesForClient: empty client id');
        }
        $this->client_id = $client_id;
        $this->loadClientImages($client_id);
    }

    public function handleEvent_UploadedClientImage(int $id): void
    {
        if($id === $this->client_id) {
            $this->loadClientImages($id);
        }
    }

    public function handleEvent_DeleteClientImage(int $index): void
    {
        $imageFilename = ($this->clientImagesFilenames[$index] ?? null);
        if(!\is_null($imageFilename)) {
            try {
                // Delete File
                if(Storage::disk($this->disk)->delete($imageFilename)) {
                    // Event
                    FileDeletedEvent::dispatch($this->disk, '', $imageFilename);
                    // Remove from database
                    ClientImage::where('filename', $imageFilename)
                               ->delete();
                    // Livewire Event
                    $this->dispatch('DeletedClientImage');

                    $this->alertSuccess('Imagen borrado');
                    // Remove from list instead of reloading data
                    // unset($this->clientImages[$index]);
                    $this->loadClientImages($this->client_id);
                    return;
                }
            }
            catch(\Throwable $ex) {
                Log::error(__CLASS__.': Error in '.__FUNCTION__);
                Log::error($ex);
            }
        }
        $this->alertError('Error');
    }
}
