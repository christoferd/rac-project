@props(['filename', 'disk' => 'admin', 'folder' => '', 'imgClass' => '', 'alt' => 'image', 'thumb' => '0'])
<img
    src="{!! route('file-load', [$disk, 'folder' => $folder, 'filename' => $filename, 'thumb' => '1', 'sec' => App\Library\SecurityLib::createToken($folder.$filename)]); !!}"
    alt="{!! $alt !!}"
    class="{!! $imgClass !!}"
/>
