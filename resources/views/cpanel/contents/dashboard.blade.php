@extends('cpanel.layouts.main')

@section('css')

@endsection

@section('content')

@hasrole('admin')
<div class="card-group">
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">{{ \App\Models\Institution::all()->count() }}</h2>
                        <!-- <span class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">+18.33%</span> -->
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Perguruan Tinggi</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="briefcase"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ \App\Models\Activity::all()->count() }}</h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Kegiatan
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="activity"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">{{ \App\Models\Survey::all()->count() }}</h2>
                        <!-- <span class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">-18.33%</span> -->
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Survey</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="file-text"></i></span>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="card">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <h2 class="text-dark mb-1 font-weight-medium">864</h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Jumlah Peserta</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="globe"></i></span>
                </div>
            </div>
        </div>
    </div> -->
</div>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Jenis Kegiatan</h4>
                <div id="campaign-v2" class="mt-2" style="height:283px; width:100%;"></div>
                <ul class="list-style-none mb-0">
                    <li>
                        <i class="fas fa-circle text-primary font-10 mr-2"></i>
                        <span class="text-muted">Program MBKM dan Hibah Lainnya</span>
                        <span class="text-dark float-right font-weight-medium">{{ \App\Models\Activity::where('activity_type', 1)->count() }}</span>
                    </li>
                    <li class="mt-3">
                        <i class="fas fa-circle text-danger font-10 mr-2"></i>
                        <span class="text-muted">Program Puspresnas/Program Diktiristek</span>
                        <span class="text-dark float-right font-weight-medium">{{ \App\Models\Activity::where('activity_type', 2)->count() }}</span>
                    </li>
                    <li class="mt-3">
                        <i class="fas fa-circle text-cyan font-10 mr-2"></i>
                        <span class="text-muted">Program Mandiri</span>
                        <span class="text-dark float-right font-weight-medium">{{ \App\Models\Activity::where('activity_type', 3)->count() }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Perguruan Tinggi Terdaftar</h4>
                <div class="net-income mt-4 position-relative" style="height:294px;"></div>
                <ul class="list-inline text-center mt-5 mb-2">
                    <li class="list-inline-item text-muted font-italic">Perguruan Tinggi Per Bulan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endhasrole

@hasrole('user')

@endhasrole

@endsection

@section('javascript')
    <script src="{{ asset('admin/assets/extra-libs/c3/d3.min.js') }}"></script>
    <script src="{{ asset('admin/assets/extra-libs/c3/c3.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('admin/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- <script src="{{ asset('admin/dist/js/pages/dashboards/dashboard1.min.js') }}"></script> -->
    <script>
        $(function () {
            // ==============================================================
            // Campaign
            // ==============================================================

            var chart1 = c3.generate({
                bindto: '#campaign-v2',
                data: {
                    columns: [
                        ['Program MBKM dan Hibah Lainnya', {{ \App\Models\Activity::where('activity_type', 1)->count() }}],
                        ['Program Puspresnas/Program Diktiristek', {{ \App\Models\Activity::where('activity_type', 2)->count() }}],
                        ['Program Mandiri', {{ \App\Models\Activity::where('activity_type', 3)->count() }}]
                    ],

                    type: 'donut',
                    tooltip: {
                        show: true
                    }
                },
                donut: {
                    label: {
                        show: false
                    },
                    title: 'Kegiatan',
                    width: 18
                },

                legend: {
                    hide: true
                },
                color: {
                    pattern: [
                        '#edf2f6',
                        '#5f76e8',
                        '#ff4f70'
                    ]
                }
            });

            d3.select('#campaign-v2 .c3-chart-arcs-title').style('font-family', 'Rubik');

            // ============================================================== 
            // grafik perguruan tinggi
            // ============================================================== 
            var data = {
                labels: <?= json_encode($arrMonthInst) ?>,
                series: [
                    <?= json_encode($arrCountInst) ?>
                ]
            };

            var options = {
                axisX: {
                    showGrid: false
                },
                seriesBarDistance: 1,
                chartPadding: {
                    top: 15,
                    right: 15,
                    bottom: 5,
                    left: 0
                },
                plugins: [
                    Chartist.plugins.tooltip()
                ],
                width: '100%'
            };

            var responsiveOptions = [
                ['screen and (max-width: 640px)', {
                    seriesBarDistance: 5,
                    axisX: {
                        labelInterpolationFnc: function (value) {
                            return value[0];
                        }
                    }
                }]
            ];
            new Chartist.Bar('.net-income', data, options, responsiveOptions);
        })
    </script>
@endsection