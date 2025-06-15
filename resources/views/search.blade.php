@extends('Components.layout')

@section('content')

<!-- Modal -->
@include('Components.login')

<div class="md:p-10 p-2">

@include('Components.topbar')

    <div class="grid grid-cols-1 gap-4">
        <div class="rounded-lg bg-gray-200 lg:col-span-2 mt-5">
            <div class="sm:m-10 m-5">
                <a href={{ route('home') }} class="hover:text-slate-900 text-slate-500 StyreneB" >&larr; kembali</a>
                <p class="my-5 sm:text-xl fw-bold StyreneB">Menampilkan pencarian untuk "{{ $q }}"</p>
                <div class="container grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-4 ">
                    @foreach ($news['articles'] as $item)                        
                        <article
                            class="rounded-lg border border-gray-100 bg-white p-4 shadow-xs transition hover:shadow-lg sm:p-6">
                            <a href={{ $item['url'] }} target="_blank">
                                <img src={{$item['urlToImage'] ?? asset('img/noimage.webp') }} alt={{$item['urlToImage']}} class="rounded-lg">
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

                            <a href={{ $item['url'] }} target='_blank' class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
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

            <!-- Pagination -->
            <div class="w-full mt-10 mx-auto">
                @if(request('page', 1) > 1)
                    &larr; <a href="?query={{ request('query') }}&page={{ request('page', 1) - 1 }}">Previous</a> |
                @endif
                    <a href="?query={{ request('query') }}&page={{ request('page', 1) + 1 }}">Next Page</a> &rarr;
            </div>

            </div>
        </div>
    </div>
</div>
@endsection

<!-- Script  -->
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