<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote App</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body>
<div id="wrapper" class="bg-gray-900 min-h-screen">
    <nav class="border-gray-200 px-2 sm:px-4 py-2.5 bg-gray-800">
        <div class="container flex flex-wrap items-center justify-between mx-auto">
            <a href="#" class="flex items-center">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Quote App</span>
            </a>
        </div>
    </nav>
    <div class="container mx-auto mt-4">
        <div class="mx-4">
            <livewire:add />
    
            <livewire:display />
        </div>
    </div>
</div>
@vite('resources/js/app.js')
@livewireScripts
</body>
</html>
