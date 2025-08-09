@props(['data', 'title'])

@php
$options = [
    'responsive' => true,
    'maintainAspectRatio' => false,
    'scales' => [
        'y' => [
            'beginAtZero' => true,
        ],
    ],
    'plugins' => [
        'legend' => [
            'display' => false,
        ],
        'title' => [
            'display' => true,
            'text' => $title,
        ],
    ],
];
@endphp

<x-chart type="line" :data="$data" :options="$options" />
