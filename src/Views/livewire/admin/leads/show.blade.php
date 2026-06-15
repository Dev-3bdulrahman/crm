<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lead->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $lead->title }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if($lead->status !== 'converted')
                <button wire:click="openConvertModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <i data-lucide="git-commit" class="w-4 h-4"></i>
                    <span>{{ __('crm::crm.convert_lead') }}</span>
                </button>
            @endif
            <a href="{{ route('admin.crm.leads.edit', $lead->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                <span>{{ __('crm::crm.edit') }}</span>
            </a>
        </div>
    </div>

    <!-- Lead Details Card -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('crm::crm.lead_details') }}</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Name -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.lead_name') }}</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $lead->name }}</span>
                </div>
                <!-- Title -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.title') }}</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $lead->title ?: '-' }}</span>
                </div>
                <!-- Email -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.email') }}</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $lead->email ?: '-' }}</span>
                </div>
                <!-- Phone -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.phone') }}</span>
                    <span class="text-sm text-gray-900 dark:text-white" dir="ltr">{{ $lead->phone ?: '-' }}</span>
                </div>
                <!-- Company -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.company') }}</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $lead->company_name ?: '-' }}</span>
                </div>
                <!-- Value -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.estimated_value') }}</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($lead->value ?? 0, 2) }}</span>
                </div>
                <!-- Source -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.source') }}</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $lead->source ? $lead->source->name : '-' }}</span>
                </div>
                <!-- Status Step -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.status_step') }}</span>
                    @if($lead->statusStep)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                            style="background-color: {{ $lead->statusStep->color === 'blue' ? 'rgba(59, 130, 246, 0.1)' : ($lead->statusStep->color === 'yellow' ? 'rgba(245, 158, 11, 0.1)' : ($lead->statusStep->color === 'green' ? 'rgba(16, 185, 129, 0.1)' : ($lead->statusStep->color === 'red' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(139, 92, 246, 0.1)'))) }}; color: {{ $lead->statusStep->color === 'blue' ? '#2563eb' : ($lead->statusStep->color === 'yellow' ? '#d97706' : ($lead->statusStep->color === 'green' ? '#059669' : ($lead->statusStep->color === 'red' ? '#dc2626' : '#7c3aed'))) }}">
                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: currentColor"></span>
                            {{ $lead->statusStep->name }}
                        </span>
                    @else
                        <span class="text-sm text-gray-900 dark:text-white">-</span>
                    @endif
                </div>
                <!-- Assignee -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.assignee') }}</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $lead->assignee ? $lead->assignee->name : '-' }}</span>
                </div>
                <!-- Lead Status -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.lead_status') }}</span>
                    @php
                        $statusClasses = [
                            'active' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400',
                            'inactive' => 'bg-gray-50 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                            'converted' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-400',
                            'lost' => 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400',
                        ];
                    @endphp
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$lead->status] ?? $statusClasses['inactive'] }}">
                        {{ __('crm::crm.status_' . $lead->status) }}
                    </span>
                </div>
                <!-- Created At -->
                <div>
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.created_at') }}</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $lead->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities Section -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('crm::crm.activities') }}</h3>
        </div>
        <div class="p-6">
            <!-- Add Activity Inline Form -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.activity_type') }}</label>
                        <select wire:model="activityType"
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="call">{{ __('crm::crm.type_call') }}</option>
                            <option value="meeting">{{ __('crm::crm.type_meeting') }}</option>
                            <option value="task">{{ __('crm::crm.type_task') }}</option>
                            <option value="email">{{ __('crm::crm.type_email') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.activity_title') }}</label>
                        <input type="text" wire:model="activityTitle" placeholder="{{ __('crm::crm.activity_title_placeholder') }}"
                            class="w-full text-right px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.due_date') }}</label>
                        <input type="date" wire:model="activityDueDate"
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end">
                        <button wire:click="addActivity"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>{{ __('crm::crm.add_activity') }}</span>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.description') }}</label>
                    <textarea wire:model="activityDescription" rows="2" placeholder="{{ __('crm::crm.activity_description_placeholder') }}"
                        class="w-full text-right px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                </div>
                @error('activityTitle') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                @error('activityType') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Pending Activities -->
            @php
                $pendingActivities = $lead->activities->where('status', 'pending');
                $completedActivities = $lead->activities->where('status', 'completed');
            @endphp

            @if($pendingActivities->isNotEmpty())
                <div class="mb-4">
                    <h4 class="text-sm font-bold text-gray-600 dark:text-gray-400 uppercase mb-3">{{ __('crm::crm.pending_activities') }}</h4>
                    <div class="space-y-2">
                        @foreach($pendingActivities as $activity)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-950/20 border border-yellow-100 dark:border-yellow-900/30 rounded-lg">
                                <div class="flex items-center gap-3">
                                    @php
                                        $typeBadgeClasses = [
                                            'call' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'meeting' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                            'task' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                            'email' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $typeBadgeClasses[$activity->type] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ __('crm::crm.type_' . $activity->type) }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->title }}</span>
                                    @if($activity->due_date)
                                        <span class="text-xs text-gray-500">{{ $activity->due_date->format('Y-m-d') }}</span>
                                    @endif
                                </div>
                                <button wire:click="markActivityComplete({{ $activity->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-900/50 rounded-lg transition-colors">
                                    <i data-lucide="check" class="w-3 h-3"></i>
                                    <span>{{ __('crm::crm.mark_complete') }}</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Completed Activities -->
            @if($completedActivities->isNotEmpty())
                <div>
                    <h4 class="text-sm font-bold text-gray-600 dark:text-gray-400 uppercase mb-3">{{ __('crm::crm.completed_activities') }}</h4>
                    <div class="space-y-2">
                        @foreach($completedActivities as $activity)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg opacity-75">
                                <div class="flex items-center gap-3">
                                    @php
                                        $typeBadgeClasses = [
                                            'call' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'meeting' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                            'task' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                            'email' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $typeBadgeClasses[$activity->type] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ __('crm::crm.type_' . $activity->type) }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white line-through">{{ $activity->title }}</span>
                                    @if($activity->due_date)
                                        <span class="text-xs text-gray-500">{{ $activity->due_date->format('Y-m-d') }}</span>
                                    @endif
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-600 dark:text-green-400">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    {{ __('crm::crm.completed') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($lead->activities->isEmpty())
                <div class="text-center py-6 text-gray-500">
                    <i data-lucide="calendar" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                    <span>{{ __('crm::crm.no_activities') }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Notes Section -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('crm::crm.notes') }}</h3>
        </div>
        <div class="p-6">
            <!-- Add Note Inline Form -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6">
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">{{ __('crm::crm.note_content') }}</label>
                    <textarea wire:model="noteContent" rows="3" placeholder="{{ __('crm::crm.note_placeholder') }}"
                        class="w-full text-right px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                    @error('noteContent') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button wire:click="addNote"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>{{ __('crm::crm.add_note') }}</span>
                    </button>
                </div>
            </div>

            <!-- Notes List -->
            @if($lead->notes->isNotEmpty())
                <div class="space-y-3">
                    @foreach($lead->notes->sortByDesc('created_at') as $note)
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg">
                            <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $note->content }}</p>
                            <div class="flex items-center gap-3 mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-xs text-gray-500">
                                    <i data-lucide="user" class="w-3 h-3 inline-block"></i>
                                    {{ $note->user ? $note->user->name : __('crm::crm.unknown_user') }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $note->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-500">
                    <i data-lucide="file-text" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                    <span>{{ __('crm::crm.no_notes') }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Convert Lead Modal -->
    <div x-data="{ open: @entangle('showConvertModal') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="relative flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="open = false" class="absolute inset-0 bg-gray-500/75 dark:bg-gray-950/75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-middle bg-white dark:bg-gray-900 rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 dark:border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ __('crm::crm.convert_lead') }}
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="convert" class="space-y-4">
                        <!-- Convert To Customer Checkbox -->
                        <div class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <input type="checkbox" id="show_conv_customer" wire:model.live="convertToCustomer"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="show_conv_customer" class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ __('crm::crm.convert_to_customer') }}
                            </label>
                        </div>

                        @if($convertToCustomer)
                            <div class="p-4 border border-gray-100 dark:border-gray-800 rounded-lg space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.customer_group') }}</label>
                                    <select wire:model="customerGroupId"
                                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">{{ __('crm::crm.select_customer_group') }}</option>
                                        @foreach($customerGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('customerGroupId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Convert To Opportunity Checkbox -->
                        <div class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <input type="checkbox" id="show_conv_opportunity" wire:model.live="convertToOpportunity"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="show_conv_opportunity" class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ __('crm::crm.convert_to_opportunity') }}
                            </label>
                        </div>

                        @if($convertToOpportunity)
                            <div class="p-4 border border-gray-100 dark:border-gray-800 rounded-lg space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.opportunity_name') }} *</label>
                                    <input type="text" wire:model="opportunityName" required
                                        class="w-full text-right px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('opportunityName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.value') }} *</label>
                                        <input type="number" step="0.01" wire:model="opportunityValue" required
                                            class="w-full text-right px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('opportunityValue') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.close_date') }} *</label>
                                        <input type="date" wire:model="closeDate" required
                                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('closeDate') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.pipeline') }} *</label>
                                        <select wire:model.live="pipelineId" required
                                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">{{ __('crm::crm.select_pipeline') }}</option>
                                            @foreach($pipelines as $pipeline)
                                                <option value="{{ $pipeline->id }}">{{ $pipeline->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('pipelineId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.stage') }} *</label>
                                        <select wire:model="pipelineStageId" required
                                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">{{ __('crm::crm.select_stage') }}</option>
                                            @foreach($stages as $stage)
                                                <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('pipelineStageId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-end gap-2 border-t border-gray-50 dark:border-gray-800 pt-4 mt-6">
                            <button type="button" @click="open = false"
                                class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                {{ __('crm::crm.cancel') }}
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                                {{ __('crm::crm.convert') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
