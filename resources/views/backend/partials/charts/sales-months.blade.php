{{-- <div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 grow">Revenue</h4>
                <div>
                    <button type="button" class="btn btn-soft-secondary btn-sm">
                        ALL
                    </button>
                    <button type="button" class="btn btn-soft-secondary btn-sm">
                        1M
                    </button>
                    <button type="button" class="btn btn-soft-secondary btn-sm">
                        6M
                    </button>
                    <button type="button" class="btn btn-soft-primary btn-sm">
                        1Y
                    </button>
                </div>
            </div><!-- end card header -->

            <div class="card-header p-0 border-0 bg-soft-light">
                <div class="row g-0 text-center">
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span></h5>
                            <p class="text-muted mb-0">Orders</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1">$<span class="counter-value" data-target="22.89">0</span>k</h5>
                            <p class="text-muted mb-0">Earnings</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="367">0</span></h5>
                            <p class="text-muted mb-0">Refunds</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                            <h5 class="mb-1 text-success"><span class="counter-value" data-target="18.92">0</span>%</h5>
                            <p class="text-muted mb-0">Conversation Ratio</p>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </div>
            <!-- end card header -->

            <div class="card-body p-0 pb-2">
                <div class="w-100">
                    <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-4">
        <!-- card -->
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 grow">Sales by Locations</h4>
                <div class="shrink-0">
                    <button type="button" class="btn btn-soft-primary btn-sm">
                        Export Report
                    </button>
                </div>
            </div>
            <!-- end card header -->

            <!-- card body -->

            <div class="card-body">

                <div id="sales-by-locations" data-colors='["--vz-light", "--vz-success", "--vz-primary"]' style="height: 269px" dir="ltr"></div>

                <div class="px-2 py-2 mt-1">
                    <p class="mb-1">Canada <span class="float-end">75%</span></p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="75"></div>
                    </div>

                    <p class="mt-3 mb-1">Greenland <span class="float-end">47%</span>
                    </p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="47"></div>
                    </div>

                    <p class="mt-3 mb-1">Russia <span class="float-end">82%</span></p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 82%" aria-valuenow="82" aria-valuemin="0" aria-valuemax="82"></div>
                    </div>
                </div>
            </div>

            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div> --}}

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 grow">Revenue</h4>
                <div>
                    <button type="button" class="btn btn-soft-secondary btn-sm chart-switcher" data-chart-type="area">
                        AREA
                    </button>
                    <button type="button" class="btn btn-soft-secondary btn-sm chart-switcher" data-chart-type="mixed">
                        MIXED
                    </button>
                    <div class="btn-group ms-2">
                        <button type="button" class="btn btn-soft-primary btn-sm time-filter" data-filter="all">
                            ALL
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm time-filter" data-filter="1M">
                            1M
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm time-filter" data-filter="6M">
                            6M
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm time-filter" data-filter="1Y">
                            1Y
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm time-filter" data-filter="custom" id="customFilterBtn">
                            CUSTOM
                        </button>
                    </div>
                </div>
            </div><!-- end card header -->

            <!-- Custom Date Range Modal -->
            <div class="modal fade" id="customDateModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Custom Date Range</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="startDate">
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="endDate">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="applyCustomDate">Apply</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-header p-0 border-0 bg-soft-light">
                <div class="row g-0 text-center">
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span></h5>
                            <p class="text-muted mb-0">Orders</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1">$<span class="counter-value" data-target="22.89">0</span>k</h5>
                            <p class="text-muted mb-0">Earnings</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="367">0</span></h5>
                            <p class="text-muted mb-0">Refunds</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                            <h5 class="mb-1 text-success"><span class="counter-value" data-target="18.92">0</span>%</h5>
                            <p class="text-muted mb-0">Conversation Ratio</p>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </div>
            <!-- end card header -->

            <div class="card-body p-0 pb-2">
                <div class="w-100">
                    <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-4">
        <!-- card -->
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 grow">Sales by Locations</h4>
                <div class="shrink-0">
                    <button type="button" class="btn btn-soft-primary btn-sm">
                        Export Report
                    </button>
                </div>
            </div>
            <!-- end card header -->
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div> <!-- end row -->

