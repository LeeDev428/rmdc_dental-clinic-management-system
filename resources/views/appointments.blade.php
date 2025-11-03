<x-app-layout>
    @section('title', 'Dashboard')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="background-color: rgb(25, 221, 221); font-size: 18px; font-family: 'Roboto', sans-serif;">
                    <span class="text-black">{{ __("Let’s book yours, " . auth()->user()->name . "!") }}</span>
                <style>
                    body.dark .bg-white {
                        background-color: #1f2937 !important; /* Dark mode background */
                    }
                    body.dark .text-gray-900 {
                        color: #f9fafb !important; /* Dark mode text color */
                    }
                    body.dark .text-gray-700 {
                        color: #d1d5db !important; /* Dark mode text color */
                    }
                    body.dark .border-gray-300 {
                        border-color: #4b5563 !important; /* Dark mode border color */
                    }
                    body.dark .bg-blue-600 {
                        background-color: #2563eb !important; /* Dark mode button color */
                    }
                    body.dark .hover\:bg-blue-700:hover {
                        background-color: #1d4ed8 !important; /* Dark mode button hover color */
                    }
                    body.dark .bg-red-600 {
                        background-color: #dc2626 !important; /* Dark mode button color */
                    }
                    body.dark .hover\:bg-red-700:hover {
                        background-color: #b91c1c !important; /* Dark mode button hover color */
                    }
                    body.dark .border-gray-300 {
                        border-color: #4b5563 !important; /* Dark mode border color */
                    }
                    .dark .border {
                        border: 1px solid #4b5563 !important; /* Thin border for visibility */
                    }
                    body.dark .fc-daygrid-day-number {
    color: #ffffff !important; /* White text color for day numbers in dark mode */
}
body.dark .fc-toolbar-title, /* Month and Year */
body.dark .fc-daygrid-day-number, /* Day numbers */
body.dark .fc-col-header-cell-cushion { /* Day names */
    color: #ffffff !important; /* White text color */
}
   /* Centered form */
   .center-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
.appointment-card {
            background: white;
            padding: 13px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 250px;
            width: 80%;
        }
        label {
            font-weight: 500;
            font-size: 12px;
            color: #555;
        }
        input[type="date"] {
            width: 100%;
            padding: 5px;
            font-size: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }
        .apptbutton {
            width: 100%;
            margin-top: 10px;
            padding: 7px;
            font-size: 12px;
            font-weight: 450;
            background-color: rgb(25, 221, 221);
            border: none;
            color: rgb(37, 37, 37);
            border-radius: 5px;
            transition: 0.3s;
        }

        .apptbutton:hover {
            background-color: rgb(15, 173, 173);
        }

        /* Modernized Modal */
        .modal-content {
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-title {
            font-size: 14px;
            font-weight: 500;
        }

        .modal-body ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        .modal-body ul li {
            font-size: 13px;
            padding: 8px;
            background: #f1f1f1;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .btn-close {
            font-size: 14px;
        }

  /* Custom Modal Styles */
.custom-modal {
    display: flex;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    justify-content: center;
    align-items: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.4s ease-in-out, visibility 0.4s ease-in-out;
}

/* Smooth pop-up effect */
.custom-modal.show {
    opacity: 1;
    visibility: visible;
}

/* Modal Content */
.modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 300px;
    max-width: 80%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transform: translateY(-30px);
    transition: transform 0.2s ease-in-out;
}

/* Animate modal content */
.custom-modal.show .modal-content {
    transform: translateY(0);
}

/* Modal Header */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Close Button */
.close-modal {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #333;
    transition: color 0.2s;
}

.close-modal:hover {
    color: red;
}

/* Time slots in 4 columns */
.time-row {
    display: flex;
    justify-content: space-around;
    margin-bottom: 10px;
}

.time-slot {
    font-size: 12px;
    padding: 8px;
    background: #f0f0f0;
    border-radius: 5px;
    width: 60px;
    text-align: center;

    transition: background 0.2s;
}

.time-slot:hover {
    background: #ddd;
}
                </style>


