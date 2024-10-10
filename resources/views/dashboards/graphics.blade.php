<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.dashboard_graphics') }}
        </h2>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <style>
            #chart-approved-pending, #chart-pending-expirated, #chart-approved-expirated {
                max-width: 700px;
                margin: 0 auto;
            }
        </style>
    </x-slot>

    @section('content')
        <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.graphics_site') }} {{ $site->name }} - {{ $site->slug }}</h1>
            <h1>{{ __('messages.graphics_label') }}</h1>

            <h2>{{ __('messages.payed_not_payed') }}</h2>
            <div id="chart-approved-pending"></div>

            <h2>{{ __('messages.not_payed_expirated') }}</h2>
            <div id="chart-pending-expirated"></div>

            <h2>{{ __('messages.payed_expirated') }}</h2>
            <div id="chart-approved-expirated"></div>
        
        </div>
        <script>
            var payed_not_payed_totals = @json(array_column($payed_not_payed, 'total'));
            var payed_not_payed_labels = @json(array_column($payed_not_payed, 'status'));
    
            var not_payed_expirated_totals = @json(array_column($not_payed_expirated, 'total'));
            var not_payed_expirated_labels = @json(array_column($not_payed_expirated, 'status'));

            var payed_expirated_totals = @json(array_column($payed_expirated, 'total'));
            var payed_expirated_labels = @json(array_column($payed_expirated, 'status'));

            var options_payed_not_payed = {
                chart: {
                    type: 'pie',
                    width: 300,
                    height: 300
                },
                series: payed_not_payed_totals,
                labels: payed_not_payed_labels,
            };
            var chartApprovedPending = new ApexCharts(document.querySelector("#chart-approved-pending"), options_payed_not_payed);
            chartApprovedPending.render();
    
            var options_not_payed_expirated = {
                chart: {
                    type: 'pie',
                    width: 300,
                    height: 300
                },
                series: not_payed_expirated_totals,
                labels: not_payed_expirated_labels,
            };
            var chartPendingExpirated = new ApexCharts(document.querySelector("#chart-pending-expirated"), options_not_payed_expirated);
            chartPendingExpirated.render();

            var options_payed_expirated = {
                chart: {
                    type: 'pie',
                    width: 300,
                    height: 300
                },
                series: payed_expirated_totals,
                labels: payed_expirated_labels,
            };
            var chartApprovedExpirated = new ApexCharts(document.querySelector("#chart-approved-expirated"), options_payed_expirated);
            chartApprovedExpirated.render();
        </script>
    @endsection
</x-app-layout>
