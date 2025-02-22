@extends('Components.layout')

@section('content')

<!-- Modal -->
@include('Components.login')

<div class="md:p-10 p-2">

@include('Components.topbar')

    <div class="grid grid-cols-1 gap-4">
        <div class="rounded-lg bg-gray-200 lg:col-span-2 mt-5">
            <div class="m-10">
                <a href={{ route('home') }} class="hover:text-slate-900 text-slate-500" >&larr; kembali</a>
                <p class="my-5 text-xl fw-bold">Menampilkan pencarian untuk " {{ $q }} "</p>
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
                        </article>
                    @endforeach
                </div>

            <!-- Pagination -->
            <div class="w-full mt-10 mx-auto">
                @if(request('page', 1) > 1)
                    &larr; <a href="?query={{ request('query') }}&page={{ request('page', 1) - 1 }}">Previous</a> |
                @endif
                    <a href="?query={{ request('query') }}&page={{ request('page', 1) + 1 }}">Next</a> &rarr;
            </div>

            </div>
        </div>
    </div>
</div>
@endsection

<!-- Script  -->
{{-- <script>

    // check like
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

    // like & share
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

    // open/close modal 
    function toggleModal() {
        const modal = document.getElementById('loginModal');
        modal.classList.toggle('hidden');
        if (!modal.classList.contains('hidden')) {
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
            modal.firstElementChild.classList.add('scale-100');
        } else {
            modal.classList.add('opacity-0');
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
        }
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
</script> --}}