<!-- Need help? Button with icon -->
<div class="flex items-center mt-4 cursor-pointer hover:text-gray-600" onclick="openHelpModal()">
    <!-- SVG for Question Mark inside a Circle -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10" stroke="black" stroke-width="2" fill="none"></circle>
        <text x="12" y="16" font-size="14" text-anchor="middle" font-family="Arial" fill="black" font-weight="100">?</text>
    </svg>

    <!-- "Need Help?" text with adjusted font size -->
    <span class="font-medium text-base text-black hover:text-gray-600">Need help</span>
</div>
<br>

<div class="center-form">
    <div class="appointment-card">
        <h2 style="font-size: 13px;">Check Available Time Slot/s</h2>
        <form id="appointmentForm">
            <label for="date">Select Date:</label>
            <input type="date" id="dateInput" name="date" value="{{ $selectedDate }}" required>
            <button type="submit" class="apptbutton">Check Availability</button>
        </form>
    </div>
</div>

<!-- Custom Modal (No Bootstrap) -->
<div id="availableTimesModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Available Time Slots</h5>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <ul id="availableTimesList">
                @if (!empty($availableTimes))
                    @foreach ($availableTimes as $time)
                        <li>{{ $time }}</li>
                    @endforeach
                @else
                    <li>No available slots for this day.</li>
                @endif
            </ul>
        </div>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("appointmentForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent default form submission

    let selectedDate = document.getElementById("dateInput").value;
    let formAction = "{{ route('appointments') }}"; // Laravel route for fetching appointments

    fetch(formAction + "?date=" + selectedDate, { method: "GET", headers: { "X-Requested-With": "XMLHttpRequest" } })
        .then(response => response.json()) // Expect JSON response
        .then(data => {
            let timesList = document.getElementById("availableTimesList");
            timesList.innerHTML = ""; // Clear previous times

            if (data.availableTimes.length > 0) {
                let rowDiv;
                data.availableTimes.forEach((time, index) => {
                    if (index % 4 === 0) { // Create a new row every 4 items
                        rowDiv = document.createElement("div");
                        rowDiv.classList.add("time-row");
                        timesList.appendChild(rowDiv);
                    }

                    let timeItem = document.createElement("div");
                    timeItem.classList.add("time-slot");
                    timeItem.innerText = time;
                    rowDiv.appendChild(timeItem);
                });
            } else {
                timesList.innerHTML = "<p>No available slots for this day.</p>";
            }

            // Show modal smoothly
            let modal = document.getElementById("availableTimesModal");
            modal.style.display = "flex";
            setTimeout(() => { modal.classList.add("show"); }, 10);
        })
        .catch(error => console.error("Error fetching available times:", error));
});

// Close modal when clicking the close button
document.querySelector(".close-modal").addEventListener("click", function() {
    let modal = document.getElementById("availableTimesModal");
    modal.classList.remove("show");
    setTimeout(() => { modal.style.display = "none"; }, 300);
});

// Close modal when clicking outside content
window.onclick = function(event) {
    let modal = document.getElementById("availableTimesModal");
    if (event.target === modal) {
        modal.classList.remove("show");
        setTimeout(() => { modal.style.display = "none"; }, 300);
    }
};

