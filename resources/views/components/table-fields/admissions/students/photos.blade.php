@php
    use App\Helpers\Qs;

    $passportPhotoUrl = !$row->user->userPersonalInfo?->passport_photo_path
        ? asset('images/default-avatar.png')
        : asset($row->user->userPersonalInfo?->passport_photo_path);
@endphp

<img style="aspect-ratio: 1/1; object-fit: cover; width: 50px; height: 50px" src="{{ $passportPhotoUrl }}" alt="photo"
    class="rounded-circle">
