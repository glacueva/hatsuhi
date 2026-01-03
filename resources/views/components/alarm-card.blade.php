@props(['alarm', 'isRecurring', 'date', 'daysRemaining'])

<div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">

    <div class="flex justify-between items-start">
        <div class="flex-1">

            {{-- Título --}}
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-900">{{ $alarm->concept }}</span>

                <span class="px-2 py-0.5 rounded text-xs font-medium
                    {{ $isRecurring ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $isRecurring ? 'Recurring' : 'One-time' }}
                </span>
            </div>

            {{-- Categoría / Fecha / Periodicidad --}}
            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600">

                <div class="flex items-center">
                    <x-filament::icon icon="heroicon-o-tag" class="w-3 h-3 mr-1.5 text-gray-400" />
                    {{ $alarm->category->name }}
                </div>

                <div class="flex items-center">
                    <x-filament::icon icon="heroicon-o-calendar" class="w-3 h-3 mr-1.5 text-gray-400" />

                    @if($date)
                        {{ $date->format('M d, Y') }}
                        <span class="ml-2 text-xs px-2 py-0.5 rounded
                            {{ $isRecurring ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $isRecurring ? 'Calculated' : 'Exact date' }}
                        </span>
                    @else
                        <span class="text-gray-400">No occurrence this month</span>
                    @endif
                </div>

                @if($isRecurring)
                    <div class="flex items-center">
                        <x-filament::icon icon="heroicon-o-arrow-path" class="w-3 h-3 mr-1.5 text-gray-400" />
                        Every {{ $alarm->periodicity_times }} {{ str($alarm->periodicity_unit)->plural($alarm->periodicity_times) }}
                    </div>
                @endif
            </div>

            {{-- Importe --}}
            <div class="mt-2 flex items-center text-sm text-gray-600">
                <x-filament::icon icon="heroicon-o-currency-dollar" class="w-3 h-3 mr-1.5 text-gray-400" />
                {{ $alarm->user->currency->symbol }}{{ number_format($alarm->amount, 2) }}
            </div>

            {{-- Fecha de inicio --}}
            @if($isRecurring && $alarm->date)
                <div class="mt-1 text-xs text-gray-500">
                    Started on {{ \Carbon\Carbon::parse($alarm->date)->format('M d, Y') }}
                </div>
            @endif
        </div>

        {{-- Acciones --}}
        <div class="ml-4 flex space-x-2">
            <button wire:click="executeAlarm({{ $alarm->id }})"
                wire:confirm="Create a movement for this alarm?"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                <x-filament::icon icon="heroicon-o-check" class="w-3 h-3" />
            </button>

            <a href="{{ route('filament.admin.resources.movement-alarms.edit', $alarm) }}"
               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                <x-filament::icon icon="heroicon-o-pencil" class="w-3 h-3" />
            </a>
        </div>
    </div>

    {{-- Badge de fecha --}}
    @if($date && $daysRemaining >= 0)
        @php
            $badge = match(true) {
                $daysRemaining === 0 => ['bg-red-100 text-red-800', 'Due today'],
                $daysRemaining <= 7 => ['bg-yellow-100 text-yellow-800', "In $daysRemaining days"],
                default => ['bg-green-100 text-green-800', "In $daysRemaining days"],
            };
        @endphp

        <span class="inline-flex items-center px-2 py-1 mt-2 rounded-full text-xs font-medium {{ $badge[0] }}">
            <x-filament::icon icon="heroicon-o-clock" class="w-3 h-3 mr-1" />
            {{ $badge[1] }}
        </span>
    @endif
</div>
