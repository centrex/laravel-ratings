<div class="relative inline-flex gap-1 group">
    @for ($i = 1; $i <= $starRating; $i++)
        @php
            $width = $this->getStarWidth($i)
        @endphp
        <div
            class="inline-block relative {{ $static ? '' : 'cursor-pointer' }}"
            wire:mouseover="{{ $static ? '' : '$set(\'hoverValue\', ' . $i . ')' }}"
            wire:mouseout="{{ $static ? '' : '$set(\'hoverValue\', 0)' }}"
            wire:click="{{ $static ? '' : 'setRating(' . $i . ')' }}"
        >
            <!-- Outlined Star -->
            {{-- <i class="{{ $iconBg }} {{ $iconBgColor }} {{ $size }}"></i> --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconBgColor }} {{ $size }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
            </svg>


            <!-- Filled Star -->
            <div class="absolute top-0 left-0 whitespace-nowrap overflow-hidden w-[{{ $width }}%]">
                {{-- <i class="{{ $iconFg }} {{ $iconFgColor }} {{ $size }}"></i> --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $iconFgColor }} {{ $size }}">
                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    @endfor

    @if($modelRated && $showSuccess)
        <div class="ml-6">
            {{-- <i class="fas fa-circle-check text-green-500 {{ $size }}"></i> --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-green-500 {{ $size }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    @endif

    @if($this->ratingCanBeChanged())
        <div class="hidden ml-6 group-hover:block">
            <a class="cursor-pointer" wire:click="undoRating">
                {{-- <i class="fas fa-circle-xmark {{ $size }} text-red-500 hover:text-red-600"></i> --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $size }} text-red-500 hover:text-red-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>
        </div>
    @endif
</div>
