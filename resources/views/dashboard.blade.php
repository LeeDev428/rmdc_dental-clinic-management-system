<x-app-layout>
    @section('title', 'Dashboard')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

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

    <!-- Automatically Display Notifications and Appointment Details in One Card -->
    <div class="py-1 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="invoice-container border border-gray-300 rounded-lg p-6 dark:bg-gray bg-gray shadow-md mt-6 relative">
<br>
<br>
        <!-- Fixed Centered Image -->
        <img src="{{ asset('img/dcms_iconmini(1).png') }}" alt="Logo"
            class="absolute top-25 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-20 h-20">
    <br>
        <!-- Invoice Header -->
        <div class="text-center font-semibold text-3xl text-gray-800 mt-10">
            <span class="text-blue-600">Your</span> Appointment
        </div>

        <div class="border border-gray-300 rounded-lg p-6 bg-gray  dark:bg-white shadow-md mt-6">

            <!-- Invoice Title -->
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-700">Billing Invoice</h1>
                    <br>
                    <p class="text-sm text-gray-500">Issued on: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
                </div>
                <div class="text-right">
                    <h2 class="text-lg font-semibold text-gray-700">
                        INVOICE #{{ $appointments ? $appointments->id : 'N/A' }}
                    </h2>

                    <br>
                    <p class="text-sm text-gray-500">
                        Status:
                        <span class="px-2 py-1 text-[14px] font-semibold rounded-md
                        @if($appointments && $appointments->status == 'pending')
                            bg-yellow-100 text-yellow-600
                        @elseif($appointments && $appointments->status == 'accepted')
                            bg-green-100 text-green-600
                        @elseif($appointments && $appointments->status == 'declined')
                            bg-red-100 text-red-600
                        @else
                            bg-gray-100 text-gray-600
                        @endif">
                        {{ $appointments ? ucfirst($appointments->status) : 'N/A' }}
                    </span>

                    </p>
                </div>
            </div>

            <!-- Billing Details -->
            <div class="mt-4 flex justify-between">
                <div>
                    <p class="font-semibold text-gray-600">Billed To:</p>
                    <p class="text-sm text-gray-500">{{ auth()->user()->name ?? 'Unknown' }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-600">Doctor:</p>
                    <span id="doctor-name" class="text-sm text-gray-500"></span>
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

           <!-- Responsive Table Container -->
        <div class="overflow-x-auto mt-6">
            <table class="w-full border-collapse border border-gray-200 min-w-[600px]">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-600">Procedure</th>
                        <th class="border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-600">Date</th>
                        <th class="border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-600">Time</th>
                        <th class="border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-600">Duration</th>
                        <th class="border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-600">Price</th>
                        <th class="border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border border-gray-200">
                        <td class="px-4 py-2 text-center text-sm text-gray-600">{{ $appointments->procedure ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-center text-sm text-gray-600">{{ $appointments && $appointments->start ? \Carbon\Carbon::parse($appointments->start)->format('F j, Y') : 'N/A' }}</td>
                        <td class="px-4 py-2 text-center text-sm text-gray-600">
                            {{ $appointments && $appointments->start ? \Carbon\Carbon::parse($appointments->start)->format('h:i A') : 'N/A' }} -
                            {{ $appointments && $appointments->end ? \Carbon\Carbon::parse($appointments->end)->format('h:i A') : 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-center text-sm text-gray-600"> <span id="estimated-time"></span></td>
                        <td class="px-4 py-2 text-center text-sm text-gray-600">₱<span id="procedure-price"></span></td>
                        <td class="px-4 py-2 text-center text-sm text-gray-600"><span id=""></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Total and Footer -->
        <div class="flex flex-col md:flex-row justify-between mt-6 border-t pt-4">
            <div class="text-sm text-gray-500">
                <p>• {{ $appointments && $appointments->created_at ? $appointments->created_at->diffForHumans() : 'N/A' }}</p>
            </div>
            <div class="text-right mt-2 md:mt-0">
                <p class="text-lg font-semibold text-gray-700">Total: ₱<span id="procedure-price2"></span></p>
            </div>
        </div>
    </div>
</div>


                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        let procedureName = "{{ $appointments->procedure ?? '' }}";

                        if (procedureName) {
                            fetch(`/get-procedure-details?procedure=${encodeURIComponent(procedureName)}`)
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById("estimated-time").textContent = data.duration;
                                    document.getElementById("procedure-price").textContent = data.price;
                                    document.getElementById("procedure-price2").textContent = data.price;
                                })
                                .catch(error => console.error("Error fetching procedure details:", error));
                        }
                    });
                </script>
        </div>
    </div>


    <section>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-blue-800 shadow-sm rounded-lg relative">
            <div class="text-center font-semibold text-3xl text-gray-800 dark:text-white mt-6">
                <span class="text-blue-600">Our</span> Services
            </div>

            <!-- Horizontal Scrollable Services -->
            <div id="services-scroll" class="flex overflow-x-auto space-x-6 p-6 scroll-smooth">
                @foreach($procedures as $procedure)
                <div class="w-[360px] flex-shrink-0 rounded-xl shadow-lg bg-white dark:bg-gray-800 hover:scale-105 transform transition duration-300 ease-in-out">
                    <img src="{{ asset('storage/' . $procedure->image_path) }}"
                         alt="{{ $procedure->procedure_name }}"
                         class="w-full h-44 object-cover rounded-t-xl">
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $procedure->procedure_name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 line-clamp-3">
                            {{ $procedure->description ?? 'No description available.' }}
                        </p>
                        <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                            <p><strong>Estimated Time:</strong> {{ $procedure->duration }} Minutes</p>
                            <p><strong>Price:</strong> ₱{{ number_format($procedure->price, 2) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Scroll Script -->
<script>
    function scrollServices(offset) {
        const container = document.getElementById('services-scroll');
        container.scrollLeft += offset;
    }
</script>


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
</x-app-layout>
