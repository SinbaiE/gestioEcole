@props(['type', 'data', 'options'])

<div x-data="{
    type: '{{ $type }}',
    data: {{ json_encode($data) }},
    options: {{ json_encode($options) }},
    init() {
        new Chart(this.$refs.canvas, {
            type: this.type,
            data: this.data,
            options: this.options,
        });
    }
}" class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
    <canvas x-ref="canvas"></canvas>
</div>