</script>

                    <!-- Modal for How to Book an Appointment -->
                    <div id="helpModal" class="hidden fixed z-10 inset-0 overflow-y-auto bg-black bg-opacity-50">
                        <div class="flex items-center justify-center min-h-screen">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md mx-4 relative">
                                <!-- Close button (X) in the upper right corner -->
                                <button onclick="closeHelpModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    &times;
                                </button>

                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">• How to Book an Appointment</h3>
                                <div class="mt-4 space-y-4 text-sm text-gray-700 dark:text-gray-300">
                                    <div>
                                        <strong class="font-bold">Select a Date:</strong> In the calendar, click on the date you'd like to book your appointment. This will open the appointment booking form.
                                    </div>
                                    <div>
                                        <strong class="font-bold">Fill Out Appointment Details:</strong>
                                        <ul class="list-disc ml-6 space-y-2">
                                            <li><strong>Event Title:</strong> Enter a title for your appointment (e.g., "Teeth Whitening" or simply your name e.g., "User's Appointment").</li>
                                            <li><strong>Dental Procedure:</strong> Choose the procedure from the dropdown menu (e.g., "Root Canal").</li>
                                            <li><strong>Estimated Duration:</strong> This will automatically update based on your selected procedure (e.g., "60 minutes").</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong class="font-bold">Agree to Terms & Conditions:</strong>
                                        <ul class="list-disc ml-6 space-y-2">
                                            <li>Read through the terms listed.</li>
                                            <li>Check the box to confirm that you agree to the terms.</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong class="font-bold">Submit the Appointment:</strong> After filling out the form, click Save Changes to confirm your appointment.
                                    </div>
                                    <br>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">• Appointment</h3>
                                    <div>
                                        <strong class="font-bold">Manage Appointments:</strong>
                                        <ul class="list-disc ml-6 space-y-2">
                                            <li>Edit your booking by clicking on your event.</li>
                                            <li>You can unlimited edit/update your appointment. But once it was approved, you can't edit/update your appointment (Deleting the appointment will face some penalties).</li>
                                            <li>Cancel your appointment by clicking 'Delete Appointment' (only available for your own bookings).</li>

                                        </ul>
                                    </div>
                                    <div>
                                        <strong class="font-bold">Wait for Dentist's approval</strong>
                                        <ul class="list-disc ml-6 space-y-2">
                                            <li>Check your Notification bell (if Approved or Declined).</li>
                                            <li>View your Invoices (Dental Appointment Details) on the Dashboard.</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- OK button to close the modal -->
                                <div class="mt-6 flex justify-end">
                                    <button onclick="closeHelpModal()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" style="font-size: 14px;">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // Function to open the modal
                    function openHelpModal() {
                        const modal = document.getElementById('helpModal');
                        modal.classList.remove('hidden');
                    }

                    // Function to close the modal
                    function closeHelpModal() {
                        const modal = document.getElementById('helpModal');
                        modal.classList.add('hidden');
                    }
                </script>


                <br>
                <div id="calendar" style="max-width: 900px; margin: auto;"></div>

                <div id="booking-modal" class="hidden fixed z-10 inset-0 overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-5 w-96 border">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Book Appointment</h3>
                            <form id="booking-form" method="POST" action="{{ route('appointments.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="booking-id" name="id">
                                <input type="hidden" id="booking-start" name="start">
                                <input type="hidden" id="booking-end" name="end">

                                <!-- Event Title -->
                                <div class="mb-4">
                                    <label for="booking-title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                    <input type="text" id="booking-title" name="title" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  
           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
           placeholder="Select a procedure"
           value="{{ __( auth()->user()->name) }}">
                                </div>

<!-- Procedure Dropdown -->
<div class="mb-4">
    <label for="operation-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dental Procedure</label>
    <select id="operation-type" name="procedure" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        <option value="">Select a Procedure</option>
        @foreach ($procedurePrices as $procedurePrice)
            <option value="{{ $procedurePrice->procedure_name }}" {{ $procedurePrice->procedure_name == $selectedProcedure ? 'selected' : '' }}>
                {{ $procedurePrice->procedure_name }}
            </option>
        @endforeach
    </select>
</div>



<!-- Estimated Time Display -->
<div class="mb-4">
    <label for="estimated-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estimated Duration</label>
    <input type="text" id="estimated-time" name="estimated-time" readonly
           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
           placeholder="Select a procedure"
           value="{{ isset($procedurePrice) ? $procedurePrice->duration . ' minutes' : '' }}">
</div>

<!-- Procedure Price Display -->
<div class="mb-4">
    <label for="procedure-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Procedure Price</label>
    <input type="text" id="procedure-price" name="procedure-price" readonly
           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
           placeholder="Price"
           value="{{ isset($procedurePrice) ? '₱' . number_format($procedurePrice->price, 2) : '' }}">
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#operation-type').change(function() {
            var selectedProcedure = $(this).val(); // Get selected procedure

            if (selectedProcedure) {
                $.ajax({
                    url: '{{ route('getProcedureDetails') }}',  // Your AJAX route to fetch price & duration
                    type: 'GET',
                    data: { procedure: selectedProcedure },
                    success: function(response) {
                        if (response) {
                            // Update price field
                            $('#procedure-price').val(response.price ? '₱' + parseFloat(response.price).toFixed(2) : 'Price not available');

                            // Update estimated duration field
                            $('#estimated-time').val(response.duration ? response.duration + ' minutes' : 'Duration not available');
                        } else {
                            $('#procedure-price').val('Price not available');
                            $('#estimated-time').val('Duration not available');
                        }
                    },
                    error: function() {
                        $('#procedure-price').val('Error fetching price');
                        $('#estimated-time').val('Error fetching duration');
                    }
                });
            } else {
                $('#procedure-price').val('Price');
                $('#estimated-time').val('Select a procedure');
            }
        });
    });
