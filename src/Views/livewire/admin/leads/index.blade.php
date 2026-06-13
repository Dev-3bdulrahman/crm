<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('crm::crm.leads') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('crm::crm.manage_leads_and_sources') }}</p>
        </div>
        <button wire:click="openCreateModal"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>{{ __('crm::crm.add_lead') }}</span>
        </button>
    </div>

    <!-- Filters Panel -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 mb-6 shadow-sm">
        <div class="flex flex-wrap items-end gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('crm::crm.search') }}</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('crm::crm.search_placeholder') }}"
                        class="w-full text-right pl-3 pr-10 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                </div>
            </div>

            <!-- Source -->
            <div class="w-full sm:w-auto sm:min-w-[160px]">
                <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('crm::crm.source') }}</label>
                <select wire:model.live="sourceId"
                    class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <option value="">{{ __('crm::crm.all_sources') }}</option>
                    @foreach($sources as $src)
                        <option value="{{ $src->id }}">{{ $src->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Step -->
            <div class="w-full sm:w-auto sm:min-w-[160px]">
                <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('crm::crm.status_step') }}</label>
                <select wire:model.live="statusId"
                    class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <option value="">{{ __('crm::crm.all_stages') }}</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Assignee -->
            <div class="w-full sm:w-auto sm:min-w-[160px]">
                <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('crm::crm.assignee') }}</label>
                <select wire:model.live="assigneeId"
                    class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <option value="">{{ __('crm::crm.all_assignees') }}</option>
                    @foreach($users as $usr)
                        <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Lead Status -->
            <div class="w-full sm:w-auto sm:min-w-[160px]">
                <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('crm::crm.lead_status') }}</label>
                <select wire:model.live="leadStatus"
                    class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <option value="">{{ __('crm::crm.all_statuses') }}</option>
                    <option value="active">{{ __('crm::crm.active') }}</option>
                    <option value="inactive">{{ __('crm::crm.inactive') }}</option>
                    <option value="converted">{{ __('crm::crm.converted') }}</option>
                    <option value="lost">{{ __('crm::crm.lost') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.title_name') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.contact_info') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.company') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.value') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.source') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.status_step') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.assignee') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.lead_status') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">{{ __('crm::crm.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $lead->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $lead->title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 dark:text-gray-300">{{ $lead->email ?: '-' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5" dir="ltr">{{ $lead->phone ?: '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $lead->company_name ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ number_format($lead->value ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $lead->source ? $lead->source->name : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($lead->statusStep)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                                        style="background-color: {{ $lead->statusStep->color === 'blue' ? 'rgba(59, 130, 246, 0.1)' : ($lead->statusStep->color === 'yellow' ? 'rgba(245, 158, 11, 0.1)' : ($lead->statusStep->color === 'green' ? 'rgba(16, 185, 129, 0.1)' : ($lead->statusStep->color === 'red' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(139, 92, 246, 0.1)'))) }}; color: {{ $lead->statusStep->color === 'blue' ? '#2563eb' : ($lead->statusStep->color === 'yellow' ? '#d97706' : ($lead->statusStep->color === 'green' ? '#059669' : ($lead->statusStep->color === 'red' ? '#dc2626' : '#7c3aed'))) }}">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: currentColor"></span>
                                        {{ $lead->statusStep->name }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $lead->assignee ? $lead->assignee->name : '-' }}
                            </td>
                            <td class="px-6 py-4">
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
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    @if($lead->status !== 'converted')
                                        <button wire:click="openConvertModal({{ $lead->id }})" title="{{ __('crm::crm.convert') }}"
                                            class="p-2 text-gray-500 hover:text-purple-600 dark:hover:text-purple-400 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors">
                                            <i data-lucide="git-commit" class="w-4 h-4"></i>
                                        </button>
                                    @endif
                                    <button wire:click="openEditModal({{ $lead->id }})" title="{{ __('crm::crm.edit') }}"
                                        class="p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <button 
                                        wire:click="$dispatch('swal:confirm', { 
                                            title: '{{ __('crm::crm.delete_lead_title') }}',
                                            text: '{{ __('crm::crm.delete_lead_confirm') }}',
                                            onConfirm: 'delete',
                                            params: { id: {{ $lead->id }} }
                                        })"
                                        title="{{ __('crm::crm.delete') }}"
                                        class="p-2 text-gray-500 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i data-lucide="database" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                <span>{{ __('crm::crm.no_leads') }}</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $leads->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Lead Modal -->
    <div x-data="{ open: @entangle('showFormModal') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="relative flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="open = false" class="absolute inset-0 bg-gray-500/75 dark:bg-gray-950/75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-middle bg-white dark:bg-gray-900 rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 dark:border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $leadId ? __('crm::crm.edit_lead') : __('crm::crm.add_lead') }}
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.title') }} *</label>
                            <input type="text" wire:model="title" required
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.lead_name') }} *</label>
                            <input type="text" wire:model="name" required
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.email') }}</label>
                                <input type="email" wire:model="email"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.phone') }}</label>
                                <input type="text" wire:model="phone"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.company') }}</label>
                                <input type="text" wire:model="companyName"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('companyName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.estimated_value') }}</label>
                                <input type="number" step="0.01" wire:model="value"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('value') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.source') }}</label>
                                <select wire:model="selectedSourceId"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_source') }}</option>
                                    @foreach($sources as $src)
                                        <option value="{{ $src->id }}">{{ $src->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedSourceId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.status_step') }}</label>
                                <select wire:model="selectedStatusId"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_status') }}</option>
                                    @foreach($statuses as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedStatusId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.assignee') }}</label>
                                <select wire:model="selectedAssigneeId"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_assignee') }}</option>
                                    @foreach($users as $usr)
                                        <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedAssigneeId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.lead_status') }}</label>
                                <select wire:model="status"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="active">{{ __('crm::crm.active') }}</option>
                                    <option value="inactive">{{ __('crm::crm.inactive') }}</option>
                                    <option value="converted">{{ __('crm::crm.converted') }}</option>
                                    <option value="lost">{{ __('crm::crm.lost') }}</option>
                                </select>
                                @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 border-t border-gray-50 dark:border-gray-800 pt-4 mt-6">
                            <button type="button" @click="open = false"
                                class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                {{ __('crm::crm.cancel') }}
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                                {{ __('crm::crm.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                            <input type="checkbox" id="conv_customer" wire:model.live="convertToCustomer"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="conv_customer" class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ __('crm::crm.convert_to_customer') }}
                            </label>
                        </div>

                        @if($convertToCustomer)
                            <div class="p-4 border border-gray-100 dark:border-gray-850 rounded-lg space-y-3">
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
                            <input type="checkbox" id="conv_opportunity" wire:model.live="convertToOpportunity"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="conv_opportunity" class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ __('crm::crm.convert_to_opportunity') }}
                            </label>
                        </div>

                        @if($convertToOpportunity)
                            <div class="p-4 border border-gray-100 dark:border-gray-850 rounded-lg space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.opportunity_name') }} *</label>
                                    <input type="text" wire:model="opportunityName" required
                                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('opportunityName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.value') }} *</label>
                                        <input type="number" step="0.01" wire:model="opportunityValue" required
                                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                                            @foreach($pipelines as $pipe)
                                                <option value="{{ $pipe->id }}">{{ $pipe->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('pipelineId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.pipeline_stage') }} *</label>
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
