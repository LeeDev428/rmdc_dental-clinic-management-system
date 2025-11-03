<x-app-layout>
    @section('title', 'History Settings')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('History Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Tabs Navigation -->
                    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg tab-button active" 
                                        id="notification-tab" 
                                        data-tab="notification" 
                                        type="button" 
                                        role="tab">
                                    <i class="fas fa-bell mr-2"></i>Notification History
                                </button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg tab-button" 
                                        id="appointment-tab" 
                                        data-tab="appointment" 
                                        type="button" 
                                        role="tab">
                                    <i class="fas fa-calendar-alt mr-2"></i>Appointment History
                                </button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg tab-button" 
                                        id="billing-tab" 
                                        data-tab="billing" 
                                        type="button" 
                                        role="tab">
                                    <i class="fas fa-file-invoice-dollar mr-2"></i>Billing History
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tabs Content -->
                    <div id="tabs-content">
                        <!-- Notification History Tab -->
                        <div class="tab-content active" id="notification-content" role="tabpanel">
                            @include('history.notification-history')
                        </div>
                        
                        <!-- Appointment History Tab -->
                        <div class="tab-content hidden" id="appointment-content" role="tabpanel">
                            @include('history.appointment-history')
                        </div>
                        
                        <!-- Billing History Tab -->
                        <div class="tab-content hidden" id="billing-content" role="tabpanel">
                            @include('history.billing-history')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tab-button {
            border-color: transparent;
            color: #6b7280;
        }
        
        .tab-button:hover {
            color: #3b82f6;
            border-color: #d1d5db;
        }
        
        .tab-button.active {
            color: #3b82f6;
            border-color: #3b82f6;
        }
        
        .tab-content {
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.add('hidden'));
                    
                    // Add active class to clicked button and show corresponding content
                    this.classList.add('active');
                    document.getElementById(targetTab + '-content').classList.remove('hidden');
                });
            });
        });
    </script>
</x-app-layout>
