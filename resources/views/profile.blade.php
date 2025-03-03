@extends('Components.layout')

@section('content')

<!-- Modal -->
@include('Components.login')

<div class="md:p-10 p-2">

    <div class=" grid grid-cols-1 gap-4">
        <div class="rounded-lg bg-gray-200 lg:col-span-2">
            <div class="m-10">
                <a href={{ route('home') }} class="hover:text-slate-900 text-slate-500" >&larr; kembali</a>
                <div class="container grid grid-cols-1 gap-4 ">
                    <h2 class="pt-10">Edit Profile</h2>
                    <form action={{ route('edit.profile') }} method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-x-16 pb-10">
                            <div class="space-y-10"> 
                                <div>
                                    <label class="sr-only" for="name">Name</label>
                                    <input
                                    class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                    placeholder="Name"
                                    name="name"
                                    value={{$user->name}}
                                    type="text"
                                    id="name"
                                    />
                                </div>

                                <div>
                                    <label class="sr-only" for="email">Email</label>
                                    <input
                                    class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                    placeholder="Email"
                                    name="email"
                                    value={{$user->email}}
                                    type="email"
                                    id="email"
                                    />
                                </div>

                                <div>
                                    <label class="sr-only" for="password">Password</label>
                                    <input
                                    class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                    placeholder="Password"
                                    name="password"
                                    type="password"
                                    id="password"
                                    />
                                </div>

                                <div class="mt-4 pt-5">
                                    <button
                                    type="submit"
                                    class="inline-block w-full rounded-lg bg-black px-5 py-3 font-medium text-white sm:w-auto"
                                    >
                                    Save
                                    </button>
                                </div>
                            </div>                  
                            <div>
                            <h2 class="pb-10">Pick a news category to explore !</h2>
                                <div class="grid grid-cols-5 gap-4 text-center">
                                   @foreach ($news_category as $item)
                                        @php
                                            $isChecked = is_array($user_preferences) && in_array($item->name, $user_preferences);
                                        @endphp
                                        <label
                                            for="category_{{$item->id}}"
                                            class="block w-full cursor-pointer rounded-lg border p-3 text-gray-600 
                                            {{ $isChecked ? 'border-black bg-black text-white' : 'border-white hover:border-black' }}"
                                            tabindex="0"
                                            onclick="toggleCheckbox('category_{{$item->id}}')"
                                        >
                                            <input 
                                                class="sr-only" 
                                                id="category_{{$item->id}}" 
                                                type="checkbox" 
                                                name="category[]"  
                                                value="{{$item->name}}" 
                                                tabindex="-1"
                                                {{ $isChecked ? 'checked' : '' }}
                                            />
                                            <span class="text-sm"> {{$item->name}}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function toggleCheckbox(id) {
        let checkbox = document.getElementById(id);
        let label = checkbox.closest("label");

        // Toggle checked state
        checkbox.checked = !checkbox.checked;

        // Toggle styling
        if (checkbox.checked) {
            label.classList.add("border-black", "bg-black", "text-white");
            label.classList.remove("border-white", "text-gray-600");
        } else {
            label.classList.remove("border-black", "bg-black", "text-white");
            label.classList.add("border-white", "text-gray-600");
        }
    }

    // Saat halaman dimuat, sesuaikan tampilan berdasarkan checked state
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            let label = checkbox.closest("label");
            if (checkbox.checked) {
                label.classList.add("border-black", "bg-black", "text-white");
                label.classList.remove("border-white", "text-gray-600");
            } else {
                label.classList.remove("border-black", "bg-black", "text-white");
                label.classList.add("border-white", "text-gray-600");
            }
        });
    });
</script>


