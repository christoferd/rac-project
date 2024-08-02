<x-guest-layout>
    <div class="relative sm:top-[-60px] px-4 bg-gray-100">
        <x-authentication-card>
            <x-slot name="logo">
                <x-authentication-card-logo/>
            </x-slot>

            <x-validation-errors class="mb-4"/>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {!! session('status') !!}
                </div>
            @endif

            <form method="POST" action="{!! route('login') !!}">
                @csrf

                <div>
                    <x-label for="email" value="{!! __tCsv('Name,or,Email') !!}"/>
                    <x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus
                             autocomplete="username"/>
                </div>

                <div x-data={show:false} class="mt-4">
                    <x-label for="password" value="{!! __('Password') !!}"/>
                    <div class="relative">
                        <x-input x-ref="pword" id="password" class="block mt-1 w-full" type="password" name="password" required
                                 autocomplete="current-password"/>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 ptr"
                             x-on:click.prevent="console.log('show'); show=!show; $refs.pword.setAttribute('type', (show?'text':'password'))"
                        >
                            <x-heroicon-o-eye-slash x-show="!show" class="w-5 h-5 text-gray-600"/>
                            <x-heroicon-o-eye x-show="show" class="w-5 h-5 text-gray-600"/>
                        </div>
                    </div>
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember"/>
                        <span class="ml-2 text-sm text-gray-600">{!! __('Remember me') !!}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (\Illuminate\Support\Facades\Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                           href="{{ route('password.request') }}">
                            {!! __('Forgot your password?') !!}
                        </a>
                    @endif

                    <x-button class="ml-4">
                        {!! __('Login') !!}
                    </x-button>
                </div>
            </form>
        </x-authentication-card>
    </div>
</x-guest-layout>
