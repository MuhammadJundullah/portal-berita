    <div class="w-full rounded-lg z-10 bg-cover bg-center">
        <ul class="flex justify-between items-center">
            <li class="text-white StyreneB">{{ \Carbon\Carbon::now()->locale('en')->isoFormat('dddd, D MMMM YYYY') }}</li>
                @if(Auth::check())
                    <h2 class="text-white tracking-[.25em] italic StyreneB">Welcome {{ Auth::user()->name }} ! happy reading and have a nice day.</h2>
                @endif
            </li>
            <div class="relative">
                <form action="{{ url('/search') }}" method="GET">
                    <input
                        type="text"
                        name="query"
                        class="w-full rounded-lg border-gray-200 p-4 pe-12 text-sm shadow-xs StyreneB mb-5"
                        placeholder="Find News"
                        value="{{ request('query') }}"
                    />
                    <button type="submit" class="absolute inset-y-0 end-0 grid place-content-center px-4 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </button>
                </form>
            </div>
        </ul>
    </div>