<?php

namespace App\Livewire\Pages\Admissions\Students\Uploads;

use App\Helpers\Qs;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Users\UserPersonalInfoRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;

class Photos extends Component
{
    use CanRefreshDataTable, WithFilePond;

    public array $photos = [];

    protected UserPersonalInfoRepository $userPersonalInfoRepo;
    protected StudentRepository $studentRepo;

    public function boot(): void
    {
        $this->userPersonalInfoRepo = app(UserPersonalInfoRepository::class);
        $this->studentRepo = app(StudentRepository::class);
    }

    // TODO: Fix validation and error handling
    public function uploadPhotos()
    {
        $this->validate();

        try {
            if (!empty($this->photos)) {
                $failedUploads = [
                    'invalid_student_id' => [],
                ];

                // Loop through each photo and process it
                collect($this->photos)->each(function (TemporaryUploadedFile $photo) use (&$failedUploads) {
                    // Get student ID from the file name
                    $studentId = Str::before($photo->getClientOriginalName(), '.' .
                        $photo->getClientOriginalExtension());

                    // Get student user record
                    $user = $this->studentRepo->find($studentId)?->user;

                    if ($user) {
                        // Upload photo
                        $uploadedPhotoPath = $this->userPersonalInfoRepo->uploadPassportPhoto($photo, $user->id);

                        // Update user personal info with new path
                        $user->userPersonalInfo?->update(['passport_photo_path' => $uploadedPhotoPath]);
                    }

                    // If user not found, record failed upload and skip to next photo
                    $failedUploads['invalid_student_id'][] = $photo->getClientOriginalName();
                });

                // If there are failed uploads, return error message
                if (!empty($failedUploads)) {
                    // Qs::json('Failed to upload photos: ' . implode(', ', $failedUploads), false);

                    $errorsInvalidId = collect($failedUploads['invalid_student_id'])->map(function ($photo) {
                        return 'Upload failed - invalid student ID: ' . $photo;
                    })->toArray();

                    return Qs::displayError($errorsInvalidId);
                }

                return Qs::jsonStoreOk();
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function rules()
    {
        return [
            'photos' => 'required|array|min:30',
            'photos.*' => 'required|file|mimes:jpg,png|max:2048',
        ];
    }

    public function validationAttributes()
    {
        return [
            'photos.*' => 'Photos',
        ];
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.admissions.students.uploads.photos');
    }
}
