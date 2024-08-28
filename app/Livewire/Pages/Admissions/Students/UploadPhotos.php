<?php

namespace App\Livewire\Pages\Admissions\Students;

use App\Helpers\Qs;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Users\UserPersonalInfoRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;

class UploadPhotos extends Component
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

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSA());
    }

    public function uploadPhotos()
    {
        try {
            $this->validate();

            DB::beginTransaction();

            if (!empty($this->photos)) {
                $failedUploads = [
                    'invalid_student_ids' => [],
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
                        return $user->userPersonalInfo?->update(['passport_photo_path' => $uploadedPhotoPath]);
                    }

                    // If user not found, record failed upload and skip to next photo
                    $failedUploads['invalid_student_ids'][] = Str::before($photo->getClientOriginalName(), '.' .
                        $photo->getClientOriginalExtension());;
                });

                // If there are failed uploads, return error message
                if (!empty($failedUploads['invalid_student_ids'])) {

                    $invalidIdErrors = collect($failedUploads['invalid_student_ids'])->map(function ($photo) {
                        return 'Invalid student ID: ' . $photo;
                    })->toArray();

                    return $this->dispatch('show_invalid_id_errors', $invalidIdErrors);
                }

                $this->dispatch('show_success');

                // Refresh student photos datatable
                $this->refreshTable('StudentPhotosTable');

                DB::commit();
            }
        } catch (ValidationException $e) {
            $messages = [];

            foreach ($e->validator->errors()->messages() as $value) {
                $messages[] = $value[0];
            }

            $this->dispatch('show_validation_errors', array_unique($messages));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function rules()
    {
        return [
            'photos' => 'required|array|max:20',
            'photos.*' => 'required|file|mimes:jpg,jpeg,png|max:5120',
        ];
    }

    public function validationAttributes()
    {
        return [
            'photos.*' => 'photo',
            'photos' => 'photos'
        ];
    }

    public function messages()
    {
        return [
            'photos.required' => 'Add at least one photo.',
            'photos.*.file' => 'Each item must be a valid file.',
            'photos.*.mimes' => 'Each photo must be a file of type JPG or PNG.',
            'photos.*.max' => 'Each photo should not be greater than 5MB.',
            'photos.max' => 'You may upload no more than :max photos at a time.',
        ];
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.admissions.students.upload-photos');
    }
}
