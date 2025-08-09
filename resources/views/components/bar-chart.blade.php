@props(['data', 'title'])

@php
$options = [
    'responsive' => true,
    'maintainAspectRatio' => false,
    'scales' => [
        'y' => [
            'beginAtZero' => true,
            'max' => 100,
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

<x-chart type="bar" :data="$data" :options="$options" />