</script>



                             <!-- Time Selection -->
<div class="mb-4 w-full md:w-1/2">
    <label for="appointment-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Time
    </label>
    <select id="appointment-time" name="time" required
        class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2">
        <option value="" disabled selected>Select Time</option>
        <option value="08:00">08:00 AM</option>
        <option value="08:15">08:15 AM</option>
        <option value="08:30">08:30 AM</option>
        <option value="08:45">08:45 AM</option>

        <option value="09:00">09:00 AM</option>
        <option value="09:15">09:15 AM</option>
        <option value="09:30">09:30 AM</option>
        <option value="09:45">09:45 AM</option>

        <option value="10:00">10:00 AM</option>
        <option value="10:15">10:15 AM</option>
        <option value="10:30">10:30 AM</option>
        <option value="10:45">10:45 AM</option>

        <option value="11:00">11:00 AM</option>
        <option value="11:15">11:15 AM</option>
        <option value="11:30">11:30 AM</option>
        <option value="11:45">11:45 AM</option>

        <option value="12:00">12:00 PM</option>
        <option value="12:15">12:15 PM</option>
        <option value="12:30">12:30 PM</option>
        <option value="12:45">12:45 PM</option>

        <option value="13:00">13:00 PM</option>
        <option value="13:15">13:15 PM</option>
        <option value="13:30">13:30 PM</option>
        <option value="13:45">13:45 PM</option>

        <option value="14:00">14:00 PM</option>
        <option value="14:15">14:15 PM</option>
        <option value="14:30">14:30 PM</option>
        <option value="14:45">14:45 PM</option>

        <option value="15:00">15:00 PM</option>
        <option value="15:15">15:15 PM</option>
        <option value="15:30">15:30 PM</option>
        <option value="15:45">15:45 PM</option>

        <option value="16:00">16:00 PM</option>
        <option value="16:15">16:15 PM</option>
        <option value="16:30">16:30 PM</option>
        <option value="16:45">16:45 PM</option>

    </select>
</div>

                                <div class="mb-4">
                                    <label for="valid-id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Valid ID</label>
                                    <input type="file" id="valid-id" name="image_path" accept="image/*" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" onchange="previewImage(event)" required>

                                    <!-- Small Preview of Image -->
                                    <div id="image-preview-container" class="mt-2">
                                        <img id="image-preview" src="#" alt="Image Preview" style="display:none; width: 50px; height: auto; border: 1px solid #ccc; cursor: zoom-in;" onclick="zoomImage()">
                                    </div>

                                    <small class="text-gray-500">Accepts image files (JPEG, PNG, JPG, SVG).</small>
                                </div>


                                <!-- Modal for Image Zoom -->
                                <div id="image-zoom-modal" class="fixed inset-0 bg-gray-700 bg-opacity-50 flex justify-center items-center" style="display:none;">
                                    <img id="zoomed-image" src="#" alt="Zoomed Image" style="max-width: 90%; max-height: 90%; cursor: zoom-out;">
                                </div>

                                <script>
                                    function previewImage(event) {
                                        var file = event.target.files[0];
                                        var reader = new FileReader();
                                        reader.onload = function(e) {
                                            var preview = document.getElementById('image-preview');
                                            preview.style.display = 'inline-block';
                                            preview.src = e.target.result;
                                        };
                                        reader.readAsDataURL(file);
                                    }

                                    function zoomImage() {
                                        var modal = document.getElementById('image-zoom-modal');
                                        var zoomedImage = document.getElementById('zoomed-image');
                                        var previewImage = document.getElementById('image-preview');

                                        zoomedImage.src = previewImage.src;
                                        modal.style.display = 'flex';

                                        modal.onclick = function() {
                                            modal.style.display = 'none';
                                        };
                                    }
                                </script>
                               <!-- Terms and Agreement Section Below the Time Input -->
