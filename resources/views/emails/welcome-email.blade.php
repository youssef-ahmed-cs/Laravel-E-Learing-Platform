@vite('resources/css/app.css')

@component('mail::message')
    <div class="rounded-lg bg-gray-50 p-6">
        <h1 class="mb-4 text-2xl font-bold">Welcome</h1>
        <p class="mb-6 text-gray-700">Hi {{ $name }}, Welcome to our platform!</p>
        @component('mail::button', ['url' => 'https://github.com/youssef-ahmed-cs'])
            Get Started
        @endcomponent
    </div>
@endcomponent
