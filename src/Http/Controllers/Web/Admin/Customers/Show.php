<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dev3bdulrahman\Crm\Models\Customer;
use Dev3bdulrahman\Crm\Models\Activity;
use Dev3bdulrahman\Crm\Models\Note;
use Dev3bdulrahman\Crm\Services\ActivityService;

class Show extends Component
{
    use AuthorizesRequests;

    public Customer $customer;

    // Activity form properties
    public string $activityType = 'call';
    public string $activityTitle = '';
    public string $activityDescription = '';
    public string $activityDueDate = '';

    // Note form properties
    public string $noteContent = '';

    #[Layout('layouts.admin')]
    public function mount(Customer $customer)
    {
        $this->authorize('view', $customer);

        $this->customer = $customer->load(['group', 'organization', 'contact', 'opportunities', 'activities', 'notes']);
    }

    public function addActivity(ActivityService $activityService)
    {
        $this->validate([
            'activityType' => 'required|in:call,meeting,task,email',
            'activityTitle' => 'required|string|max:255',
            'activityDescription' => 'nullable|string|max:1000',
            'activityDueDate' => 'nullable|date',
        ]);

        $activityService->createActivity([
            'subject_type' => Customer::class,
            'subject_id' => $this->customer->id,
            'type' => $this->activityType,
            'title' => $this->activityTitle,
            'description' => $this->activityDescription ?: null,
            'due_date' => $this->activityDueDate ?: null,
            'status' => 'pending',
            'user_id' => auth()->id(),
            'company_id' => $this->customer->company_id,
        ]);

        $this->reset(['activityType', 'activityTitle', 'activityDescription', 'activityDueDate']);
        $this->activityType = 'call';

        $this->customer->load('activities');

        $this->dispatch('notify', ['type' => 'success', 'message' => __('crm::crm.activity_added')]);
    }

    public function addNote()
    {
        $this->validate([
            'noteContent' => 'required|string|max:2000',
        ]);

        Note::create([
            'noteable_type' => Customer::class,
            'noteable_id' => $this->customer->id,
            'content' => $this->noteContent,
            'user_id' => auth()->id(),
            'company_id' => $this->customer->company_id,
        ]);

        $this->reset('noteContent');

        $this->customer->load('notes');

        $this->dispatch('notify', ['type' => 'success', 'message' => __('crm::crm.note_added')]);
    }

    public function markActivityComplete($activityId)
    {
        $activity = Activity::where('id', $activityId)
            ->where('subject_type', Customer::class)
            ->where('subject_id', $this->customer->id)
            ->firstOrFail();

        $activity->update(['status' => 'completed']);

        $this->customer->load('activities');

        $this->dispatch('notify', ['type' => 'success', 'message' => __('crm::crm.activity_completed')]);
    }

    public function render()
    {
        return view('crm::livewire.admin.customers.show')
            ->title(__('crm::crm.view_customer'));
    }
}
