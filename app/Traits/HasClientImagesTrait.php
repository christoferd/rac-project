<?php

namespace App\Traits;

use App\Models\ClientImage;

/**
 * Usage:
 * 1. add trait to top of class, use trait in class.
 *      use App\Traits\HasClientImagesTrait;
 *
 * 2. add to $listeners
 *      // by ClientImageManager
 *      'UploadedClientImage'              => 'handleEvent_UploadedClientImage',
 *      // by ClientImageManager
 *      'DeletedClientImage'               => 'handleEvent_DeletedClientImage',
 *
 * 3. load images with your model data (e.g. in function loadModelData())
 *      $this->loadClientImages($rental->client_id ?? 0);
 */
trait HasClientImagesTrait
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
     */

    public array $clientImagesFilenames = [];
    //
    // function loadClientImages(int $clientId): void
    // {
    //     if($clientId > 0) {
    //         $this->clientImagesFilenames = ClientImage::select(['id', 'filename'])
    //                                                   ->where('client_id', $clientId)
    //                                                   ->pluck('filename')
    //                                                   ->toArray();
    //     }
    //     else {
    //         $this->clientImagesFilenames = [];
    //     }
    // }
    //
    // /**
    //  * @param int $id Client ID
    //  * @return void
    //  */
    // public function handleEvent_UploadedClientImage(int $id): void
    // {
    //     $this->loadClientImages($id);
    // }
    //
    // /**
    //  * @param int $id Client ID
    //  * @return void
    //  */
    // public function handleEvent_DeletedClientImage(int $id): void
    // {
    //     $this->loadClientImages($id);
    // }

}
