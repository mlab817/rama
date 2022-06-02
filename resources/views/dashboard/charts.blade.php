<section>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Onboarding Timeline</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-center">
                                <strong>
                                    Onboarding: {{ now()->subYear()->format('Y M')  }} - {{ now()->format('Y M') }}</strong>
                            </p>

                            <div class="chart">
                                <canvas id="salesChart" style="height: 100vh; width: 100vw;" height="500" width="1262"></canvas>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <p class="text-center">
                                <strong>Top Five Regions</strong>
                            </p>

                            @foreach($regions->sortByDesc('vehicles_count')->slice(0, 5) as $region)
                                <div class="progress-group">
                                    <span class="progress-text">{{ $region->name }}</span>
                                    <span class="progress-number">{{ $region->vehicles_count }}</span>
                                    <div class="progress sm">
                                        <div class="progress-bar progress-bar-aqua" style="width: {{ $region->vehicles_count / $regions->max('vehicles_count') * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');

        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($merged->keys()),
                datasets: [{
                    label: '# of Onboarded Vehicles',
                    data: @json($merged->values()),
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
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</section>
