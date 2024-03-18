<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Upload File</title>
</head>


@if (session()->has('success'))
    <div class="w-1/3 relative py-3 pl-4 pr-10 leading-normal text-green-700 bg-green-100 rounded-lg mt-5 mx-auto"
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

<div class="mx-auto w-full">
    <form class="space-y-3 mx-auto" action="/upload" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="md:max-w-lg w-full p-10 bg-white rounded-xl z-10 mx-auto">
            <div class="text-center">
                <h2 class="mt-5 text-3xl font-bold text-gray-900">
                    File Upload!
                </h2>
                <p class="mt-2 text-sm text-gray-400">Select a file to upload.</p>
            </div>
            @if (session()->has('error'))
                <div class="w-full relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg mt-2 mb-2"
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
            <div class="grid grid-cols-1 space-y-2">
                <label class="text-sm font-bold text-gray-500 tracking-wide">Attach Document</label>
                <div class="relative">
                    <input id="fileInput" name="file" type="file" accept=".xls,.xlsx" class="hidden" />
                    <label for="fileInput"
                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md cursor-pointer bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 5a2 2 0 012-2h10a2 2 0 012 2v3a1 1 0 011 1v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a1 1 0 011-1V5zm4 5a2 2 0 11-4 0 2 2 0 014 0zm5-2a1 1 0 00-2 0v4a1 1 0 102 0V8z"
                                clip-rule="evenodd" />
                        </svg>
                        <span id="fileNameSpan">Choose a file...</span>
                        <button id="resetUpload" type="button"
                            class="absolute right-0 top-0 px-2 py-2.5 text-red-600  rounded-md hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>

                            </svg>
                        </button>
                    </label>
                </div>
            </div>
            <p class="text-sm text-gray-300">
                <span>File type: .xls,.xlsx</span>
            </p>
            <div>
                <button type="submit"
                    class="my-5 w-full flex justify-center bg-blue-500 text-gray-100 p-4  rounded-full tracking-wide
                                    font-semibold  focus:outline-none focus:shadow-outline hover:bg-blue-600 shadow-lg cursor-pointer transition ease-in duration-300">
                    Upload
                </button>
            </div>
            <div class="text-center">
                <a href="/uploaded-file" class="font-semibold hover:underline text-[#6b7280]">Show uploaded file</a>
            </div>
        </div>
    </form>
</div>
<script>
    const fileInput = document.getElementById('fileInput');
    const fileNameSpan = document.getElementById('fileNameSpan');
    const resetButton = document.getElementById('resetUpload');

    fileInput.addEventListener('change', function() {
        const files = this.files;
        if (files.length > 0) {
            const fileName = files[0].name;
            fileNameSpan.textContent = fileName;
            resetButton.style.display = 'inline-block';
        } else {
            fileNameSpan.textContent = 'Choose a file...';
            resetButton.style.display = 'none';
        }
    });

    resetButton.addEventListener('click', function() {
        fileInput.value = '';
        fileNameSpan.textContent = 'Choose a file...';
        resetButton.style.display = 'none';
    });

    function closeAlert(alertId) {
        var alert = document.getElementById(alertId);
        if (alert) {
            alert.style.display = 'none';
        }
    }
</script>
