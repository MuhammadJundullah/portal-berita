{{-- {{dd($berita_trending)}} --}}

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

    <style>
        .eb-garamond-custom {
            font-family: "EB Garamond", serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>

@extends('Components.layout')

@section('content')

{{-- modal password salah --}}
@if(session('error'))
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

<!-- Modal login -->
@include('Components.login')

<div class="md:p-10 p-2">

    @include('Components.topbar')

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">

        {{-- berita menarik untuk user lama --}}

        @if (Auth::check() && Auth::user()->created_at->diffInMonths(now()) >= 1)    
            <div class="rounded-lg bg-gray-200 lg:col-span-2">
                <div class="m-10">
                    <p class="my-5 text-xl fw-bold eb-garamond-custom">Berita Menarik Untuk Anda</p>
                    <div class="h-screen container grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-4 overflow-y-auto">
                        @foreach ($berita_rekomendasi as $item)            
                        <article
                            class="rounded-lg border border-gray-100 bg-white p-4 shadow-xs transition hover:shadow-lg sm:p-6">
                            <a href={{ $item['url'] }} target="_blank">
                                <img src={{$item['urlToImage'] ?? asset('img/noimage.webp')}} alt={{$item['urlToImage']}} class="rounded-lg">
                                <h3 class="mt-2 text-lg font-medium text-gray-900">
                                    {{ $item['title'] }}
                                </h3>
                            </a>
                            <ul class="flex gap-3 text-slate-400">
                                <li>{{ $item['source']['name'] }}</li>
                                {{-- <li>| {{ $item['author'] }}</li> --}}
                                <li class="fw-lighter">| {{ \Carbon\Carbon::parse($item['publishedAt'])->diffForHumans() }}</li>
                            </ul>

                            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
                                {{ $item['description'] }}
                            </p>

                            <a href={{ $item['url'] }} target="_blank" class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                Find out more
                                <span aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">
                                    &rarr;
                                </span>
                            </a>

                            <!-- Tombol Like dan Share -->
                             <div class="mt-4 flex items-center gap-4">

                            <!-- Tombol Like -->
                            <button data-news-id="{{ $item['title'] }}" 
                                onclick="sendInteraction('{{ addslashes($item['title']) }}', 'like', this)" 
                                class="like-button flex items-center gap-2 text-gray-600 hover:text-red-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 14.7v5.3a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V12a2 2 0 0 0-2-2h-3.6l.6-4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v4H8a2 2 0 0 0-2 2v2.7z"/>
                                </svg>
                                Like
                            </button>


                                <!-- Tombol Share -->
                            <button onclick="sharePost('{{ $item['url'] }}', '{{ addslashes($item['title']) }}', this)" 
                                class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5h6m0 0v6m0-6L10 16l-4-4-6 6"/>
                                </svg>
                                Share
                            </button>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
            </div>
        @else

        {{-- berita tranding untuk user yang belum login atau user baru --}}

            <div class="rounded-lg bg-gray-200 lg:col-span-2">
                <div class="m-10">
                    <p class="my-5 text-xl fw-bold">Berita Trending</p>
                    <div class="h-screen container grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-4 overflow-y-auto">
                        @foreach ($berita_trending as $item)            
                        <article
                            class="rounded-lg border border-gray-100 bg-white p-4 shadow-xs transition hover:shadow-lg sm:p-6">
                            <a href={{ $item['url'] }} target="_blank">
                                <img src={{$item['urlToImage'] ?? asset('img/noimage.webp')}} alt={{$item['urlToImage']}} class="rounded-lg">
                                <h3 class="mt-2 text-lg font-medium text-gray-900">
                                    {{ $item['title'] }}
                                </h3>
                            </a>
                            <ul class="flex gap-3 text-slate-400">
                                <li>{{ $item['source']['name'] }}</li>
                                {{-- <li>| {{ $item['author'] }}</li> --}}
                                <li class="fw-lighter">| {{ \Carbon\Carbon::parse($item['publishedAt'])->diffForHumans() }}</li>
                            </ul>

                            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
                                {{ $item['description'] }}
                            </p>

                            <a href={{ $item['url'] }} target="_blank" class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                Find out more
                                <span aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">
                                    &rarr;
                                </span>
                            </a>

                            <!-- Tombol Like dan Share -->
                             <div class="mt-4 flex items-center gap-4">

                            <!-- Tombol Like -->
                            <button data-news-id="{{ $item['title'] }}" 
                                onclick="sendInteraction('{{ addslashes($item['title']) }}', 'like', this)" 
                                class="like-button flex items-center gap-2 text-gray-600 hover:text-red-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 14.7v5.3a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V12a2 2 0 0 0-2-2h-3.6l.6-4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v4H8a2 2 0 0 0-2 2v2.7z"/>
                                </svg>
                                Like
                            </button>


                                <!-- Tombol Share -->
                            <button onclick="sharePost('{{ $item['url'] }}', '{{ addslashes($item['title']) }}', this)" 
                                class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5h6m0 0v6m0-6L10 16l-4-4-6 6"/>
                                </svg>
                                Share
                            </button>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    
                    <p class="text-md hover:text-slate-500 my-2 text-black transition eb-garamond-custom" ><a href="/news/trending?page=1">lihat berita trending lainnya &rarr;</a></p>

                    {{-- <div class="mt-4">
                        {{ $berita_trending->links() }}
                    </div> --}}

                </div>
            </div>
        @endif

        {{-- berita terbaru --}}

        <div class="rounded-lg bg-gray-200">
            <div class="m-10">
                <p class="my-5 text-xl fw-bold eb-garamond-custom">Berita Terbaru</p>
                <div class="h-screen container grid grid-cols-1 gap-4 overflow-y-auto">
                    @foreach ($berita_terbaru as $item)            
                        <article
                            class="rounded-lg border border-gray-100 bg-white p-4 shadow-xs transition hover:shadow-lg sm:p-6">
                            <a href={{ $item['url'] }} target="_blank">
                                <img src={{$item['urlToImage'] ?? asset('img/noimage.webp')}} alt={{$item['urlToImage']}} class="rounded-lg">
                                <h3 class="mt-2 text-lg font-medium text-gray-900">
                                    {{ $item['title'] }}
                                </h3>
                            </a>
                            <ul class="flex gap-3 text-slate-400">
                                <li>{{ $item['source']['name'] }}</li>
                                {{-- <li>| {{ $item['author'] }}</li> --}}
                                <li class="fw-lighter">| {{ \Carbon\Carbon::parse($item['publishedAt'])->diffForHumans() }}</li>
                            </ul>

                            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
                                {{ $item['description'] }}
                            </p>

                            <a href={{ $item['url'] }} target="_blank" class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                Find out more
                                <span aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">
                                    &rarr;
                                </span>
                            </a>

                            <!-- Tombol Like dan Share -->
                            <div class="mt-4 flex items-center gap-4">

                            <!-- Tombol Like -->
                            <button data-news-id="{{ $item['title'] }}" 
                                onclick="sendInteraction('{{ addslashes($item['title']) }}', 'like', this)" 
                                class="like-button flex items-center gap-2 text-gray-600 hover:text-red-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 14.7v5.3a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V12a2 2 0 0 0-2-2h-3.6l.6-4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v4H8a2 2 0 0 0-2 2v2.7z"/>
                                </svg>
                                Like
                            </button>

                                <!-- Tombol Share -->
                             <button onclick="sharePost('{{ $item['url'] }}', '{{ addslashes($item['title']) }}', this)" 
                                class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5h6m0 0v6m0-6L10 16l-4-4-6 6"/>
                                </svg>
                                Share
                            </button>
                            </div>
                        </article>
                    @endforeach
                </div>
                <p class="text-md hover:text-slate-500 my-2 text-black transition eb-garamond-custom" ><a href="/news/newest?page=1">lihat berita terbaru lainnya &rarr;</a></p>
            </div>
        </div>
    </div>

    {{-- berita trending untuk pengguna lama --}}

    @if (Auth::check() && Auth::user()->created_at->diffInMonths(now()) >= 1)    
        <div class="rounded-lg bg-gray-200 mt-7">
            <div class="p-10">
                <p class="my-5 text-xl fw-bold eb-garamond-custom">Berita Trending</p>
                <div class="container flex gap-6 overflow-x-auto max-w-screen max-h-full">
                    @foreach ($berita_trending as $item)
                        <article
                            class="min-w-[400px] rounded-lg border border-gray-100 bg-white p-6 shadow-xs transition hover:shadow-lg">
                            <a href={{ $item['url'] }} target="_blank">
                                <img src={{$item['urlToImage'] ?? asset('img/noimage.webp')}} alt={{$item['urlToImage']}} class="rounded-lg w-full h-48 object-cover">
                                <h3 class="mt-4 text-lg font-medium text-gray-900">
                                    {{ $item['title'] }}
                                </h3>
                            </a>
                            <ul class="flex gap-3 text-slate-400 mt-2">
                                <li>{{ $item['source']['name'] }}</li>
                                <li class="fw-lighter">| {{ \Carbon\Carbon::parse($item['publishedAt'])->diffForHumans() }}</li>
                            </ul>

                            <p class="mt-4 line-clamp-3 text-sm/relaxed text-gray-500">
                                {{ $item['description'] }}
                            </p>

                            <a href={{ $item['url'] }} target="_blank" class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                Find out more
                                <span aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">
                                    &rarr;
                                </span>
                            </a>

                            <!-- Tombol Like dan Share -->
                            <div class="mt-6 flex items-center gap-4">

                            <!-- Tombol Like -->
                            <button data-news-id="{{ $item['title'] }}" 
                                onclick="sendInteraction('{{ addslashes($item['title']) }}', 'like', this)" 
                                class="like-button flex items-center gap-2 text-gray-600 hover:text-red-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 14.7v5.3a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V12a2 2 0 0 0-2-2h-3.6l.6-4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v4H8a2 2 0 0 0-2 2v2.7z"/>
                                </svg>
                                Like
                            </button>

                                <!-- Tombol Share -->
                             <button onclick="sharePost('{{ $item['url'] }}', '{{ addslashes($item['title']) }}', this)" 
                                class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5h6m0 0v6m0-6L10 16l-4-4-6 6"/>
                                </svg>
                                Share
                            </button>
                            </div>
                        </article>
                    @endforeach
                </div>
                <p class="text-md hover:text-slate-500 my-2 text-black transition eb-garamond-custom" ><a href="/news/trending?page=1">lihat berita trending lainnya &rarr;</a></p>
            </div>
        </div>
    @endif
</div>
@endsection

<script>

    // check like status
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".like-button").forEach(button => {
            const newsTitle = button.dataset.newsId; 

            fetch(`/check-like-status/${encodeURIComponent(newsTitle)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.liked) {
                        button.classList.remove("text-gray-600");
                        button.classList.add("text-red-500");
                    }
                })
                .catch(error => console.error("Error fetching like status:", error));
        });
    });

    // fetch like & share information
    function sendInteraction(newsId, type, button) {
        if (!{{ Auth::check() ? 'true' : 'false' }}) {
            toggleModal();
            return;
        }

        fetch('/interact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                news_title: newsId,
                interaction_type: type
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload(); 
        })
        .catch(error => console.error('Error:', error));
    }


    // share berita dengan sharepost
    function sharePost(link, newsTitle, button) {
        if (navigator.share) {
            navigator.share({
                title: newsTitle, 
                url: link
            }).then(() => {
                fetch('/interact', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        news_title: newsTitle,
                        interaction_type: 'share'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                    button.classList.remove("text-gray-600");
                    button.classList.add("text-blue-600");
                })
                .catch(error => console.error("Error:", error));
            }).catch(err => console.log("Share failed:", err));
        } else {
            alert("Sharing not supported in this browser.");
        }
    }


    // show register modal
    function toggleRegisterModal() {
        let modal = document.getElementById("registerModal");
        modal.classList.toggle("hidden");
        setTimeout(() => {
            modal.classList.toggle("opacity-0");
        }, 10);
    }
    
    // show login modal
    function toggleModal() {
        let modal = document.getElementById("loginModal");
        modal.classList.toggle("hidden");
        setTimeout(() => {
            modal.classList.toggle("opacity-0");
        }, 10);
    }

    // switch to register modal
    function switchToRegister() {
        toggleModal(); 
        toggleRegisterModal(); 
    }
</script>
