<?php

namespace App\Repositories\Users;

use App\Http\Requests\Users\User;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class UserPersonalInfoRepository
{
    public function uploadPassportPhoto($path)
    {
        $dir_rel = 'app/public/uploads/passport-photos/';
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
        return str_replace('app/public', 'storage', $dir_rel) . $imageName;
    }

    public function deletePassportPhoto($path)
    {
        $file = storage_path($path);

        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function destroy($userId)
    {
        $user = User::find($userId);
        return $user->userPersonalInfo()->destroy();
    }
}
