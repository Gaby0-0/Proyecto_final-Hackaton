@props(['title', 'value', 'icon', 'trend' => null, 'trendValue' => null, 'color' => 'blue'])

@php
$colorClasses = [
    'blue' => 'from-blue-500 to-blue-600',
    'green' => 'from-green-500 to-green-600',
    'purple' => 'from-purple-500 to-purple-600',
    'orange' => 'from-orange-500 to-orange-600',
    'red' => 'from-red-500 to-red-600',
    'indigo' => 'from-indigo-500 to-indigo-600',
];
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $value }}</p>

                @if($trend && $trendValue)
                <div class="mt-2 flex items-center text-sm">
                    @if($trend === 'up')
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span class="text-green-600 font-medium">{{ $trendValue }}</span>
                    @else
                    <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                    <span class="text-red-600 font-medium">{{ $trendValue }}</span>
                    @endif
                    <span class="text-gray-500 ml-1">vs mes anterior</span>
                </div>
                @endif
            </div>

            <div class="w-12 h-12 bg-gradient-to-br {{ $colorClasses[$color] }} rounded-lg flex items-center justify-center">
                <i class="{{ $icon }} text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>
