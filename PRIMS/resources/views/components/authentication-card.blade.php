<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-blend-overlay bg-prims-azure-900 bg-opacity-50" style="background-image: url(img/homepage-bg.png);">
    <div class="w-full sm:max-w-md px-6 py-4 bg-prims-yellow-1 shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex justify-center my-3">
            {{ $logo }}
        </div>
        {{ $slot }}
    </div>
</div>
