<?php

namespace App\Repositories\Users;

use App\Http\Requests\Users\User;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class UserPersonalInfoRepository
{
    public function uploadPassportPhotos() {}

    public function uploadPassportPhoto(UploadedFile $fileObject, $userId)
    {
        $dir_rel = 'app/public/uploads/passport-photos/';
        $dir = storage_path($dir_rel);

        // Create directory if it doesn't exist
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // create new image manager and object
        $imageManager = new ImageManager(new Driver());
        $image = $imageManager->read($fileObject->getRealPath());

        // TODO: Convert to JPG and compress
        // TODO: Delete any existing files with the same name
        // TODO: Show validation errors on front-end

        // Save to storage path
        $image->save($dir . '/' . $userId . $fileObject->getClientOriginalExtension());


        // Return generated path
        return str_replace('app/public', 'storage', $dir_rel) . $userId;
    }

    public function deletePassportPhoto($path): bool
    {
        $file = storage_path($path);

        return file_exists($file) ? unlink($file) : false;
    }

    public function destroy($userId)
    {
        $user = User::find($userId);
        return $user->userPersonalInfo()->destroy();
    }
}
