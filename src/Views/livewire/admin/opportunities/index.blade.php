<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('crm::crm.opportunities') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('crm::crm.pipeline_kanban_desc') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Pipeline Switcher -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('crm::crm.pipeline') }}:</label>
                <select wire:model.live="pipelineId"
                    class="py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    @foreach($pipelines as $pipe)
                        <option value="{{ $pipe->id }}">{{ $pipe->name }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="openOpportunityCreate()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>{{ __('crm::crm.add_deal') }}</span>
            </button>
        </div>
    </div>

    <!-- Kanban Board Scroll Wrapper -->
    <div class="overflow-x-auto pb-6">
        <div class="flex gap-4 min-w-[1000px] items-start">
            @foreach($stages as $stage)
                <!-- Kanban Column -->
                <div class="flex-1 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-xl p-4 flex flex-col max-h-[80vh]">
                    <!-- Column Header -->
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200/50 dark:border-gray-800">
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $stage->name }}</h4>
                            <span class="text-xs text-gray-400 font-medium">
                                {{ count($pipelineData[$stage->id] ?? []) }} {{ __('crm::crm.deals') }}
                            </span>
                        </div>
                        <button wire:click="openOpportunityCreate({{ $stage->id }})"
                            class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-800 rounded-lg transition-colors"
                            title="{{ __('crm::crm.add_deal') }}">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex-1 overflow-y-auto space-y-3 pr-1">
                        @forelse($pipelineData[$stage->id] ?? [] as $opp)
                            <!-- Deal Card -->
                            <div class="bg-white dark:bg-gray-850 p-4 rounded-lg border border-gray-150/40 dark:border-gray-800 shadow-xs hover:shadow-md transition-shadow relative group">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <h5 class="font-bold text-gray-900 dark:text-white text-sm leading-snug">
                                        {{ $opp->name }}
                                    </h5>
                                    <!-- Status badge -->
                                    @if($opp->status !== 'open')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $opp->status === 'won' ? 'bg-green-50 text-green-700 dark:bg-green-950/20' : 'bg-red-50 text-red-750 dark:bg-red-950/20' }}">
                                            {{ __('crm::crm.opp_status_' . $opp->status) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="text-sm font-extrabold text-blue-600 dark:text-blue-400 mb-3">
                                    {{ number_format($opp->value, 2) }}
                                </div>

                                <div class="space-y-1.5 border-t border-gray-100 dark:border-gray-800/80 pt-2.5 text-xs text-gray-500">
                                    @if($opp->customer)
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <span class="font-semibold text-gray-700 dark:text-gray-300 truncate">{{ $opp->customer->name }}</span>
                                        </div>
                                    @endif
                                    @if($opp->owner)
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="shield" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <span class="truncate">{{ $opp->owner->name }}</span>
                                        </div>
                                    @endif
                                    @if($opp->close_date)
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <span>{{ $opp->close_date->format('Y/m/d') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons on Hover/Focus -->
                                <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-850 flex items-center justify-between gap-2">
                                    <!-- Move Left/Right Buttons for non-drag UX -->
                                    <div class="flex gap-1">
                                        @php
                                            $currentIndex = $stages->pluck('id')->search($stage->id);
                                            $prevStage = $currentIndex > 0 ? $stages[$currentIndex - 1] : null;
                                            $nextStage = $currentIndex < count($stages) - 1 ? $stages[$currentIndex + 1] : null;
                                        @endphp
                                        
                                        @if($prevStage)
                                            <button wire:click="moveStage({{ $opp->id }}, {{ $prevStage->id }})"
                                                class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded text-gray-400 hover:text-gray-700"
                                                title="{{ __('crm::crm.move_to') }} {{ $prevStage->name }}">
                                                <i data-lucide="chevron-right" class="w-4 h-4"></i> <!-- RTL arrow -->
                                            </button>
                                        @else
                                            <span class="w-6"></span>
                                        @endif

                                        @if($nextStage)
                                            <button wire:click="moveStage({{ $opp->id }}, {{ $nextStage->id }})"
                                                class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded text-gray-400 hover:text-gray-700"
                                                title="{{ __('crm::crm.move_to') }} {{ $nextStage->name }}">
                                                <i data-lucide="chevron-left" class="w-4 h-4"></i> <!-- RTL arrow -->
                                            </button>
                                        @else
                                            <span class="w-6"></span>
                                        @endif
                                    </div>

                                    <!-- Edit & Delete -->
                                    <div class="flex gap-1">
                                        <button wire:click="openOpportunityEdit({{ $opp->id }})"
                                            class="p-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button 
                                            wire:click="$dispatch('swal:confirm', { 
                                                title: '{{ __('crm::crm.delete_opp_title') }}',
                                                text: '{{ __('crm::crm.delete_opp_confirm') }}',
                                                onConfirm: 'deleteOpportunity',
                                                params: { id: {{ $opp->id }} }
                                            })"
                                            class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @comment
                        @empty
                            <div class="py-8 text-center text-gray-400 text-xs border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-lg">
                                {{ __('crm::crm.no_deals_in_stage') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Opportunity Create/Edit Modal -->
    <div x-data="{ open: @entangle('showOpportunityModal') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="relative flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="open = false" class="absolute inset-0 bg-gray-500/75 dark:bg-gray-950/75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-middle bg-white dark:bg-gray-900 rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 dark:border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $opportunityId ? __('crm::crm.edit_deal') : __('crm::crm.add_deal') }}
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveOpportunity" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.deal_name') }} *</label>
                            <input type="text" wire:model="name" required
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.value') }} *</label>
                                <input type="number" step="0.01" wire:model="value" required
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('value') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.close_date') }}</label>
                                <input type="date" wire:model="closeDate"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('closeDate') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.pipeline_stage') }} *</label>
                                <select wire:model="selectedStageId" required
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_stage') }}</option>
                                    @foreach($stages as $stageOpt)
                                        <option value="{{ $stageOpt->id }}">{{ $stageOpt->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedStageId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.deal_status') }}</label>
                                <select wire:model="status"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="open">{{ __('crm::crm.opp_status_open') }}</option>
                                    <option value="won">{{ __('crm::crm.opp_status_won') }}</option>
                                    <option value="lost">{{ __('crm::crm.opp_status_lost') }}</option>
                                </select>
                                @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.customer') }}</label>
                                <select wire:model="customerId"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_customer') }}</option>
                                    @foreach($customers as $cust)
                                        <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                                    @endforeach
                                </select>
                                @error('customerId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.owner') }}</label>
                                <select wire:model="userId"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_owner') }}</option>
                                    @foreach($users as $usr)
                                        <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                    @endforeach
                                </select>
                                @error('userId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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
</div>
