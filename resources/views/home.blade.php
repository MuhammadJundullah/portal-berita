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
                    <p class="my-5 text-xl fw-bold">Berita Menarik Untuk Anda</p>
                    <div class="container grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-4 ">
                        <article
                            class="rounded-lg border border-gray-100 bg-white p-4 shadow-xs transition hover:shadow-lg sm:p-6">
                            <span class="inline-block rounded-sm bg-blue-600 p-2 text-white">
                                <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="size-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                >
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"
                                />
                                </svg>
                            </span>

                            <a href="#">
                                <h3 class="mt-0.5 text-lg font-medium text-gray-900">
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                                </h3>
                            </a>

                            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae dolores, possimus pariatur
                                animi temporibus nesciunt praesentium dolore sed nulla ipsum eveniet corporis quidem, mollitia
                                itaque minus soluta, voluptates neque explicabo tempora nisi culpa eius atque dignissimos.
                                Molestias explicabo corporis voluptatem?
                            </p>

                            <a href="#" class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                Find out more

                                <span aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">
                                &rarr;
                                </span>
                            </a>
                        </article>
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
                            <article class="rounded-lg border border-gray-100 bg-white p-4 shadow-xs transition hover:shadow-lg sm:p-6">
                                <a href="#">
                                    <h3 class="mt-0.5 text-lg font-medium text-gray-900">
                                        <a href={{ $item->link }} terget="_blank">{{ $item->headline }}</a>
                                    </h3>
                                </a>
                                <ul class="flex gap-3 text-slate-400">
                                    <li>{{ $item->category }}</li>
                                    <li>| {{ $item->authors }}</li>
                                    <li class="fw-lighter">| {{ \Carbon\Carbon::parse($item->date)->diffForHumans() }}</li>
                                </ul>

                                <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
                                    {{ $item->short_description }}
                                </p>

                                <a href="{{ $item->link }}" target='_blank' class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                    Find out more
                                    <span aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">
                                        &rarr;
                                    </span>
                                </a>

                                <!-- Tombol Like dan Share -->
                                <div class="mt-4 flex items-center gap-4">

                                <!-- Tombol Like -->
                                <button data-news-id="{{ $item->id }}" 
                                    onclick="sendInteraction({{ $item->id }}, 'like', this)" 
                                    class="like-button flex items-center gap-2 text-gray-600 hover:text-red-500 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 14.7v5.3a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V12a2 2 0 0 0-2-2h-3.6l.6-4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v4H8a2 2 0 0 0-2 2v2.7z"/>
                                    </svg>
                                    Like
                                </button>

                                <!-- Tombol Share -->
                                <button onclick="sharePost('{{ $item->link }}', {{ $item->id }}, this)" 
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

                    <div class="mt-4">
                        {{ $berita_trending->links() }}
                    </div>

                </div>
            </div>
        @endif

        {{-- berita terbaru --}}

        <div class="rounded-lg bg-gray-200">
            <div class="m-10">
                <p class="my-5 text-xl fw-bold">Berita Terbaru</p>
                <div class="h-screen container grid grid-cols-1 gap-4 overflow-y-auto">
                    @foreach ($berita_terbaru as $item)            
                        <article
                            class="rounded-lg border border-gray-100 bg-white p-4 shadow-xs transition hover:shadow-lg sm:p-6">
                            <a href="#">
                                <h3 class="mt-0.5 text-lg font-medium text-gray-900">
                                    {{ $item->headline }}
                                </h3>
                            </a>
                            <ul class="flex gap-3 text-slate-400">
                                <li>{{ $item->category }}</li>
                                <li>| {{ $item->authors }}</li>
                                <li class="fw-lighter">| {{ \Carbon\Carbon::parse($item->date)->diffForHumans() }}</li>
                            </ul>

                            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
                                {{ $item->short_description }}
                            </p>

                            <a href="{{ $item->link }}" target='_blank' class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                Find out more
                                <span aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">
                                    &rarr;
                                </span>
                            </a>

                            <!-- Tombol Like dan Share -->
                            <div class="mt-4 flex items-center gap-4">

                            <!-- Tombol Like -->
                            <button data-news-id="{{ $item->id }}" 
                                onclick="sendInteraction({{ $item->id }}, 'like', this)" 
                                class="like-button flex items-center gap-2 text-gray-600 hover:text-red-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 14.7v5.3a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V12a2 2 0 0 0-2-2h-3.6l.6-4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v4H8a2 2 0 0 0-2 2v2.7z"/>
                                </svg>
                                Like
                            </button>

                            <!-- Tombol Share -->
                            <button onclick="sharePost('{{ $item->link }}', {{ $item->id }}, this)" 
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
    </div>

    {{-- berita trending untuk pengguna terauthentikasi atau pengguna lama --}}

    @if (Auth::check() && Auth::user()->created_at->diffInMonths(now()) >= 1)    
        <div class="rounded-lg bg-gray-200 mt-7">
            <div class="p-10">
                <p class="my-5 text-xl fw-bold">Berita Trending</p>
                <div class="container flex gap-4 overflow-x-auto max-w-screen max-h-full">
                    @foreach ($berita_trending as $item)            
                        <article class="w-96 flex-shrink-0 rounded-lg border border-gray-100 bg-white p-4 shadow-xs transition hover:shadow-lg sm:p-6">
                            <a href="#">
                                <h3 class="mt-0.5 text-lg font-medium text-gray-900">
                                    {{ $item->headline }}
                                </h3>
                            </a>
                            <ul class="flex gap-3 text-slate-400">
                                <li>{{ $item->category }}</li>
                                <li>| {{ $item->authors }}</li>
                                <li class="fw-lighter">| {{ \Carbon\Carbon::parse($item->date)->diffForHumans() }}</li>
                            </ul>

                            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
                                {{ $item->short_description }}
                            </p>

                            <a href="{{ $item->link }}" target='_blank' class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                Find out more
                                <span aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">
                                    &rarr;
                                </span>
                            </a>

                            <!-- Tombol Like dan Share -->
                            <div class="mt-4 flex items-center gap-4">

                            <!-- Tombol Like -->
                            <button data-news-id="{{ $item->id }}" 
                                onclick="sendInteraction({{ $item->id }}, 'like', this)" 
                                class="like-button flex items-center gap-2 text-gray-600 hover:text-red-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 14.7v5.3a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V12a2 2 0 0 0-2-2h-3.6l.6-4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v4H8a2 2 0 0 0-2 2v2.7z"/>
                                </svg>
                                Like
                            </button>

                            <!-- Tombol Share -->
                            <button onclick="sharePost('{{ $item->link }}', {{ $item->id }}, this)" 
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
    @endif
</div>
@endsection

<script>
    // check like status
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".like-button").forEach(button => {
            const newsId = button.dataset.newsId;
            fetch(`/check-like-status/${newsId}`)
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
    function sendInteraction(newsId, type) {
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
                news_id: newsId,
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
    function sharePost(link, newsId, button) {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: link
            }).then(() => {
                sendInteraction(newsId, 'share', button); 
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
