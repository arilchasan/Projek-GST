<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Button Page</title>
</head>
@if (session()->has('error'))
        <div class="w-3/4 relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg mt-5 mx-auto"
            style="height: 50px;" role="alert" id="error-alert">
            <p>{{ session('error') }}</p>
            <span class="absolute inset-y-0 right-0 flex items-center mr-4" onclick="closeAlert('error-alert')">
                <svg class="w-4 h-4 fill-current" role="button" viewBox="0 0 20 20">
                    <path
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
            </span>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="w-3/4 relative py-3 pl-4 pr-10 leading-normal text-green-700 bg-green-100 rounded-lg mt-5 mx-auto"
            style="height: 50px;" role="alert" id="success-alert">
            <p>{{ session('success') }}</p>
            <span class="absolute inset-y-0 right-0 flex items-center mr-4" onclick="closeAlert('success-alert')">
                <svg class="w-4 h-4 fill-current" role="button" viewBox="0 0 20 20">
                    <path
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
            </span>
        </div>
    @endif
<div>
    <div class="bg-white p-4 rounded shadow-md">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 gap-4">
            <!-- Bagian Atas -->
                <button
                class="w-1/2 py-2 px-4 bg-[#f97316] hover:bg-[#ea580c] text-left text-white text-lg font-bold rounded shadow-md mb-4">Create Json File for E-Filling</button>
                <div class="grid grid-cols-2 gap-4">
                    <!-- 6 Button di Bagian Atas -->
                    <button
                        class="w-full py-2 px-4 bg-[#7c3aed] hover:bg-[#5b21b6] text-white font-normal rounded shadow-md">
                        <h3 class="text-2xl font-semibold">B2B</h3>
                        <p class="text-sm">Invoices To Taxable Persons</p>
                    </button>
                    <button
                        class="w-full py-2 px-4 bg-[#0891b2] hover:bg-[#0e7490] text-white font-normal rounded shadow-md">
                        <h3 class="text-2xl font-semibold">B2C:Large</h3>
                        <p class="text-sm">Invoices > 2.5 lac (Interstate)</p>
                    </button>
                    <button
                        class="w-full py-2 px-4 bg-[#047857] hover:bg-[#115e59] text-white font-normal rounded shadow-md">
                        <h3 class="text-2xl font-semibold">B2C-Small</h3>
                        <p class="text-sm">Other than B2B & B2C-L</p>
                    </button>
                    <button
                        class="w-full py-2 px-4 bg-[#be185d] hover:bg-[#9d174d] text-white font-normal rounded shadow-md">
                        <h3 class="text-2xl font-semibold">HSN Report</h3>
                        <p class="text-sm">Summary of HSN Codes</p>
                    </button>
                    <button
                        class="w-full py-2 px-4 bg-[#0369a1] hover:bg-[#075985] text-white font-normal rounded shadow-md">
                        <h3 class="text-2xl font-semibold">Dr./Cr. Notes</h3>
                        <p class="text-sm">To Registered Dealers</p>
                    </button><button
                        class="w-full py-2 px-4 bg-[#be123c] hover:bg-[#9f1239] text-white font-normal rounded shadow-md">
                        <h3 class="text-2xl font-semibold">Dr./Cr. Notes</h3>
                        <p class="text-sm">To Unregistered Dealers</p>
                    </button>
                </div>

        </div>
    </div>
    <div class="container my-auto px-4 py-8">
        <div class="grid grid-cols-1 gap-4 justify-center items-center">
            <!-- Bagian Bawah -->

                <button
                class="w-1/2 py-2 px-4 bg-[#166534] hover:bg-[#15803d] text-left text-white text-lg font-bold rounded shadow-md mb-4">Create Excel File for E-Filling</button>
                <div class="grid grid-cols-2 gap-4">
                    <!-- 6 Button di Bagian Bawah -->
                    <button
                        class="w-full py-2 px-4 bg-[#164e63] hover:bg-[#0e7490] text-white font-semibold rounded shadow-md">GST
                        Summary</button>
                    <button
                        class="w-full py-2 px-4 bg-[#064e3b] hover:bg-[#047857] text-white font-semibold rounded shadow-md">GST
                        Register (Bill wise)</button>
                    <button
                        class="w-full py-2 px-4 bg-[#854d0e] hover:bg-[#a16207] text-white font-semibold rounded shadow-md">GST
                        Register (HSN Wise) </button>
                    <button
                        class="w-full py-2 px-4 bg-[#3730a3] hover:bg-[#4338ca] text-white font-semibold rounded shadow-md">Date
                        Wise HSN Summary</button>
                    <button
                        class="w-full py-2 px-4 bg-[#a21caf] hover:bg-[#86198f] text-white font-semibold rounded shadow-md">GST
                        Register (Date Wise) </button>
                    <button
                        class="w-full py-2 px-4 bg-[#4d7c0f] hover:bg-[#3f6212] text-white font-semibold rounded shadow-md">GST
                        Register Detailed</button>
                </div>
                <div class="flex justify-center mt-4">
                    <button
                        class="w-1/2 py-2 px-4 bg-[#172554] hover:bg-[#1e3a8a] text-white font-semibold rounded shadow-md">Details
                        of Credits Notes</button>
                </div>

        </div>
    </div>
</div>
    <a href="/auth/logout" class="fixed bottom-5 right-5 w-1/1 py-2 px-4 bg-[#44403c] hover:bg-[#57534e] text-white font-semibold rounded shadow-md">Close</a>
</div>

<script>
    function closeAlert(alertId) {
        var alert = document.getElementById(alertId);
        if (alert) {
            alert.style.display = 'none';
        }
    }
</script>
