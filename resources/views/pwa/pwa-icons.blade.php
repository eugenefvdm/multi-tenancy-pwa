@php
    $config = config('pwa.manifest');
    $icons = $config['icons'] ?? [];
    $screenshots = $config['screenshots'] ?? [];
@endphp

<!-- Add a new section to output the PWA splash from the manifest -->
<div class="mt-4 p-4 bg-gray-100 rounded-lg">
    <h3 class="text-xl font-semibold mb-4">PWA Splash</h3>
    <div class="flex flex-col items-center">
        <div class="mt-2 text-sm text-gray-600">
            <div>1125x2436</div>
        </div>
        <img src="{{ $config['splash']['1125x2436'] }}" 
             alt="PWA Splash" 
             class="w-full max-w-md object-contain rounded-lg shadow-md"
             title="PWA Splash">      
    </div>
</div>

<div class="mt-4 p-4 bg-gray-100 rounded-lg">
    <h3 class="text-xl font-semibold mb-4">PWA Screenshots</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($screenshots as $screenshot)
            <div class="flex flex-col items-center">
                <div class="mt-2 text-sm text-gray-600">
                    <div>{{ $screenshot['label'] }}</div>
                    <div>{{ $screenshot['sizes'] }} - {{ $screenshot['form_factor'] }}</div>
                </div>
                <img src="{{ $screenshot['src'] }}" 
                     alt="{{ $screenshot['label'] }}" 
                     class="w-full max-w-md object-contain rounded-lg shadow-md"
                     title="{{ $screenshot['label'] }} ({{ $screenshot['sizes'] }})">                
            </div>
        @endforeach
    </div>
</div>

<div class="mt-4 p-4 bg-gray-100 rounded-lg">
    <h3 class="text-xl font-semibold mb-4">PWA Icons</h3>
    <div class="grid grid-cols-2 gap-4">
        @foreach($icons as $size => $icon)
            <div class="flex flex-col items-center">
                <span class="mt-2 text-sm text-gray-600">{{ $size }}</span>
                <img src="{{ $icon['path'] }}" 
                     alt="PWA Icon {{ $size }}" 
                     class="w-24 h-24 object-contain"
                     title="Size: {{ $size }}">                
            </div>
        @endforeach
    </div>
</div> 