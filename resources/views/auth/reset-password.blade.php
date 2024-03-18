<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>


<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8 items-center">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        {{-- <img class="mx-auto h-50 w-1/4" src="/assets/icons.jpeg"> --}}
        <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Reset Password
        </h2>
    </div>

    @if (session()->has('error'))
        <div class="w-1/4 relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg mt-5"
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
        <div class="w-1/4 relative py-3 pl-4 pr-10 leading-normal text-green-700 bg-green-100 rounded-lg mt-5"
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

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-3" action="{{ route('password.update', ['token' => $token]) }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                <div class="mt-2">
                    <input id="email" name="email" type="email" value="{{ $email ?? old('email') }}" required
                        class="pl-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password
                        Baru</label>
                </div>
                <div class="mt-2 relative">
                    <input id="password" name="password" type="password" required
                        class="block p-2 w-full rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm">
                        <span id="password-toggle" class="cursor-pointer" onclick="togglePasswordVisibility()">
                            <i id="password-icon" class="far fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <label for="password_confirmation"
                        class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                </div>
                <div class="mt-2 relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="block p-2 w-full rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm">
                        <span id="password1-toggle" class="cursor-pointer" onclick="togglePasswordVisibility1()">
                            <i id="password1-icon" class="far fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update
                    Password</button>
            </div>
        </form>
        <p>Already have account? <a href="/auth/login" class="font-semibold hover:underline">Sign In</a></p>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById("password");
        const passwordIcon = document.getElementById("password-icon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            passwordIcon.className = "far fa-eye-slash";
        } else {
            passwordField.type = "password";
            passwordIcon.className = "far fa-eye";
        }
    }

    function togglePasswordVisibility1() {
        const passwordField = document.getElementById("password_confirmation");
        const passwordIcon = document.getElementById("password1-icon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            passwordIcon.className = "far fa-eye-slash";
        } else {
            passwordField.type = "password";
            passwordIcon.className = "far fa-eye";
        }
    }


    function closeAlert(alertId) {
        var alert = document.getElementById(alertId);
        if (alert) {
            alert.style.display = 'none';
        }
    }
</script>
