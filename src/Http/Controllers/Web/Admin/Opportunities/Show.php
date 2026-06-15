<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dev3bdulrahman\Crm\Models\Opportunity;
use Dev3bdulrahman\Crm\Models\Activity;
use Dev3bdulrahman\Crm\Models\Note;
use Dev3bdulrahman\Crm\Services\ActivityService;

class Show extends Component
{
    use AuthorizesRequests;

    public Opportunity $opportunity;

    // Activity form properties
    public string $activityType = 'call';
    public string $activityTitle = '';
    public string $activityDescription = '';
    public string $activityDueDate = '';

    // Note form properties
    public string $noteContent = '';

    #[Layout('layouts.admin')]
    public function mount(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
        $this->authorize('view', $this->opportunity);

        $this->opportunity->load(['pipeline', 'stage', 'customer', 'lead', 'activities', 'notes', 'owner']);
    }

    public function addActivity(ActivityService $service)
    {
        $this->validate([
            'activityType' => 'required|in:call,meeting,task,email',
            'activityTitle' => 'required|string|max:255',
            'activityDescription' => 'nullable|string',
            'activityDueDate' => 'nullable|date',
        ]);

        $service->createActivity([
            'subject_type' => 'opportunity',
            'subject_id' => $this->opportunity->id,
            'type' => $this->activityType,
            'title' => $this->activityTitle,
            'description' => $this->activityDescription ?: null,
            'due_date' => $this->activityDueDate ?: null,
            'user_id' => auth()->id(),
            'company_id' => auth()->user()->company_id,
        ]);

        $this->reset(['activityType', 'activityTitle', 'activityDescription', 'activityDueDate']);
        $this->activityType = 'call';

        $this->opportunity->load('activities');

        session()->flash('success', __('crm::crm.activity_added'));
    }

    public function addNote()
    {
        $this->validate([
            'noteContent' => 'required|string',
        ]);

        Note::create([
            'noteable_type' => 'opportunity',
            'noteable_id' => $this->opportunity->id,
            'content' => $this->noteContent,
            'user_id' => auth()->id(),
            'company_id' => auth()->user()->company_id,
        ]);

        $this->reset('noteContent');

        $this->opportunity->load('notes');

        session()->flash('success', __('crm::crm.note_added'));
    }

    public function markActivityComplete($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $activity->update(['status' => 'completed']);

        $this->opportunity->load('activities');
    }

    public function render()
    {
        return view('crm::livewire.admin.opportunities.show')
            ->title(__('crm::crm.opportunity_details'));
    }
}
