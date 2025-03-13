{{-- modal login --}}
<div id="loginModal" class="z-10 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden opacity-0 transition-opacity duration-300 ease-in-out">
    <section class="bg-white relative">
        <button class="absolute top-2 right-2 text-gray-600 hover:text-red-300" onclick="toggleModal()">
            ✖
        </button>
        <div class="lg:grid lg:min-h-32 lg:grid-cols-12">
            <section class="relative flex h-32 items-end bg-gray-900 lg:col-span-5 lg:h-full xl:col-span-6">
                <img
                    alt="Login Background"
                    src="https://images.unsplash.com/photo-1617195737496-bc30194e3a19?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fHx8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="absolute inset-0 h-full w-full object-cover opacity-80"
                />
                <div class="hidden lg:relative lg:block lg:p-12">
                    <h2 class="mt-6 text-2xl font-bold text-white sm:text-3xl md:text-4xl">
                        Welcome Back!
                    </h2>
                    <p class="mt-4 leading-relaxed text-white/90">
                        Please login to continue.
                    </p>
                </div>
            </section>
            <main class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6">
                <div class="max-w-xl lg:max-w-3xl">
                    <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Login</h2>
                    <form action="{{ route('login') }}" method="POST" class="mt-8 grid grid-cols-1 gap-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm fw-light text-gray-700">Username</label>
                            <input type="text" name="name" id="name" class="mt-1 w-full p-2 border rounded" required>
                        </div>
                        <div>
                            <label for="password" class="block text-sm fw-light text-gray-700">Password</label>
                            <input type="password" name="password" id="password" class="mt-1 w-full p-2 border rounded" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                            Login
                        </button>
                    </form>
                    <p class="mt-4 text-sm text-gray-600 text-center">
                        Don't have an account?
                        <button class="text-blue-500 hover:underline" onclick="switchToRegister()">
                            Register here
                        </button>
                    </p>
                    <div class="mt-4 text-center border-t border-gray-300 pt-4">
                        <a href="{{ url('/auth/google') }}" class="btn btn-danger flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.29 1.22 8.18 2.24l6.08-6.08C34.92 2.92 30.06 1 24 1 14.73 1 7.02 6.48 3.47 14.26l7.14 5.53C12.5 14.1 17.77 9.5 24 9.5z"/>
                                <path fill="#4285F4" d="M46.5 24.5c0-1.63-.15-3.22-.42-4.75H24v9.5h12.7c-.55 2.95-2.18 5.45-4.63 7.12l7.14 5.53C43.98 38.52 46.5 31.92 46.5 24.5z"/>
                                <path fill="#FBBC05" d="M10.61 28.74c-.75-2.22-1.17-4.58-1.17-7.24s.42-5.02 1.17-7.24L3.47 8.74C1.26 12.48 0 17.05 0 22s1.26 9.52 3.47 13.26l7.14-5.53z"/>
                                <path fill="#34A853" d="M24 46c6.06 0 11.18-2.02 14.91-5.48l-7.14-5.53c-2.02 1.36-4.56 2.16-7.77 2.16-6.23 0-11.5-4.6-13.39-10.79l-7.14 5.53C7.02 41.52 14.73 46 24 46z"/>
                                <path fill="none" d="M0 0h48v48H0z"/>
                            </svg>
                            Login dengan Google
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </section>
</div>

{{-- modal register --}}

<div id="registerModal" class="z-10 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden opacity-0 transition-opacity duration-300 ease-in-out">
    <section class="bg-white relative">
        <button class="absolute top-2 right-2 text-gray-600 hover:text-red-300" onclick="toggleRegisterModal()">
            ✖
        </button>
        <div class="lg:grid lg:min-h-32 lg:grid-cols-12">
            <section class="relative flex h-32 items-end bg-gray-900 lg:col-span-5 lg:h-full xl:col-span-6">
                <img
                    alt="Register Background"
                    src="https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="absolute inset-0 h-full w-full object-cover opacity-80"
                />
                <div class="hidden lg:relative lg:block lg:p-12">
                    <h2 class="mt-6 text-2xl font-bold text-white sm:text-3xl md:text-4xl">
                        Join Us Today!
                    </h2>
                    <p class="mt-4 leading-relaxed text-white/90">
                        Create an account to get started.
                    </p>
                </div>
            </section>
            <main class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6">
                <div class="max-w-xl lg:max-w-3xl">
                    <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Register</h2>
                    
                    <form action="{{ route('register') }}" method="POST" class="mt-8 grid grid-cols-1 gap-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm fw-light text-gray-700">Username</label>
                            <input type="text" name="name" id="name" class="mt-1 w-full p-2 border rounded" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm fw-light text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="mt-1 w-full p-2 border rounded" required>
                        </div>
                        <div>
                            <label for="password" class="block text-sm fw-light text-gray-700">Password</label>
                            <input type="password" name="password" id="password" class="mt-1 w-full p-2 border rounded" required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm fw-light text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 w-full p-2 border rounded" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                            Register
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </section>
</div>

{{-- alert error login /register --}}
@if ($errors->any())
    <div class="pt-10 px-10">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Oops! Ada kesalahan:</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

