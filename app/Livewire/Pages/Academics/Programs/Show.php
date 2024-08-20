<?php

namespace App\Livewire\Pages\Academics\Programs;

use App\Helpers\Qs;
use App\Repositories\Academics\CourseRepository;
use App\Repositories\Academics\ProgramsRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    use CanRefreshDataTable;

    protected ProgramsRepository $programRepo;
    protected CourseRepository $courseRepo;

    public $data;

    public function boot()
    {
        $this->programRepo = app(ProgramsRepository::class);
        $this->courseRepo = app(CourseRepository::class);
    }

    public function mount(string $id)
    {
        Gate::allowIf(Qs::userIsTeamSA());

        $id = Qs::decodeHash($id);
        $this->data['programId'] = $id;
        $this->data['program'] = $this->programRepo->findOne($id);
        $this->data['levels'] = $this->programRepo->getCourseLevelsByProgram($id);
        $this->data['newcourses'] = $this->courseRepo->getAll();
        $this->data['pcourses'] = $this->programRepo->getCoursesByProgram($id);

        return $this->data['program'] ? $this->render() : Qs::goWithDanger('pages.programs.index');
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.programs.show', $this->data);
    }
}
