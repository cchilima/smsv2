<?php

namespace App\Repositories\Users;

// use App\Models\Users\{User};

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class UserPersonalInfoRepository
{
    public function uploadPassportPhoto($path)
    {
        $dir_rel = 'app/public/uploads/passport-photos';
        $dir = storage_path($dir_rel);

        // Create directory if it doesn't exist
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // create new image manager and object
        $imageManager = new ImageManager(new Driver());
        $image = $imageManager->read($path->getRealPath());

        // construct unique image name
        $imageName = time() . '-' . $path->getClientOriginalName();

        // save to storage path
        $image->save($dir . '/' . $imageName, 100, 'jpg');

        // Return generated path
        return $dir_rel . $imageName;
    }
}
