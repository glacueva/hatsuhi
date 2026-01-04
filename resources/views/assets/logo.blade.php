@php
$isLogin = Request::segment(2) == 'login';
$logoSize = $isLogin ? "5rem" : "1rem";
$display = $isLogin ? "block": "ruby";
@endphp

<div style="display:{{ $display }}">
    
    <!-- Column 1: Logo -->
    <div>
        <img src="/assets/Hatsuhi.png" 
             alt="Logo" 
             class="h-8 w-auto"
             style="max-width: {{ $logoSize }};">
    </div>
    
    <!-- Column 2: Text -->
    @if(!$isLogin)
    <div>
        <span class="text-xl font-bold tracking-tight text-gray-950 dark:text-white whitespace-nowrap">
            HATSUHI
        </span>
    </div>
    @else
    @endif
</div>