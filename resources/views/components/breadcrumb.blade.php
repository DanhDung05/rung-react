@if (!empty($items))
    <nav class="flex items-center flex-wrap gap-2 text-sm {{ $variant === 'light' ? 'text-white' : 'text-text-muted' }}" aria-label="Breadcrumb">
        <ol class="flex items-center flex-wrap gap-2">
            @foreach ($items as $index => $item)
                @php
                    $isLast = $index === count($items) - 1;
                    $url = $item['url'] ?? null;
                    
                    $linkClass = $variant === 'light' 
                        ? 'text-white hover:text-white/80 transition-base' 
                        : 'hover:text-primary transition-base';
                        
                    $currentClass = $variant === 'light'
                        ? 'text-white font-medium'
                        : 'text-text font-medium';
                        
                    $iconClass = $variant === 'light' ? 'text-white/80' : 'text-text-subtle';
                    
                    $shadowStyle = $variant === 'light' ? 'text-shadow: 0 2px 6px rgba(0,0,0,0.8);' : '';
                @endphp

                <li class="flex items-center gap-2">
                    @if (!$isLast && $url)
                        <a href="{{ $url }}" class="{{ $linkClass }}" style="{{ $shadowStyle }}">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="{{ $currentClass }}" aria-current="page" style="{{ $shadowStyle }}">
                            {{ $item['label'] }}
                        </span>
                    @endif

                    @if (!$isLast)
                        <svg class="w-4 h-4 {{ $iconClass }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="{{ $shadowStyle }}">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif