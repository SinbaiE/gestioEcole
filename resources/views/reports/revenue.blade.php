<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Revenue Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Revenue Report') }}</h3>
                    <div class="mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">{{ __('Monthly Revenue') }}</h4>
                                <div class="mt-4">
                                    <canvas id="monthlyRevenueChart"></canvas>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">{{ __('Revenue by Room Type') }}</h4>
                                <div class="mt-4">
                                    <canvas id="revenueByRoomTypeChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <h4 class="text-lg font-medium text-gray-900">{{ __('Service Revenue') }}</h4>
                            <div class="mt-4">
                                <canvas id="serviceRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
            const monthlyRevenueChart = new Chart(monthlyRevenueCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
                    datasets: [{
                        label: 'Monthly Revenue',
                        data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                }
            });

            const revenueByRoomTypeCtx = document.getElementById('revenueByRoomTypeChart').getContext('2d');
            const revenueByRoomTypeChart = new Chart(revenueByRoomTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($revenueByRoomType->pluck('name')) !!},
                    datasets: [{
                        label: 'Revenue by Room Type',
                        data: {!! json_encode($revenueByRoomType->pluck('revenue')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });

            const serviceRevenueCtx = document.getElementById('serviceRevenueChart').getContext('2d');
            const serviceRevenueChart = new Chart(serviceRevenueCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($serviceRevenue->pluck('category')) !!},
                    datasets: [{
                        label: 'Service Revenue',
                        data: {!! json_encode($serviceRevenue->pluck('revenue')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 255, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
        </script>
    @endpush
</x-app-layout>
