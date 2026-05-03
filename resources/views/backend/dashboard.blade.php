@extends('backend.master')

@section('title', 'Dashboard | Dashboard Overview')

@push('styles')
@endpush

@section('content')
    <!-- Begin page -->


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="grow">
                                <h4 class="fs-16 mb-1">🌞 Have a nice day, 🎉 {{ Auth::user()->name }}!!</h4>
                                <p class="text-muted mb-0">💫What's your plan today !!</p>
                            </div>
                        </div>
                        <!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="grow overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Users
                                        </p>
                                    </div>
                                    <div class="shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i
                                                class="ri-arrow-right-up-line fs-13 align-middle"></i>{{ $total_users ?? '' }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">Users :
                                            {{ $total_users ?? '' }}
                                        </h4>
                                        <a href="{{ route('system-user.index') }}"
                                            class="text-decoration-underline">View Total User Info</a>
                                    </div>
                                    <div class="avatar-sm shrink-0">
                                        <span class="avatar-title bg-soft-success rounded fs-3">
                                            <i class="bx bx-user text-success"></i>

                                            {{--
                                           //it's for payment icon
                                           <i class="bx bx-dollar-circle text-success"></i> --}}

                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="grow overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Language
                                        </p>
                                    </div>
                                    <div class="shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            {{ $total_language ?? '' }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">Total Language
                                            {{ $total_language ?? '' }}
                                        </h4>
                                        <a href="{{ route('language.index') }}" class="text-decoration-underline">View Total Language
                                        </a>
                                    </div>
                                    <div class="avatar-sm shrink-0">
                                        <span class="avatar-title bg-soft-success rounded fs-3">
                                            <i class="bx bx-book-open text-success"></i>

                                            {{--
                                           //it's for payment icon
                                           <i class="bx bx-dollar-circle text-success"></i> --}}

                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="grow overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total
                                        </p>
                                    </div>
                                    <div class="shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            {{-- {{ $total_chapter ?? '' }} --}}
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">Total :
                                            {{-- {{ $total_chapter ?? '' }} --}}
                                        </h4>
                                        <a href="#" class="text-decoration-underline">View Total
                                        </a>
                                    </div>
                                    <div class="avatar-sm shrink-0">
                                        <span class="avatar-title bg-soft-success rounded fs-3">
                                            <i class="bx bx-book-content text-success"></i>

                                            {{--
                                           //it's for payment icon
                                           <i class="bx bx-dollar-circle text-success"></i> --}}

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="grow overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total
                                        </p>
                                    </div>
                                    <div class="shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            {{-- {{ $total_books ?? '' }} --}}
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">Total  :
                                            {{-- {{ $total_books ?? '' }} --}}
                                        </h4>
                                        <a href="#" class="text-decoration-underline">View Total
                                            Books</a>
                                    </div>
                                    <div class="avatar-sm shrink-0">
                                        <span class="avatar-title bg-soft-success rounded fs-3">
                                            <i class="ri-book-line text-success"></i>

                                            {{--
                                           //it's for payment icon
                                           <i class="bx bx-dollar-circle text-success"></i> --}}

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>

                <!-- end row-->
            </div>
            <!-- end .h-100-->

        </div>
        <!-- end col -->

    </div>

    <div class="row" style="margin-bottom: 100px;">
        <!-- Chart 1 -->
        <div class="col-lg-12 col-md-12 col-12 mb-4">
            <div class="card card-body mb-10">
                <div class="p-4 rounded-3">
                    <div class="chart-container" style="height: 400px;">
                        <canvas id="new-users-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 2 -->
        {{--  <div class="col-lg-4 col-md-12 col-12 mb-4">
            <div class="card card-body mb-10">
                <div style="background-color: white;" class="p-4 rounded-3">
                    <div class="chart-container" style="height: 400px;">
                        <canvas id="total-order-chart"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- Chart 3 -->
        {{-- <div class="col-lg-4 col-md-12 col-12 mb-4">
            <div class="card card-body mb-10">
                <div style="background-color: white;" class="p-4 rounded-3">
                    <div class="chart-container" style="height: 400px;">
                        <canvas id="total-payment-chart"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>


    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- chart for new users start --}}
    <script>
        // Get the current year dynamically
        const currentYear = new Date().getFullYear();

        // Data passed from the controller
        const labels = @json($chartData['labels']); // Will always have 12 months
        const data = @json($chartData['data']); // Will have counts, with 0 for months without users

        const ctx = document.getElementById('new-users-chart').getContext('2d');
        const newUserChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'This year\'s Users',
                    data: data,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(0, 123, 255, 0.5)',
                        'rgba(220, 53, 69, 0.5)',
                        'rgba(40, 167, 69, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(23, 162, 184, 0.5)',
                        'rgba(255, 193, 7, 0.5)',
                        'rgba(188, 80, 144, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132,0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(0, 123, 255, 0.5)',
                        'rgba(220, 53, 69, 0.5)',
                        'rgba(40, 167, 69, 0.5)',
                        'rgba(23, 162, 184, 0.5)',
                        'rgba(255, 193, 7, 0.5)',
                        'rgba(188, 80, 144, 0.5)'
                    ],
                    borderWidth: 1,
                    barThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // will be fixed height wise
                plugins: {
                    title: {
                        display: true,
                        text: `Total Users per Month (${currentYear})`
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    {{-- chart for new users end --}}
@endpush
