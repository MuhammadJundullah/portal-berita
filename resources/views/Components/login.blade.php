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

