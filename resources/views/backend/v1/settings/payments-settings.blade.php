@extends('backend.master')

@section('title', 'Payment Settings')

@section('content')

    <div class="container py-5">
        <h3 class="mb-4">Payment Settings</h3>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="stripe-tab" data-bs-toggle="tab" data-bs-target="#stripe" type="button"
                    role="tab" data-uri="{{ route('settings.payments.stripe.update') }}">Stripe</button>
            </li>
            {{-- <li class="nav-item">
            <button class="nav-link" id="mail-tab" data-bs-toggle="tab"
                    data-bs-target="#mail" type="button" role="tab"
                    data-uri="{{ route('settings.payments.stripe.test') }}">SSL COMMERZ</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="other-tab" data-bs-toggle="tab"
                    data-bs-target="#other" type="button" role="tab"
                    data-uri="{{ route('settings.payments.stripe.test') }}">Other</button>
        </li> --}}
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content mt-4" id="settingsTabsContent">
            <!-- Stripe Tab -->
            <div class="tab-pane fade show active" id="stripe" role="tabpanel">
                <form class="ajax-form">
                    {{-- STRIPE_KEY --}}
                    <div class="mb-3">
                        <label class="form-label">Stripe Key</label>
                        <input type="text" name="stripe_key" class="form-control" value="{{ env('STRIPE_KEY') }}"
                            placeholder="Enter Stripe Key">
                    </div>
                    {{-- STRIPE_SECRET --}}
                    <div class="mb-3">
                        <label class="form-label">Stripe Secret</label>
                        <input type="text" name="stripe_secret" class="form-control" value="{{ env('STRIPE_SECRET') }}"
                            placeholder="Enter Stripe Secret">
                    </div>
                    {{-- STRIPE_WEBHOOK_SECRET --}}
                    <div class="mb-3">
                        <label class="form-label">Stripe WEBHOOK Secret</label>
                        <input type="text" name="stripe_webhook_secret" class="form-control"
                            value="{{ env('STRIPE_WEBHOOK_SECRET') }}" placeholder="Enter Stripe Secret">
                    </div>
                    {{-- PAYMENT_SUCCESS_URL --}}
                    <div class="mb-3">
                        <label class="form-label">PAYMENT SUCCESS URL</label>
                        <input type="text" name="payment_success_url" class="form-control"
                            value="{{ env('PAYMENT_SUCCESS_URL') }}" placeholder="Enter Payment Success URL">
                    </div>
                    {{-- PAYMENT_CANCEL_URL --}}
                    <div class="mb-3">
                        <label class="form-label">PAYMENT CANCEL URL</label>
                        <input type="text" name="payment_cancel_url" class="form-control"
                            value="{{ env('PAYMENT_CANCEL_URL') }}" placeholder="Enter Payment Cancel URL">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Stripe</button>
                </form>
            </div>

            <!-- Mail Tab -->
            <div class="tab-pane fade" id="mail" role="tabpanel">
                <form class="ajax-form">
                    <div class="mb-3">
                        <label class="form-label">Mail Host</label>
                        <input type="text" name="mail_host" class="form-control" value="{{ env('MAIL_HOST') }}"
                            placeholder="Enter Mail Host">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mail Username</label>
                        <input type="text" name="mail_username" class="form-control" value="{{ env('MAIL_USERNAME') }}"
                            placeholder="Enter Mail Username">
                    </div>
                    <button type="submit" class="btn btn-success">Save Mail</button>
                </form>
            </div>

            <!-- Other Tab -->
            <div class="tab-pane fade" id="other" role="tabpanel">
                <form class="ajax-form">
                    <div class="mb-3">
                        <label class="form-label">App Name</label>
                        <input type="text" name="app_name" class="form-control" value="{{ config('app.name') }}"
                            placeholder="Enter App Name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">App URL</label>
                        <input type="text" name="app_url" class="form-control" value="{{ config('app.url') }}"
                            placeholder="Enter App URL">
                    </div>
                    <button type="submit" class="btn btn-warning">Save Other</button>
                </form>
            </div>
        </div>

        <!-- Response -->
        <div class="mt-5">
            <h6>Error Response (If show any error):</h6>
            <pre id="responseBox" class="bg-white border rounded p-3"></pre>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        $(function() {

            // ===== Remember Active Tab =====
            const activeTabKey = 'activeSettingsTab';

            // On tab change — save ID
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                localStorage.setItem(activeTabKey, $(e.target).attr('id'));
            });

            // On page load — restore last active tab
            const lastTab = localStorage.getItem(activeTabKey);
            if (lastTab) {
                const trigger = document.getElementById(lastTab);
                if (trigger) {
                    const tab = new bootstrap.Tab(trigger);
                    tab.show();
                }
            }

            // ===== AJAX Form Submission =====
            $('.ajax-form').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);
                const activeTab = $('#settingsTabs .nav-link.active');
                const $url = activeTab.data('uri');
                console.log($url, $form.serialize())
                //const csrf = $('meta[name="csrf-token"]').attr('content');
                const csrf = '{{ csrf_token() }}';

                $.ajax({
                    url: $url,
                    method: 'PUT',
                    data: $form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    success: function(res) {
                        $('#responseBox').text(JSON.stringify(res, null, 2));

                        // Reload the page but stay on same tab
                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    },
                    error: function(xhr) {
                        console.log('failed ')
                        $('#responseBox').text('Error: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush


