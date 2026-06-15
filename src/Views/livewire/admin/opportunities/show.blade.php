<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $opportunity->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('crm::crm.opportunity_details') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.crm.opportunities.edit', $opportunity->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                <span>{{ __('crm::crm.edit_opportunity') }}</span>
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('crm::crm.details') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.name') }}</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $opportunity->name }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.status') }}</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                    @if($opportunity->status === 'won') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400
                    @elseif($opportunity->status === 'lost') bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400
                    @else bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400
                    @endif">
                    {{ __('crm::crm.status_' . $opportunity->status) }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.pipeline') }}</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $opportunity->pipeline?->name ?: '-' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.stage') }}</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $opportunity->stage?->name ?: '-' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.customer') }}</span>
                @if($opportunity->customer)
                    <a href="{{ route('admin.crm.customers.show', $opportunity->customer->id) }}"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        {{ $opportunity->customer->name }}
                    </a>
                @else
                    <span class="text-sm text-gray-900 dark:text-white">-</span>
                @endif
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.lead') }}</span>
                @if($opportunity->lead)
                    <a href="{{ route('admin.crm.leads.show', $opportunity->lead->id) }}"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        {{ $opportunity->lead->name }}
                    </a>
                @else
                    <span class="text-sm text-gray-900 dark:text-white">-</span>
                @endif
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.value') }}</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $opportunity->value ? number_format($opportunity->value, 2) : '-' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.close_date') }}</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $opportunity->close_date ? $opportunity->close_date->format('Y-m-d') : '-' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.owner') }}</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $opportunity->owner?->name ?: '-' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.created_at') }}</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $opportunity->created_at->format('Y-m-d') }}</span>
            </div>
        </div>
    </div>

    <!-- Activities Section -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('crm::crm.activities') }}</h3>

        <!-- Add Activity Form -->
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 mb-4 border border-gray-100 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                <div>
                    <select wire:model="activityType"
                        class="w-full text-right px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">{{ __('crm::crm.select_activity_type') }}</option>
                        <option value="call">{{ __('crm::crm.activity_call') }}</option>
                        <option value="meeting">{{ __('crm::crm.activity_meeting') }}</option>
                        <option value="email">{{ __('crm::crm.activity_email') }}</option>
                        <option value="task">{{ __('crm::crm.activity_task') }}</option>
                    </select>
                </div>
                <div>
                    <input type="text" wire:model="activityTitle" placeholder="{{ __('crm::crm.activity_title') }}"
                        class="w-full text-right px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <input type="text" wire:model="activityDescription" placeholder="{{ __('crm::crm.activity_description') }}"
                        class="w-full text-right px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <input type="date" wire:model="activityDueDate"
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            <div class="flex justify-end">
                <button wire:click="addActivity"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>{{ __('crm::crm.add_activity') }}</span>
                </button>
            </div>
        </div>

        <!-- Activities List -->
        @if($opportunity->activities && $opportunity->activities->count())
            <div class="space-y-3">
                {{-- Pending Activities --}}
                @foreach($opportunity->activities->where('status', 'pending') as $activity)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-700 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded text-xs font-semibold bg-yellow-50 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-400">
                                {{ __('crm::crm.activity_' . $activity->type) }}
                            </span>
                            <div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->title }}</span>
                                @if($activity->due_date)
                                    <span class="text-xs text-gray-400 mr-2">{{ $activity->due_date->format('Y-m-d') }}</span>
                                @endif
                            </div>
                        </div>
                        <button wire:click="markActivityComplete({{ $activity->id }})"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 dark:bg-green-950/30 dark:text-green-400 dark:hover:bg-green-950/50 rounded-lg transition-colors">
                            <i data-lucide="check" class="w-3 h-3"></i>
                            <span>{{ __('crm::crm.mark_complete') }}</span>
                        </button>
                    </div>
                @endforeach

                {{-- Completed Activities --}}
                @foreach($opportunity->activities->where('status', 'completed') as $activity)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-700 opacity-60">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded text-xs font-semibold bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400">
                                {{ __('crm::crm.activity_' . $activity->type) }}
                            </span>
                            <div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white line-through">{{ $activity->title }}</span>
                                @if($activity->due_date)
                                    <span class="text-xs text-gray-400 mr-2">{{ $activity->due_date->format('Y-m-d') }}</span>
                                @endif
                            </div>
                        </div>
                        <span class="text-xs text-green-600 dark:text-green-400 font-semibold">{{ __('crm::crm.completed') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i data-lucide="calendar" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                <span>{{ __('crm::crm.no_activities') }}</span>
            </div>
        @endif
    </div>

    <!-- Notes Section -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('crm::crm.notes') }}</h3>

        <!-- Add Note Form -->
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 mb-4 border border-gray-100 dark:border-gray-700">
            <textarea wire:model="noteContent" rows="3" placeholder="{{ __('crm::crm.note_placeholder') }}"
                class="w-full text-right px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3"></textarea>
            <div class="flex justify-end">
                <button wire:click="addNote"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>{{ __('crm::crm.add_note') }}</span>
                </button>
            </div>
        </div>

        <!-- Notes List -->
        @if($opportunity->notes && $opportunity->notes->count())
            <div class="space-y-3">
                @foreach($opportunity->notes->sortByDesc('created_at') as $note)
                    <div class="p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <p class="text-sm text-gray-900 dark:text-white mb-2">{{ $note->content }}</p>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <span>{{ $note->user?->name ?: __('crm::crm.unknown_author') }}</span>
                            <span>&middot;</span>
                            <span>{{ $note->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i data-lucide="sticky-note" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                <span>{{ __('crm::crm.no_notes') }}</span>
            </div>
        @endif
    </div>
</div>
