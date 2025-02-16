@extends('Components.layout')

@section('content')

<!-- Modal -->
@include('Components.login')

<div class="md:p-10 p-2">

    <div class=" grid grid-cols-1 gap-4">
        <div class="rounded-lg bg-gray-200 lg:col-span-2">
            <div class="m-10">
                <a href={{ route('home') }} class="hover:underline" >&larr; kembali</a>
                <div class="container grid grid-cols-1 gap-4 ">

                    <div class="grid h-screen place-content-center px-4">
                        <h1 class="tracking-widest text-gray-500 uppercase">403 | Forbidden</h1>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection



