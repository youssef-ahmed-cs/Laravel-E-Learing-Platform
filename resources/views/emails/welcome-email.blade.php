@vite('resources/css/app.css')
@component('mail::message')
    <div class="bg-gray-50 p-6 rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Welcome</h1>
        <p class="mb-6 text-gray-700">Hi {{$name}}, Welcome to our platform!</p>
        @component('mail::button', ['url' => 'https://github.com/youssef-ahmed-cs'])
            <span class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Get Started</span>
        @endcomponent
    </div>
@endcomponent
