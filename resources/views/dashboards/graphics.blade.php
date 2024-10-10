<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.dashboard_graphics') }}
        </h2>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <style>
            #chart-approved-pending, #chart-pending-expirated {
                max-width: 500px;
                margin: 0 auto;
            }
        </style>
    </x-slot>

    @section('content')
        <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.adasd') }}</h1>
            <h1>Total de facturas por estado</h1>

            <!-- Gráfico para APROBADAS y PENDIENTES -->
            <h2>Aprobadas y Pendientes</h2>
            <div id="chart-approved-pending"></div>

            <!-- Gráfico para PENDIENTES y EXPIRADAS -->
            <h2>Pendientes y Expiradas</h2>
            <div id="chart-pending-expirated"></div>
        </div>
        <script>
            // Extraer datos de los arrays para el gráfico Aprobadas y Pendientes
            var approvedPendingTotals = @json(array_column($approved_pending, 'total'));
            var approvedPendingLabels = @json(array_column($approved_pending, 'status'));
    
            // Extraer datos de los arrays para el gráfico Pendientes y Expiradas
            var pendingExpiratedTotals = @json(array_column($pending_expirated, 'total'));
            var pendingExpiratedLabels = @json(array_column($pending_expirated, 'status'));
    
            // Gráfico para APROBADAS y PENDIENTES
            var optionsApprovedPending = {
                chart: {
                    type: 'pie',
                    width: 300,
                    height: 300
                },
                series: approvedPendingTotals, // Totales para aprobadas y pendientes
                labels: approvedPendingLabels, // Estados: APROBADO, PENDIENTE
            };
    
            var chartApprovedPending = new ApexCharts(document.querySelector("#chart-approved-pending"), optionsApprovedPending);
            chartApprovedPending.render();
    
            // Gráfico para PENDIENTES y EXPIRADAS
            var optionsPendingExpirated = {
                chart: {
                    type: 'pie',
                    width: 300,
                    height: 300
                },
                series: pendingExpiratedTotals, // Totales para pendientes y expiradas
                labels: pendingExpiratedLabels, // Estados: PENDIENTE, EXPIRADA
            };
    
            var chartPendingExpirated = new ApexCharts(document.querySelector("#chart-pending-expirated"), optionsPendingExpirated);
            chartPendingExpirated.render();
        </script>
    @endsection
</x-app-layout>
