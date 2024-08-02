<?php

namespace App\View\Components;

use App\Models\Client;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ClientImagesDisplay extends Component
{
    public int $clientId;
    /**
     * @var int 1/0: Display edit button. Default: 1
     */
    public int $allowEdit;
    public array $clientImages;
    public $media = null;
    public string $numColumns;
    public string $storageDisk = 'admin';

    public function __construct(int    $clientId = 0,
                                string $numColumns = '4', string $filenamesJson = '{}', string $allowEdit = '1')
    {
        $this->clientId = $clientId;
        $this->numColumns = $numColumns;
        $this->clientImages = (array) \json_decode($filenamesJson);
        if($clientId) {
            $class = Client::class;
            $model = $class::findOrFail($clientId);
            $this->media = $model->getMedia('files');
        }
        $this->allowEdit = intval($allowEdit);
    }

    public function render(): View
    {
        return view('components.client-images-display');
    }
}
