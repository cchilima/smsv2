<?php

namespace App\Repositories\Users;

use App\Http\Requests\Users\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;


class UserPersonalInfoRepository
{
    /**
     * Upload a passport photo for a user.
     *
     * @param  UploadedFile  $fileObject The uploaded file object.
     * @param  int  $userId The ID of the user.
     * @return string The path to the uploaded newly stored file.
     */
    public function uploadPassportPhoto(UploadedFile $fileObject, $userId)
    {
        $dir = 'app/public/uploads/passport-photos';

        // Create directory if it doesn't exist
        if (!is_dir(storage_path($dir))) {
            mkdir(storage_path($dir), 0777, true);
        }

        // Create new image manager and image instance
        $imageManager = new ImageManager(new Driver());
        $image = $imageManager->read($fileObject->getRealPath());

        // Resize and encode image to JPG
        $resizedImage = $image->scaleDown(height: 500);
        $encodedImage = $resizedImage->encode(new JpegEncoder(quality: 50));
        $fileName = $userId . '.jpg';

        // Save to storage path
        $encodedImage->save(storage_path($dir) . '/' . $fileName);

        // Return generated path
        return 'storage/uploads/passport-photos/' . $fileName;
    }

    public function deletePassportPhoto($path): bool
    {
        $file = public_path($path);

        return file_exists($file) ? unlink($file) : false;
    }

    public function destroy($userId)
    {
        $user = User::find($userId);
        return $user->userPersonalInfo()->destroy();
    }
}