<div class="mb-4">
    <label for="terms-checkbox" class="text-sm text-gray-600 dark:text-gray-300">
       &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;  <span class="font-bold">🔺 Terms and Conditions 🔺</span>

        <ul class="list-disc pl-5 text-gray-600 dark:text-gray-300">
            <li>Rescheduling and editing the appointment is not allowed once the appointment was created.</li>
            <li>If you delete your appointment, it will be counted as the first violation. You are allowed a maximum of 3 violations. On the 3rd violation, you will be restricted from the system.</li>
            <li>Only one appointment is allowed per day.</li>
        </ul>
    </label>
    <!-- Move checkbox below -->
    <div class="flex items-center mt-2">
        <input type="checkbox" id="terms-checkbox" required class="mr-2">
        <label for="terms-checkbox" class="text-sm text-gray-600 dark:text-gray-300">
            I agree to the Terms and Conditions
        </label>
    </div>
</div>


                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const termsCheckbox = document.getElementById('terms-checkbox');

                                        // Retrieve the stored value from localStorage (if any)
                                        const termsAccepted = localStorage.getItem('termsAccepted');

                                        // If the value exists and is 'true', set the checkbox as checked
                                        if (termsAccepted === 'true') {
                                            termsCheckbox.checked = true;
                                        } else {
                                            // If no saved state, leave it unchecked
                                            termsCheckbox.checked = false;
                                        }

                                        // When the checkbox state changes, update localStorage
                                        termsCheckbox.addEventListener('change', function () {
                                            localStorage.setItem('termsAccepted', termsCheckbox.checked);
                                        });
                                    });
                                </script>


                                    <button type="submit" class="mt-2 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Save Changes
                                    </button>


                                <button id="delete-appointment" type="button" class="mt-2 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 hidden">
                                    Delete Appointment
                                </button>
                            </form>


                            <button id="close-modal" class="mt-2 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Close</button>
                        </div>
                    </div>
                </div>


                <!-- Success or Failure Popup -->
                <div id="popup-message" class="hidden fixed z-10 inset-0 flex justify-center items-center bg-gray-900 bg-opacity-50">
                    <div id="popup-content" class="bg-white dark:bg-gray-800 rounded-lg p-5 max-w-xs mx-auto text-center">
                        <div id="popup-icon" class="text-3xl mb-4"></div>
                        <p id="popup-text" class="text-lg font-medium text-gray-900 dark:text-gray-300"></p>
                    </div>
                </div>

                <!-- Rating Modal -->
                <div id="rating-modal" class="hidden fixed z-20 inset-0 flex justify-center items-center bg-gray-900 bg-opacity-50">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-5 w-96 shadow-lg relative">
                        <!-- Close Button -->
                        <button id="close-rating-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                            &times;
                        </button>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Rate Your Experience</h3>
                        <div class="flex justify-center mb-4">
                            <!-- Star Rating -->
                            <div id="star-rating" class="flex space-x-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg data-value="{{ $i }}" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400 cursor-pointer hover:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <textarea id="rating-message" class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="3" placeholder="Leave a message (optional)"></textarea>
                        <button id="submit-rating" class="mt-4 w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Submit</button>
                    </div>
                </div>

                <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ratingModal = document.getElementById('rating-modal');
        const closeRatingModal = document.getElementById('close-rating-modal');
        const stars = document.querySelectorAll('#star-rating svg');
        let selectedRating = 0;

        // Hover effect for stars
        stars.forEach(star => {
            star.addEventListener('mouseover', function () {
                const value = this.getAttribute('data-value');
                stars.forEach(s => s.classList.toggle('text-yellow-400', s.getAttribute('data-value') <= value));
            });

            star.addEventListener('mouseout', function () {
                stars.forEach(s => s.classList.toggle('text-yellow-400', s.getAttribute('data-value') <= selectedRating));
            });

            star.addEventListener('click', function () {
                selectedRating = this.getAttribute('data-value');
                stars.forEach(s => s.classList.toggle('text-yellow-400', s.getAttribute('data-value') <= selectedRating));
            });
        });

        // Submit rating
        document.getElementById('submit-rating').addEventListener('click', function () {
            const message = document.getElementById('rating-message').value;

            if (selectedRating === 0) {
                alert('Please select a rating before submitting.');
                return;
            }

            fetch("{{ route('ratings.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    rating: selectedRating,
                    message: message,
                })
            })
            .then(response => {
                if (!response.ok) {
                    console.error('Response status:', response.status);
                    return response.json().then(err => {
                        throw new Error(err.message || 'Failed to submit rating.');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Rating submitted:', data);
                showPopup('success', 'Thank you for your feedback!');
                ratingModal.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error submitting rating:', error);
                showPopup('error', 'An error occurred while submitting your rating. Please try again.');
            });
        });

        // Show rating modal 7 seconds after "Save Changes"
        document.getElementById('booking-form').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            // Simulate form submission success
            setTimeout(() => {
                ratingModal.classList.remove('hidden');
            }, 6000); // Show modal after 7 seconds

            // Proceed with the actual form submission logic
            const form = event.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log('Form submitted successfully:', data);
                // Optionally handle success response
            })
            .catch(error => console.error('Error submitting form:', error));
        });

        // Close rating modal
        closeRatingModal.addEventListener('click', function () {
            ratingModal.classList.add('hidden');
        });

        // Function to show modern success/failed popup
        function showPopup(type, message) {
            const popup = document.createElement('div');
            popup.className = `fixed top-5 right-5 z-50 px-4 py-3 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            popup.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">${type === 'success' ? '✔️' : '❌'}</span>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(popup);

            setTimeout(() => {
                popup.classList.add('opacity-0');
                setTimeout(() => popup.remove(), 500); // Remove after fade-out
            }, 3000); // Show for 3 seconds
        }
    });
</script>
            </div>
        </div>
    </div>
    @section('css')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
        <style>

            .fc-event {
                transition: background-color 0.3s, transform 0.3s;
                position: relative;
                background-color: rgb(25, 221, 221);
                font-size: 13px;
            }
            .fc-event:hover {
                background-color: rgb(26, 184, 184);
                transform: scale(1.05);
            }
            .fc-event-title {
                display: none;
            }
            .fc-event-time::before {
                content: "\2022"; /* Bullet character */
                color: #1E90FF; /* Light blue color */
                font-size: 10px; /* Larger bullet */
                margin-right: 8px;
            }
            .fc-event-time {
                display: inline-block;
                margin-left: 12px;
            }
            .fc-event {
                cursor: pointer;
            }
            .fc-event.other-appointment {
                pointer-events: none;
                background-color: #99b2cf;
            }
            .fc-event.other-appointment:hover {
                background-color: #f0f0f0;
            }

            @media (max-width: 768px) { /* Tablets and small screens */
    .event-time {
        font-size: 14px !important;
    }
}

@media (max-width: 480px) { /* Phones like Galaxy S8+ and smaller */
    .event-time {
        font-size: 12px !important;
    }
}

@media (max-width: 360px) { /* Very small screens */
    .event-time {
        font-size: 11px !important;
    }
}

        </style>
    @endsection

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
        <script>
         document.addEventListener('DOMContentLoaded', function() {
    // Define estimated times for each procedure
    const procedureTimes = {

    };

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: @json($appointments).filter(appointment => appointment.status !== 'declined'),


        eventTimeFormat: { // 24-hour format
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },

        dateClick: function(info) {
    const selectedDate = new Date(info.dateStr);
    const now = new Date();

    const isSunday = now.getDay() === 0; // Sunday = 0
    const isMonday = now.getDay() === 1; // Monday = 1

    let allowedStart, allowedEnd;

    if (isSunday) {
        // If today is Sunday, allow only Monday
        allowedStart = new Date(now);
        allowedStart.setDate(now.getDate() + 1); // Monday
        allowedStart.setHours(0, 0, 0, 0);
        allowedEnd = new Date(allowedStart); // Only Monday
    } else {
        // If today is Monday-Saturday, allow booking from tomorrow to Sunday
        allowedStart = new Date(now);
        allowedStart.setDate(now.getDate() + 1); // Tomorrow
        allowedStart.setHours(0, 0, 0, 0);

        allowedEnd = new Date(now);
        allowedEnd.setDate(now.getDate() - now.getDay() + 7); // Sunday
        allowedEnd.setHours(23, 59, 59, 999);
    }

    // ✅ New Rule: Ensure booking is at least 4 hours ahead
    const minAllowedTime = new Date(now);
    minAllowedTime.setHours(now.getHours() + 4); // 4 hours from now

    if (selectedDate < allowedStart || selectedDate > allowedEnd) {
        showErrorMessage("Invalid booking date. Please follow the allowed schedule.");
        return;
    }

    if (selectedDate.getTime() < minAllowedTime.getTime()) {
        showErrorMessage("Appointments must be scheduled at least 4 hours in advance.");
        return;
    }

    $('#booking-start').val(info.dateStr);
    $('#booking-end').val(info.dateStr);
    $('#booking-modal').removeClass('hidden');
    $('#booking-id').val('');
    $('#delete-appointment').addClass('hidden');
},


        eventClick: function(info) {
            const event = info.event;
            const isLoggedInUserEvent = event.extendedProps.user_id === {{ auth()->id() }};

            if (isLoggedInUserEvent) {
                $('#booking-id').val(event.id);
                $('#booking-title').val(event.title.split(' (')[0]);
                $('#operation-type').val(event.title.split(' (')[1].replace(')', ''));
                $('#booking-start').val(event.start.toISOString().split('T')[0]);

                let appointmentTime = event.start.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });

                // Validate the selected time is within 8 AM - 5 PM
                const [hour, minute] = appointmentTime.split(':').map(Number);
                if (hour < 8 || hour >= 17) {
                    showErrorMessage("Appointments can only be scheduled between 08:00 and 17:00.");
                    return;
                }

                $('#appointment-time').val(appointmentTime);
                $('#booking-modal').removeClass('hidden');
                $('#delete-appointment').removeClass('hidden');

                const procedure = event.title.split(' (')[1].replace(')', '');
                $('#estimated-time').val(procedureTimes[procedure] || 'Select a procedure');
            }
        },

        eventContent: function(info) {
    const isLoggedInUserEvent = info.event.extendedProps.user_id === {{ auth()->id() }};
    const status = info.event.extendedProps.status; // Get event status
    let timeFormatted = info.timeText;

    const timeParts = timeFormatted.match(/(\d{1,2}):(\d{2})([ap]+m)/);
    if (timeParts) {
        let hour = parseInt(timeParts[1]);
        let minute = timeParts[2];
        let period = timeParts[3].toLowerCase();

        if (period === 'pm' && hour < 12) hour += 12;
        if (period === 'am' && hour === 12) hour = 0;

        timeFormatted = (hour < 10 ? '0' : '') + hour + ':' + minute;
    }

    let userName = "{{ auth()->user()->name }}".substring(0, 0);

    // If status is "accepted", show a small checkmark instead of a dot
    let icon = status === "accepted"
        ? `<span style="font-size: 8px;">✔</span>` // Small checkmark
        : `<span style="color: white; font-size: 18px;">•</span>`; // Default Pending

        return {
    html: isLoggedInUserEvent
        ? `${icon}<span class="event-time">${timeFormatted}</span> ${userName}`
        : `${icon}<span class="event-time">${timeFormatted}</span>`
};


},


// Add class to disable "pending" and "accepted" events
eventClassNames: function(info) {
    const status = info.event.extendedProps.status;
    let classes = [];

    if (info.event.extendedProps.user_id !== {{ auth()->id() }}) {
        classes.push('other-appointment');
    }

    if (status === "pending" || status === "accepted") {
        classes.push('disabled-event'); // Add class to disable event
    }

    return classes;
},

// Prevent clicking on "pending" or "accepted" events
eventClick: function(info) {
    const status = info.event.extendedProps.status;

    if (status === "pending" || status === "accepted") {
        info.jsEvent.preventDefault(); // Prevent default click action
        return; // Stop further execution
    }

    // Allow other events to be clicked
    console.log("Event clicked:", info.event);
}

    });

    calendar.render();

    $('#operation-type').on('change', function() {
        const selectedProcedure = $(this).val();
        $('#estimated-time').val(procedureTimes[selectedProcedure] || 'Select a procedure');
    });

    function showErrorMessage(message) {
        // Remove any existing error messages before adding a new one
        const existingError = document.getElementById("error-message");
        if (existingError) existingError.remove();

        const errorModal = document.createElement("div");
        errorModal.id = "error-message";
        errorModal.innerHTML = `
            <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
                        background: rgba(255, 0, 0, 0.9); color: white; padding: 15px 20px;
                        border-radius: 8px; text-align: center; font-size: 16px;
                        width: 350px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
                        z-index: 9999;">
                <p style="margin: 0; font-weight: bold;">⚠️ ${message}</p>
                <br>
                <button onclick="document.getElementById('error-message').remove()"
                        style="background: white; color: red; padding: 5px 10px; border: none;
                        cursor: pointer; font-size: 14px; border-radius: 4px;">
                    Close
                </button>
            </div>`;

        document.body.appendChild(errorModal);

        // Auto-remove the error message after 5 seconds
        setTimeout(() => {
            const modal = document.getElementById("error-message");
            if (modal) modal.remove();
        }, 5000);
    }

                $('#booking-form').on('submit', function(event) {
                event.preventDefault();

                const appointmentId = $('#booking-id').val();
                const startDate = $('#booking-start').val();
                const startTime = $('#appointment-time').val();
                const procedure = $('#operation-type').val();
                const title = $('#booking-title').val();
                const startDateTime = startDate + 'T' + startTime + ':00';

                const formData = new FormData();
                formData.append('id', appointmentId);
                formData.append('title', title + ' (' + procedure + ')');
                formData.append('start', startDateTime);
                formData.append('end', startDateTime);
                formData.append('procedure', procedure);
                formData.append('time', startTime);
                formData.append('user_id', {{ auth()->id() }});
                formData.append('_token', '{{ csrf_token() }}');

                const imageFile = $('#valid-id')[0].files[0];
                if (imageFile) {
                    formData.append('image_path', imageFile);
                }

                const url = appointmentId ? "{{ route('appointments.update', '') }}/" + appointmentId : "{{ route('appointments.store') }}";
                const method = appointmentId ? "PUT" : "POST"; // Ensure method is POST for file upload

                $.ajax({
                    url: url,
                    type: method,
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {
                        calendar.refetchEvents();

                        if (!appointmentId) {
                            calendar.addEvent({
                                id: response.id,
                                title: response.title,
                                start: response.start,
                                end: response.end,
                                user_id: response.user_id,
                                procedure: response.procedure
                            });
                        } else {
                            var updatedEvent = calendar.getEventById(response.id);
                            if (updatedEvent) {
                                updatedEvent.setProp('title', response.title);
                                updatedEvent.setStart(response.start);
                                updatedEvent.setEnd(response.end);
                                updatedEvent.setExtendedProp('procedure', response.procedure);
                            }
                        }

                        $('#booking-modal').addClass('hidden');
                        showPopup('success', 'Appointment successfully updated!');
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                        showPopup('error', 'Failed to book appointment: ' + errorMessage);
                    }
                });
            });

                $('#delete-appointment').on('click', function() {
                    const appointmentId = $('#booking-id').val();
                    if (appointmentId) {
                        $.ajax({
                            url: "{{ route('appointments.destroy', '') }}/" + appointmentId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                const event = calendar.getEventById(appointmentId);
                                event.remove();
                                $('#booking-modal').addClass('hidden');
                                showPopup('success', 'Appointment successfully deleted!');
                            },
                            error: function(xhr) {
                                const errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                                showPopup('error', 'Failed to delete appointment: ' + errorMessage);
                            }
                        });
                    }
                });

                $('#close-modal').on('click', function() {
                    $('#booking-modal').addClass('hidden');
                });

                function showPopup(type, message) {
                    const icon = type === 'success' ? '✔️' : '❌';
                    $('#popup-icon').text(icon);
                    $('#popup-text').text(message);
                    $('#popup-message').removeClass('hidden');
                    setTimeout(function() {
                        $('#popup-message').addClass('hidden');
                    }, 3000);
                }
            });
        </script>

    @endsection
</x-app-layout>
