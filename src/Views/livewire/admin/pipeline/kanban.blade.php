<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('crm::crm.kanban') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('crm::crm.pipeline_kanban_desc') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Pipeline Selector -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('crm::crm.pipeline') }}:</label>
                <select wire:model.live="selectedPipelineId"
                    class="py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    @foreach($pipelines as $pipeline)
                        <option value="{{ $pipeline->id }}">{{ $pipeline->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="overflow-x-auto pb-6">
        <div class="flex gap-4 min-w-[900px] items-start"
             x-data="{
                dragging: null,
                dragStage: null,
                init() {
                    this.initSortable();
                },
                initSortable() {
                    this.$nextTick(() => {
                        document.querySelectorAll('[data-stage-column]').forEach(column => {
                            if (column._sortableInitialized) return;
                            column._sortableInitialized = true;

                            column.addEventListener('dragover', (e) => {
                                e.preventDefault();
                                column.classList.add('bg-blue-50/50', 'dark:bg-blue-900/10');
                            });
                            column.addEventListener('dragleave', () => {
                                column.classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
                            });
                            column.addEventListener('drop', (e) => {
                                e.preventDefault();
                                column.classList.remove('bg-blue-50/50', 'dark:bg-blue-900/10');
                                const opportunityId = e.dataTransfer.getData('text/plain');
                                const newStageId = column.dataset.stageColumn;
                                if (opportunityId && newStageId) {
                                    $wire.moveOpportunity(parseInt(opportunityId), parseInt(newStageId));
                                }
                            });
                        });
                    });
                }
             }"
             x-init="init()"
             wire:ignore.self>

            @foreach($stages as $stage)
                <!-- Stage Column -->
                <div class="flex-1 min-w-[280px] max-w-[360px] bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-xl p-4 flex flex-col max-h-[75vh]">
                    <!-- Column Header -->
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200/50 dark:border-gray-800">
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $stage->name }}</h4>
                            <span class="text-xs text-gray-400 font-medium">
                                {{ count($opportunities[$stage->id] ?? []) }} {{ __('crm::crm.deals') }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 text-xs font-bold text-gray-600 dark:text-gray-300">
                                {{ count($opportunities[$stage->id] ?? []) }}
                            </span>
                        </div>
                    </div>

                    <!-- Cards Container (Drop Zone) -->
                    <div class="flex-1 overflow-y-auto space-y-3 pr-1 min-h-[100px] transition-colors rounded-lg"
                         data-stage-column="{{ $stage->id }}">

                        @forelse($opportunities[$stage->id] ?? [] as $opp)
                            @php
                                $opp = is_array($opp) ? (object) $opp : $opp;
                                $customer = isset($opp->customer) ? (is_array($opp->customer) ? (object) $opp->customer : $opp->customer) : null;
                            @endphp
                            <!-- Opportunity Card -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-3 cursor-grab active:cursor-grabbing hover:shadow-md transition-shadow"
                                 draggable="true"
                                 wire:key="opp-{{ $opp->id }}"
                                 x-on:dragstart="
                                    dragging = {{ $opp->id }};
                                    $event.dataTransfer.setData('text/plain', '{{ $opp->id }}');
                                    $event.dataTransfer.effectAllowed = 'move';
                                 "
                                 x-on:dragend="dragging = null">

                                <!-- Name -->
                                <h5 class="font-semibold text-gray-900 dark:text-white text-sm leading-snug mb-2 text-right">
                                    {{ $opp->name }}
                                </h5>

                                <!-- Value -->
                                <div class="text-sm font-extrabold text-blue-600 dark:text-blue-400 mb-2 text-right">
                                    {{ number_format($opp->value ?? 0, 2) }}
                                </div>

                                <!-- Details -->
                                <div class="space-y-1.5 border-t border-gray-100 dark:border-gray-700 pt-2 text-xs text-gray-500">
                                    @if($customer)
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <span class="font-medium text-gray-700 dark:text-gray-300 truncate">{{ $customer->name }}</span>
                                        </div>
                                    @endif

                                    @if(isset($opp->close_date) && $opp->close_date)
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <span>{{ is_string($opp->close_date) ? $opp->close_date : (is_object($opp->close_date) && method_exists($opp->close_date, 'format') ? $opp->close_date->format('Y/m/d') : $opp->close_date) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Move Buttons (fallback for non-drag) -->
                                <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between gap-2">
                                    <div class="flex gap-1">
                                        @php
                                            $stageIds = $stages->pluck('id')->toArray();
                                            $currentIndex = array_search($stage->id, $stageIds);
                                            $prevStageId = $currentIndex > 0 ? $stageIds[$currentIndex - 1] : null;
                                            $nextStageId = $currentIndex < count($stageIds) - 1 ? $stageIds[$currentIndex + 1] : null;
                                        @endphp

                                        @if($prevStageId)
                                            <button wire:click="moveOpportunity({{ $opp->id }}, {{ $prevStageId }})"
                                                class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors"
                                                title="{{ __('crm::crm.move_to') }} {{ $stages->firstWhere('id', $prevStageId)?->name }}">
                                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                            </button>
                                        @else
                                            <span class="w-6"></span>
                                        @endif

                                        @if($nextStageId)
                                            <button wire:click="moveOpportunity({{ $opp->id }}, {{ $nextStageId }})"
                                                class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors"
                                                title="{{ __('crm::crm.move_to') }} {{ $stages->firstWhere('id', $nextStageId)?->name }}">
                                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                            </button>
                                        @else
                                            <span class="w-6"></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- Empty State -->
                            <div class="py-8 text-center text-gray-400 text-xs border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-lg">
                                <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-2 text-gray-300 dark:text-gray-600"></i>
                                <span>{{ __('crm::crm.no_opportunities_in_stage') }}</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Empty Pipelines State -->
    @if($pipelines instanceof \Illuminate\Support\Collection && $pipelines->isEmpty())
        <div class="text-center py-16">
            <i data-lucide="git-branch" class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600"></i>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('crm::crm.no_pipelines') }}</h3>
            <p class="text-sm text-gray-500">{{ __('crm::crm.no_pipelines_desc') }}</p>
        </div>
    @endif
</div>
