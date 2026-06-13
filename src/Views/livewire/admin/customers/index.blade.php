<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('crm::crm.customers') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('crm::crm.manage_contacts_and_organizations') }}</p>
        </div>
        <div>
            @if($activeTab === 'customers')
                <button wire:click="openCustomerCreate"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>{{ __('crm::crm.add_customer') }}</span>
                </button>
            @elseif($activeTab === 'organizations')
                <button wire:click="openOrgCreate"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>{{ __('crm::crm.add_organization') }}</span>
                </button>
            @elseif($activeTab === 'contacts')
                <button wire:click="openContactCreate"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>{{ __('crm::crm.add_contact') }}</span>
                </button>
            @elseif($activeTab === 'groups')
                <button wire:click="openGroupCreate"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>{{ __('crm::crm.add_group') }}</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-100 dark:border-gray-800 mb-6 flex flex-wrap gap-2">
        <button wire:click="$set('activeTab', 'customers')"
            class="px-4 py-2 text-sm font-semibold transition-all border-b-2 {{ $activeTab === 'customers' ? 'border-blue-600 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
            {{ __('crm::crm.customers_tab') }}
        </button>
        <button wire:click="$set('activeTab', 'organizations')"
            class="px-4 py-2 text-sm font-semibold transition-all border-b-2 {{ $activeTab === 'organizations' ? 'border-blue-600 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
            {{ __('crm::crm.organizations_tab') }}
        </button>
        <button wire:click="$set('activeTab', 'contacts')"
            class="px-4 py-2 text-sm font-semibold transition-all border-b-2 {{ $activeTab === 'contacts' ? 'border-blue-600 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
            {{ __('crm::crm.contacts_tab') }}
        </button>
        <button wire:click="$set('activeTab', 'groups')"
            class="px-4 py-2 text-sm font-semibold transition-all border-b-2 {{ $activeTab === 'groups' ? 'border-blue-600 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
            {{ __('crm::crm.groups_tab') }}
        </button>
    </div>

    @if($activeTab !== 'groups')
        <!-- Search -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 mb-6 shadow-sm max-w-md">
            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('crm::crm.search') }}</label>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('crm::crm.search_placeholder') }}"
                    class="w-full text-right pl-3 pr-10 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
            </div>
        </div>
    @endif

    <!-- Content Tables -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-6">
        @if($activeTab === 'customers')
            <!-- Customers Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.name') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.contact_info') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.group') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.organization') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.status') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">{{ __('crm::crm.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($items as $cust)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $cust->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 dark:text-gray-300">{{ $cust->email ?: '-' }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5" dir="ltr">{{ $cust->phone ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $cust->group ? $cust->group->name : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $cust->organization ? $cust->organization->name : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="toggleCustomerStatus({{ $cust->id }})"
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold transition-all duration-200 hover:opacity-80 active:scale-95 {{ $cust->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-50 text-gray-650 dark:bg-gray-800 dark:text-gray-400' }}">
                                        {{ __('crm::crm.status_' . $cust->status) }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button wire:click="openCustomerEdit({{ $cust->id }})" title="{{ __('crm::crm.edit') }}"
                                            class="p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <button 
                                            wire:click="$dispatch('swal:confirm', { 
                                                title: '{{ __('crm::crm.delete_customer_title') }}',
                                                text: '{{ __('crm::crm.delete_customer_confirm') }}',
                                                onConfirm: 'deleteCustomer',
                                                params: { id: {{ $cust->id }} }
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
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i data-lucide="database" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                    <span>{{ __('crm::crm.no_customers') }}</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @elseif($activeTab === 'organizations')
            <!-- Organizations Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.org_name') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.contact_info') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.website') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.status') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">{{ __('crm::crm.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($items as $org)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $org->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 dark:text-gray-300">{{ $org->email ?: '-' }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5" dir="ltr">{{ $org->phone ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $org->website ?: '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="toggleOrgStatus({{ $org->id }})"
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold transition-all duration-200 hover:opacity-80 active:scale-95 {{ $org->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-50 text-gray-650 dark:bg-gray-800 dark:text-gray-400' }}">
                                        {{ __('crm::crm.status_' . $org->status) }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button wire:click="openOrgEdit({{ $org->id }})" title="{{ __('crm::crm.edit') }}"
                                            class="p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <button 
                                            wire:click="$dispatch('swal:confirm', { 
                                                title: '{{ __('crm::crm.delete_org_title') }}',
                                                text: '{{ __('crm::crm.delete_org_confirm') }}',
                                                onConfirm: 'deleteOrg',
                                                params: { id: {{ $org->id }} }
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
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i data-lucide="database" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                    <span>{{ __('crm::crm.no_organizations') }}</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @elseif($activeTab === 'contacts')
            <!-- Contacts Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.contact_name') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.contact_info') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.organization') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.job_title') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.status') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">{{ __('crm::crm.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($items as $con)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $con->fullName }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 dark:text-gray-300">{{ $con->email ?: '-' }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5" dir="ltr">{{ $con->phone ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $con->organization ? $con->organization->name : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $con->job_title ?: '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="toggleContactStatus({{ $con->id }})"
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold transition-all duration-200 hover:opacity-80 active:scale-95 {{ $con->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-50 text-gray-655 dark:bg-gray-800 dark:text-gray-400' }}">
                                        {{ __('crm::crm.status_' . $con->status) }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button wire:click="openContactEdit({{ $con->id }})" title="{{ __('crm::crm.edit') }}"
                                            class="p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <button 
                                            wire:click="$dispatch('swal:confirm', { 
                                                title: '{{ __('crm::crm.delete_contact_title') }}',
                                                text: '{{ __('crm::crm.delete_contact_confirm') }}',
                                                onConfirm: 'deleteContact',
                                                params: { id: {{ $con->id }} }
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
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i data-lucide="database" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                    <span>{{ __('crm::crm.no_contacts') }}</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @elseif($activeTab === 'groups')
            <!-- Customer Groups Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('crm::crm.group_name') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">{{ __('crm::crm.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($groups as $grp)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $grp->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button wire:click="openGroupEdit({{ $grp->id }})" title="{{ __('crm::crm.edit') }}"
                                            class="p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <button 
                                            wire:click="$dispatch('swal:confirm', { 
                                                title: '{{ __('crm::crm.delete_group_title') }}',
                                                text: '{{ __('crm::crm.delete_group_confirm') }}',
                                                onConfirm: 'deleteGroup',
                                                params: { id: {{ $grp->id }} }
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
                                <td colspan="2" class="px-6 py-12 text-center text-gray-500">
                                    <i data-lucide="database" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                    <span>{{ __('crm::crm.no_groups') }}</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if($activeTab !== 'groups' && $items->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Customer Form Modal -->
    <div x-data="{ open: @entangle('showCustomerModal') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="relative flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="open = false" class="absolute inset-0 bg-gray-500/75 dark:bg-gray-950/75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-middle bg-white dark:bg-gray-900 rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 dark:border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $customerId ? __('crm::crm.edit_customer') : __('crm::crm.add_customer') }}
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveCustomer" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.customer_name') }} *</label>
                            <input type="text" wire:model="customerName" required
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('customerName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.email') }}</label>
                                <input type="email" wire:model="customerEmail"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('customerEmail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.phone') }}</label>
                                <input type="text" wire:model="customerPhone"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('customerPhone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.address') }}</label>
                            <input type="text" wire:model="customerAddress"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('customerAddress') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.customer_group') }}</label>
                                <select wire:model="customerGroupId"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_customer_group') }}</option>
                                    @foreach($groups as $grp)
                                        <option value="{{ $grp->id }}">{{ $grp->name }}</option>
                                    @endforeach
                                </select>
                                @error('customerGroupId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.status') }}</label>
                                <select wire:model="customerStatus"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="active">{{ __('crm::crm.active') }}</option>
                                    <option value="inactive">{{ __('crm::crm.inactive') }}</option>
                                </select>
                                @error('customerStatus') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.organization') }}</label>
                                <select wire:model="customerOrgId"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_org') }}</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                                    @endforeach
                                </select>
                                @error('customerOrgId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.contact_person') }}</label>
                                <select wire:model="customerContactId"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_contact') }}</option>
                                    @foreach($contacts as $con)
                                        <option value="{{ $con->id }}">{{ $con->fullName }}</option>
                                    @endforeach
                                </select>
                                @error('customerContactId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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

    <!-- Organization Form Modal -->
    <div x-data="{ open: @entangle('showOrgModal') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="relative flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="open = false" class="absolute inset-0 bg-gray-500/75 dark:bg-gray-950/75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-middle bg-white dark:bg-gray-900 rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 dark:border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $orgId ? __('crm::crm.edit_organization') : __('crm::crm.add_organization') }}
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveOrg" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.org_name') }} *</label>
                            <input type="text" wire:model="orgName" required
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('orgName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.email') }}</label>
                                <input type="email" wire:model="orgEmail"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('orgEmail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.phone') }}</label>
                                <input type="text" wire:model="orgPhone"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('orgPhone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.website') }}</label>
                            <input type="text" wire:model="orgWebsite" placeholder="https://example.com"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('orgWebsite') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.address') }}</label>
                                <input type="text" wire:model="orgAddress"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('orgAddress') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.status') }}</label>
                                <select wire:model="orgStatus"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="active">{{ __('crm::crm.active') }}</option>
                                    <option value="inactive">{{ __('crm::crm.inactive') }}</option>
                                </select>
                                @error('orgStatus') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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

    <!-- Contact Form Modal -->
    <div x-data="{ open: @entangle('showContactModal') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="relative flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="open = false" class="absolute inset-0 bg-gray-500/75 dark:bg-gray-950/75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-middle bg-white dark:bg-gray-900 rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 dark:border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $contactId ? __('crm::crm.edit_contact') : __('crm::crm.add_contact') }}
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveContact" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.first_name') }} *</label>
                                <input type="text" wire:model="contactFirstName" required
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('contactFirstName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.last_name') }}</label>
                                <input type="text" wire:model="contactLastName"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('contactLastName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.email') }}</label>
                                <input type="email" wire:model="contactEmail"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('contactEmail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.phone') }}</label>
                                <input type="text" wire:model="contactPhone"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('contactPhone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.job_title') }}</label>
                                <input type="text" wire:model="contactJobTitle"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('contactJobTitle') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.organization') }}</label>
                                <select wire:model="contactOrgId"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('crm::crm.select_org') }}</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                                    @endforeach
                                </select>
                                @error('contactOrgId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.status') }}</label>
                            <select wire:model="contactStatus"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active">{{ __('crm::crm.active') }}</option>
                                <option value="inactive">{{ __('crm::crm.inactive') }}</option>
                            </select>
                            @error('contactStatus') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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

    <!-- Customer Group Form Modal -->
    <div x-data="{ open: @entangle('showGroupModal') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="relative flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="open = false" class="absolute inset-0 bg-gray-500/75 dark:bg-gray-950/75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-middle bg-white dark:bg-gray-900 rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 dark:border-gray-800 pb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $groupId ? __('crm::crm.edit_group') : __('crm::crm.add_group') }}
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveGroup" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('crm::crm.group_name') }} *</label>
                            <input type="text" wire:model="groupName" required
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('groupName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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
