<?php

namespace Dev3bdulrahman\Crm\Services;

use Dev3bdulrahman\Crm\Models\Activity;
use Dev3bdulrahman\Crm\Models\Note;
use Illuminate\Database\Eloquent\Collection;

class ActivityService
{
    /**
     * List activities based on filters.
     */
    public function listActivities(array $filters = []): Collection
    {
        $query = Activity::query()->with(['user', 'subject']);

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['subject_type']) && !empty($filters['subject_id'])) {
            $query->where('subject_type', $filters['subject_type'])
                  ->where('subject_id', $filters['subject_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['overdue'])) {
            $query->where('due_date', '<', now())
                  ->where('status', 'pending');
        }

        return $query->orderBy('due_date', 'asc')->get();
    }

    /**
     * Create a new Activity.
     */
    public function createActivity(array $data): Activity
    {
        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }
        return Activity::create($data);
    }

    /**
     * Update an Activity.
     */
    public function updateActivity($id, array $data): Activity
    {
        $activity = Activity::findOrFail($id);
        $activity->update($data);
        return $activity;
    }

    /**
     * Delete an Activity.
     */
    public function deleteActivity($id): bool
    {
        $activity = Activity::findOrFail($id);
        return $activity->delete();
    }

    // ─── Polymorphic Notes ────────────────────────────────────────────────────

    /**
     * Log a history note.
     */
    public function logNote(string $noteableType, $noteableId, string $content, $userId = null): Note
    {
        return Note::create([
            'user_id' => $userId ?: auth()->id(),
            'noteable_type' => $noteableType,
            'noteable_id' => $noteableId,
            'content' => $content,
        ]);
    }

    /**
     * Get notes for a target subject.
     */
    public function getNotes(string $noteableType, $noteableId): Collection
    {
        return Note::where('noteable_type', $noteableType)
            ->where('noteable_id', $noteableId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
