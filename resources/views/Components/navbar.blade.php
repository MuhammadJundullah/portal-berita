<div class="w-full z-10">
  <nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 StyreneB">
      <div class="relative flex h-20 items-center sm:px-0 px-10 text-sm sm:text-lg">
        <div class="flex flex-1 items-center sm:items-stretch sm:justify-start">
          <div class="flex shrink-0 items-center">
            <img class="h-20 w-auto" src="{{ asset('img/nobck.png') }}" alt="NewsToday.com">
            <p  class="rounded-md sm:px-3 py-2 text-sm fw-light text-white sm:block hidden"" aria-current="page"><span class="fw-bold">News</span>Today.com</p>
          </div>
        </div>

        @if(Auth::check())
          <div class="pt-2">
            <ul class="flex gap-4">
              <li>
                <a href={{ route('profile', ['id' => Auth::user()->id]) }} class="text-gray-400 hover:text-white">My Profile</a>
              </li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="text-gray-400 hover:text-white">Logout</button>
                </form>
              </li>
            </ul>
            
          </div>
        @endif

        @if(!Auth::check())
            <button class="text-gray-400 hover:text-white" onclick="toggleModal()">Login/Register</button>
        @endif

      </div>
    </div>
  </nav>

</div>
