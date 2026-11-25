<x-app-layout>
    <x-slot:title>My account</x-slot:title>

    <x-slot name="header">
        <h1 class="font-heading text-[40px] font-bold leading-none text-ink">My account</h1>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <div class="rounded-2xl border border-line bg-white p-5 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="rounded-2xl border border-line bg-white p-5 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-2xl border border-line bg-white p-5 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
