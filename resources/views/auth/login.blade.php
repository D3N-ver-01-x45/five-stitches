<x-guest-layout>
    <x-slot:title>Log in</x-slot:title>

    <h1 class="font-heading text-[2rem] font-bold leading-tight text-ink">Welcome back</h1>
    <p class="mt-2 text-sm text-muted">Log in to your Five Stitches account.</p>

    <!-- Session Status (e.g. "Your password has been reset.") -->
    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5"
          x-data="{ submitting: false }" x-on:submit="submitting = true">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email"
                          :value="old('email')" placeholder="you@example.com"
                          required autofocus autocomplete="username"
                          aria-describedby="email-error" />
            <x-input-error id="email-error" :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-forest underline-offset-4 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-forest rounded"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="relative mt-2">
                <x-text-input id="password" class="block w-full pr-20"
                              x-bind:type="show ? 'text' : 'password'"
                              type="password" name="password"
                              placeholder="••••••••"
                              required autocomplete="current-password"
                              aria-describedby="password-error" />
                <button type="button" x-on:click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center px-4 text-sm font-medium text-forest hover:text-ink focus:outline-none focus-visible:underline"
                        x-bind:aria-pressed="show.toString()"
                        x-bind:aria-label="show ? 'Hide password' : 'Show password'">
                    <span x-text="show ? 'Hide' : 'Show'">Show</span>
                </button>
            </div>
            <x-input-error id="password-error" :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2.5">
            <input id="remember_me" type="checkbox" name="remember"
                   class="h-[18px] w-[18px] rounded border-line text-forest focus:ring-forest">
            <span class="text-sm text-muted">{{ __('Keep me logged in') }}</span>
        </label>

        <x-primary-button class="w-full" x-bind:disabled="submitting">
            <span x-show="!submitting">{{ __('Log in') }}</span>
            <span x-show="submitting" x-cloak>{{ __('Logging in…') }}</span>
        </x-primary-button>
    </form>

    @if (Route::has('register'))
        <p class="mt-8 border-t border-line pt-6 text-center text-sm text-muted">
            New to Five Stitches?
            <a href="{{ route('register') }}" class="font-semibold text-forest underline-offset-4 hover:underline">Create an account</a>
        </p>
    @endif

    <p class="mt-4 text-center">
        <a href="{{ url('/') }}" class="text-sm text-muted underline-offset-4 hover:text-ink hover:underline">← Back to shop</a>
    </p>
</x-guest-layout>
