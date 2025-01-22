<div class="mt-4 flex items-center justify-between">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <div>
            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </div>
    </form>

    <a href="{{ route('logout') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                       {{-- onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" --}}>
                                        Logout
                                    </a>
</div>
