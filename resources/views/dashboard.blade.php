<x-app-layout>
    @section('title', 'Dashboard')
    <x-toast-notification />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <style>
        html {
            scroll-behavior: smooth;
        }
        .quick-action-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-blue-800 overflow-hidden shadow-sm sm:rounded-lg">
                <img src="{{ asset('img/udashboard.png') }}" alt="Description" loading="lazy">
                <div class="flex justify-between items-center p-3 text-black-900 dark:text-black-100" style="background-color: rgb(187, 233, 233); font-size: 14px;">
                    <span>{{ __("You're logged in, ".auth()->user()->name. "!") }}</span>
                    <button id="view-teeth-layout-btn" class="btn btn-primary" style="font-size: 14px;">
                        View My Teeth Layout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Teeth Layout Modal -->
    <div id="teeth-layout-modal" class="modal" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">My Teeth Layout</h5>
                    <button type="button" class="close" onclick="closeTeethLayoutModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="teeth-layout-content" class="svg-dental-chart" style="height: 600px; overflow-y: auto;">
                        <svg id="teeth-chart" viewBox="0 0 400 700" xmlns="http://www.w3.org/2000/svg">
                            <!-- Quadrant lines -->
                            <line x1="200" y1="60" x2="200" y2="640" stroke="#a084ca" stroke-width="3" stroke-dasharray="8,6"/>
                            <line x1="40" y1="350" x2="360" y2="350" stroke="#a084ca" stroke-width="3" stroke-dasharray="8,6"/>
                        </svg>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeTeethLayoutModal()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Statistics Cards -->
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Appointments Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Appointments</p>
                        <h3 class="text-3xl font-bold mt-2">{{ \App\Models\Appointment::where('user_id', auth()->id())->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Appointments Card -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Pending</p>
                        <h3 class="text-3xl font-bold mt-2">{{ \App\Models\Appointment::where('user_id', auth()->id())->where('status', 'pending')->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Accepted Appointments Card -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Accepted</p>
                        <h3 class="text-3xl font-bold mt-2">{{ \App\Models\Appointment::where('user_id', auth()->id())->where('status', 'accepted')->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Messages Card -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Messages</p>
                        <h3 class="text-3xl font-bold mt-2">{{ \App\Models\Message::where('user_id', auth()->id())->count() }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- <div class="text-center font-semibold text-2xl text-gray-800 dark:text-white mb-6">
            Quick Actions
        </div> --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Book Appointment -->
            {{-- <a href="{{ route('appointments') }}" class="quick-action-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:bg-blue-50 dark:hover:bg-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-4 mb-4">
                        <i class="fas fa-calendar-plus text-3xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Book Appointment</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Schedule your next dental visit</p>
                </div>
            </a> --}}

            {{-- <!-- View Messages -->
            <a href="{{ route('messages.index') }}" class="quick-action-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:bg-purple-50 dark:hover:bg-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-4 mb-4">
                        <i class="fas fa-comments text-3xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">View Messages</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Check messages from dentist</p>
                </div>
            </a> --}}

            {{-- <!-- View History -->
            <a href="{{ route('usersettings') }}" class="quick-action-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:bg-green-50 dark:hover:bg-gray-700">
                <div class="flex flex-col items-center">
                    <div class="bg-green-100 dark:bg-green-900 rounded-full p-4 mb-4">
                        <i class="fas fa-history text-3xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">View History</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Access appointment history</p>
                </div>
            </a> --}}
        </div>
    </div>

    <!-- Automatically Display Notifications and Appointment Details in One Card -->
    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <!-- Appointment Section with Clean Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/dcms_iconmini(1).png') }}" alt="Logo" class="w-20 h-20">
            </div>
            <h1 class="text-3xl font-bold text-gray-800">
                <span class="text-blue-600">Your</span> Appointment Details
            </h1>
            <p class="text-sm text-gray-500 mt-2">Invoice and Payment Information</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-8" id="billing-invoice">

            <!-- Invoice Header -->
            <div class="flex justify-between items-start border-b-2 border-gray-200 pb-6 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Billing Invoice</h2>
                    <p class="text-sm text-gray-500">Issued: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 mb-2">Invoice Number</div>
                    <h3 class="text-xl font-bold text-gray-800">
                        #{{ $appointments ? str_pad($appointments->id, 6, '0', STR_PAD_LEFT) : 'N/A' }}
                    </h3>
                    <div class="mt-3">
                        <span class="inline-flex px-3 py-1.5 text-xs font-semibold rounded-full
                        @if($appointments && $appointments->status == 'pending')
                            bg-yellow-100 text-yellow-700 border border-yellow-300
                        @elseif($appointments && $appointments->status == 'accepted')
                            bg-green-100 text-green-700 border border-green-300
                        @elseif($appointments && $appointments->status == 'declined')
                            bg-red-100 text-red-700 border border-red-300
                        @else
                            bg-gray-100 text-gray-700 border border-gray-300
                        @endif">
                        {{ $appointments ? ucfirst($appointments->status) : 'N/A' }}
                    </span>
                    </div>
                </div>
            </div>

            <!-- Clinic & Patient Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="bg-blue-50 p-5 rounded-lg border border-blue-100">
                    <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wide mb-3">Clinic Information</h4>
                    <p class="font-bold text-gray-800 text-base mb-2">RMDC - Robles Moncayo Dental Clinic</p>
                    <p class="text-sm text-gray-600 leading-relaxed">Unit F Medina Bldg, Niog Elementary School<br>
                    Bacoor, Cavite, Philippines</p>
                    <div class="mt-4 space-y-1">
                        <p class="text-sm text-gray-600"><span class="font-medium">Email:</span> robles_moncayo@yahoo.com</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Phone:</span> (+63) 912-3456-789</p>
                    </div>
                </div>
                <div class="bg-green-50 p-5 rounded-lg border border-green-100">
                    <h4 class="text-sm font-semibold text-green-800 uppercase tracking-wide mb-3">Patient Information</h4>
                    <div class="space-y-2">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wide">Patient Name</span>
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wide">Email Address</span>
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wide">Attending Doctor</span>
                            <p id="doctor-name" class="text-sm font-semibold text-gray-800">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                fetch('/admin/details')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("doctor-name").innerText = data.name || "Dr. Unknown";
                    })
                    .catch(error => console.error("Error fetching admin:", error));
            </script>

            <!-- Procedure Details -->
            <div class="mb-8">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Appointment Details</h4>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Schedule & Duration</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4">
                                    <p class="text-sm font-semibold text-gray-800">{{ $appointments->procedure ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Dental Procedure</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Date:</span> 
                                            {{ $appointments && $appointments->start ? \Carbon\Carbon::parse($appointments->start)->format('F j, Y') : 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Time:</span> 
                                            {{ $appointments && $appointments->start ? \Carbon\Carbon::parse($appointments->start)->format('g:i A') : 'N/A' }} - 
                                            {{ $appointments && $appointments->end ? \Carbon\Carbon::parse($appointments->end)->format('g:i A') : 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Duration:</span> <span id="estimated-time" class="text-blue-600 font-semibold"></span>
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <p class="text-lg font-bold text-gray-800">₱<span id="procedure-price"></span></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="mb-8">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Payment Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Payment Method</span>
                                @if($appointments && $appointments->payment_method)
                                    <span class="text-sm font-semibold text-gray-800 uppercase">{{ $appointments->payment_method }}</span>
                                @else
                                    <span class="text-sm text-gray-400">Not specified</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Reference ID</span>
                                @if($appointments && $appointments->payment_reference)
                                    <span class="text-xs font-mono text-gray-600">{{ $appointments->payment_reference }}</span>
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-300">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Status</span>
                                @if($appointments && $appointments->payment_status)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $appointments->payment_status === 'paid' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-yellow-100 text-yellow-700 border border-yellow-300' }}">
                                        {{ strtoupper($appointments->payment_status) }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">Pending</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Booked On</span>
                                <span class="text-sm font-medium text-gray-800">
                                    {{ $appointments && $appointments->created_at ? $appointments->created_at->format('M j, Y g:i A') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Breakdown -->
                    <div class="bg-blue-50 p-5 rounded-lg border border-blue-100">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Total Amount</span>
                                <span class="text-lg font-bold text-gray-800">₱<span id="total-price"></span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Down Payment (20%)</span>
                                <span class="text-lg font-semibold text-green-600">
                                    @if($appointments && $appointments->down_payment)
                                        - ₱{{ number_format($appointments->down_payment, 2) }}
                                    @else
                                        <span class="text-gray-400 text-sm">N/A</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t-2 border-blue-200">
                                <span class="text-base font-bold text-gray-800">Balance Due</span>
                                <span class="text-2xl font-bold text-blue-600">₱<span id="balance-due"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Export Button -->
            <div class="mt-8 pt-6 border-t-2 border-gray-200 flex justify-center">
                @if($appointments)
                    <x-download-invoice-button :appointment="$appointments">
                        Export as PDF
                    </x-download-invoice-button>
                @else
                    <button disabled class="inline-flex items-center gap-2 px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg shadow-md cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        No Invoice Available
                    </button>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>For inquiries, contact us at <a href="mailto:robles_moncayo@yahoo.com" class="text-blue-600 hover:underline font-medium">robles_moncayo@yahoo.com</a></p>
            <p class="mt-1">RMDC - Robles Moncayo Dental Clinic &copy; {{ date('Y') }}</p>
        </div>
    </div>


                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        let procedureName = "{{ $appointments->procedure ?? '' }}";
                        let downPayment = {{ $appointments->down_payment ?? 0 }};

                        if (procedureName) {
                            fetch(`/get-procedure-details?procedure=${encodeURIComponent(procedureName)}`)
                                .then(response => response.json())
                                .then(data => {
                                    let duration = data.duration;
                                    let price = parseFloat(data.price);
                                    
                                    document.getElementById("estimated-time").textContent = duration + " minutes";
                                    document.getElementById("procedure-price").textContent = price.toFixed(2);
                                    document.getElementById("total-price").textContent = price.toFixed(2);
                                    
                                    // Calculate balance
                                    let balance = price - downPayment;
                                    document.getElementById("balance-due").textContent = balance.toFixed(2);
                                })
                                .catch(error => console.error("Error fetching procedure details:", error));
                        }
                    });
                    
                    // PDF Export Function
                    function exportToPDF() {
                        window.print();
                    }
                </script>
                
                <style>
                    @media print {
                        /* Hide everything except the invoice */
                        body * {
                            visibility: hidden;
                        }
                        #billing-invoice, #billing-invoice * {
                            visibility: visible;
                        }
                        #billing-invoice {
                            position: absolute;
                            left: 0;
                            top: 0;
                            width: 100%;
                        }
                        /* Hide the export button when printing */
                        #billing-invoice button {
                            display: none;
                        }
                        /* Ensure proper page breaks */
                        .border {
                            page-break-inside: avoid;
                        }
                    }
                </style>
        </div>
    </div>


    <section id="our-services">
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-blue-800 shadow-sm rounded-lg relative">
            <div class="text-center font-semibold text-3xl text-gray-800 dark:text-white mt-6 mb-8">
                <span class="text-blue-600">Our</span> Services
            </div>

            <!-- Grid Layout for Services (3 cards per row) -->
            <div id="services-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @include('partials.services-cards', ['procedures' => $procedures])
            </div>

            <!-- Pagination -->
            <div id="services-pagination" class="flex justify-center py-6">
                {{ $procedures->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>

<!-- AJAX Pagination Script -->
<script>
    $(document).ready(function() {
        // Handle pagination clicks
        $(document).on('click', '#services-pagination .pagination a', function(e) {
            e.preventDefault();
            
            let page = $(this).attr('href').split('page=')[1];
            fetchServices(page);
        });
        
        function fetchServices(page) {
            $.ajax({
                url: "/dashboard?page=" + page,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#services-container').html(data.html);
                    $('#services-pagination').html(data.pagination);
                },
                error: function(xhr) {
                    console.error('Error loading services:', xhr);
                }
            });
        }
    });
</script>

<style>
    .service-card {
        width: 100%;
        display: flex;
        flex-direction: column;
    }
    
    /* Pagination styling */
    #services-pagination .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }
    
    #services-pagination .page-item {
        list-style: none;
    }
    
    #services-pagination .page-link {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    #services-pagination .page-link:hover {
        background-color: #3b82f6;
        color: white;
    }
    
    #services-pagination .page-item.active .page-link {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    #services-pagination .page-item.disabled .page-link {
        color: #9ca3af;
        cursor: not-allowed;
    }
</style>


    <script>
        document.getElementById('view-teeth-layout-btn').addEventListener('click', function () {
            const modal = document.getElementById('teeth-layout-modal');
            const content = document.getElementById('teeth-layout-content');

            // Reset modal content
            content.innerHTML = `
                <svg id="teeth-chart" viewBox="0 0 400 700" xmlns="http://www.w3.org/2000/svg">
                    <line x1="200" y1="60" x2="200" y2="640" stroke="#a084ca" stroke-width="3" stroke-dasharray="8,6"/>
                    <line x1="40" y1="350" x2="360" y2="350" stroke="#a084ca" stroke-width="3" stroke-dasharray="8,6"/>
                </svg>
            `;

            fetch('/user/teeth-layout')
                .then(response => response.json())
                .then(data => {
                    const chart = document.getElementById('teeth-chart');

                    if (!data.teeth || data.teeth.length === 0) {
                        content.innerHTML = '<p class="text-center text-gray-600 dark:text-gray-300">No teeth layout</p>';
                    } else {
                        const upperArc = [];
                        const lowerArc = [];
                        const rX = 120, rY = 220, cx = 200, cy = 280, cy2 = 420;

                        // Upper teeth: 1-16 (right to left)
                        for (let i = 0; i < 16; i++) {
                            const angle = Math.PI * (1 - i / 15);
                            upperArc.push({
                                x: cx + rX * Math.cos(angle),
                                y: cy - rY * Math.sin(angle),
                                number: i + 1,
                                idx: i + 1
                            });
                        }

                        // Lower teeth: 17-32 (left to right)
                        for (let i = 0; i < 16; i++) {
                            const angle = Math.PI * (i / 15);
                            lowerArc.push({
                                x: cx + rX * Math.cos(angle),
                                y: cy2 + rY * Math.sin(angle),
                                number: i + 17,
                                idx: i + 17
                            });
                        }

                        // Draw upper teeth
                        upperArc.forEach((pos) => {
                            drawTooth(chart, pos.x, pos.y, pos.idx, pos.number, data.teeth);
                        });

                        // Draw lower teeth
                        lowerArc.forEach((pos) => {
                            drawTooth(chart, pos.x, pos.y, pos.idx, pos.number, data.teeth);
                        });
                    }

                    modal.style.display = 'flex';
                });
        });

        function drawTooth(chart, x, y, idx, label, teethData) {
            const tooth = teethData.find(t => t.number == idx && !t.removed);
            if (!tooth) return;

            const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
            group.setAttribute('class', 'tooth-group');
            group.setAttribute('transform', `translate(${x},${y})`);

            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('d', getToothPath('incisor'));
            path.setAttribute('fill', '#fff');
            path.setAttribute('stroke', '#333');
            path.setAttribute('stroke-width', '2');
            group.appendChild(path);

            const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            text.setAttribute('x', 0);
            text.setAttribute('y', 6);
            text.setAttribute('text-anchor', 'middle');
            text.setAttribute('font-size', '16');
            text.setAttribute('fill', '#333');
            text.textContent = label;
            group.appendChild(text);

            chart.appendChild(group);
        }

        function getToothPath(type) {
            switch (type) {
                case 'molar':
                    return 'M -15,-10 Q 0,-25 15,-10 Q 20,10 0,25 Q -20,10 -15,-10 Z';
                case 'premolar':
                    return 'M -10,-10 Q 0,-20 10,-10 Q 13,10 0,18 Q -13,10 -10,-10 Z';
                case 'canine':
                    return 'M -7,-10 Q 0,-22 7,-10 Q 8,10 0,20 Q -8,10 -7,-10 Z';
                case 'incisor':
                default:
                    return 'M -8,-10 Q 0,-15 8,-10 Q 8,10 0,15 Q -8,10 -8,-10 Z';
            }
        }

        function closeTeethLayoutModal() {
            const modal = document.getElementById('teeth-layout-modal');
            modal.style.display = 'none';
        }
    </script>

    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1050;
             overflow: auto;
        }

        .modal-dialog {
            width: 700px;
            height: 700px;
            max-width: 70vw;
            max-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            width: 500px;
            height: 500px;
            max-width: 95vw;
            max-height: 120vh;
            display: flex;
            flex-direction: column;
             overflow: auto;
        }

        .modal-header, .modal-footer {
            padding: 16px;
            background: #f1f1f1;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 16px;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
             overflow: auto;
        }

        .svg-dental-chart {
            width: 80%;
            height: 100%;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            position: relative;
        }
    </style>
    
    <!-- Include Feedback Modal -->
    <x-feedback-modal />
</x-app-layout>
