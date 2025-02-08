@extends('Components.layout')

@section('content')

<!-- Modal -->
<div id="loginModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden opacity-0 transition-opacity duration-300 ease-in-out">
    <div class="bg-white p-6 rounded-lg shadow-lg w-80 transform scale-95 transition-transform duration-300 ease-in-out">
        <h2 class="text-xl font-semibold mb-4">Login</h2>
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
                {{ $errors->first('email') }}
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label class="block mb-2">Username</label>
            <input type="text" name="name" class="w-full p-2 border rounded mb-4" required>
            <label class="block mb-2">Password</label>
            <input type="password" name="password" class="w-full p-2 border rounded mb-4" required>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Login</button>
        </form>
        <button class="mt-4 w-full bg-gray-300 p-2 rounded" onclick="toggleModal()">Close</button>
    </div>
</div>

<div class="md:p-10 p-2">
    <div class="w-full mb-7 rounded-lg bg-gray-200">
        <ul class="p-5 flex justify-between items-center">
            <li>20 Januari 2023</li>
            <li> 
                @if(Auth::check())
                    <h2>Welcome {{ Auth::user()->name }} !</h2>
                @endif
            </li>
            <div class="relative">
                <input
                type="email"
                class="w-full rounded-lg border-gray-200 p-4 pe-12 text-sm shadow-xs"
                placeholder="Find News"
                />

                <span class="absolute inset-y-0 end-0 grid place-content-center px-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </span>
            </div>
        </ul>
    </div>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">
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
        <div class="rounded-lg bg-gray-200">
            <div class="m-10">
                <p class="my-5 text-xl fw-bold">Berita Terbaru</p>
                <div class="container grid grid-cols-1 gap-4">
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
    <div class="rounded-lg bg-gray-200 mt-7">
         <div class="p-10">
            <p class="my-5 text-xl fw-bold">Berita Tranding</p>
            <div class="container grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4">
                @foreach ($berita_trending as $item)            
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
@endsection

<!-- Script  -->
<script>

    // check like
    document.addEventListener("DOMContentLoaded", function () {
        // Ambil semua tombol Like
        document.querySelectorAll(".like-button").forEach(button => {
            const newsId = button.dataset.newsId;

            // Fetch status Like dari backend
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
    function sendInteraction(newsId, type, button = null) {
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
            alert(data.message); // Feedback ke user

            if (type === 'like' && button) {
                // Toggle warna tombol Like
                button.classList.toggle("text-red-500");
                button.classList.toggle("text-gray-600");
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function sharePost(link, newsId, button) {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: link
            }).then(() => {
                sendInteraction(newsId, 'share', button); // Catat interaksi Share
            }).catch(err => console.log("Share failed:", err));
        } else {
            alert("Sharing not supported in this browser.");
        }
    }
</script>

