@php
$routes = [
    'login' => route('user.login'),
    'logout' => route('user.api.logout'),
    'register' => route('user.register'),
    'passwordReset' => route('password-reset'),
    'passwordChange' => route('password.change'),
    'address' => route('user.api.address'),
    'addresses' => route('user.api.addresses', [
        'account' => '##account##',
    ]),
    'accounts' => route('user.api.accounts'),
    'notifications' => route('user.api.notifications'),
    'reviews' => route('user.api.reviews'),
    'review' => route('user.api.review'),
    'returns' => route('user.api.returns'),
];
@endphp


{{-- Render the vue js mini app --}}
<x-ecom.layout.main>
    <vue-user-account-app :routes=@json($routes) site-name="{{ config('app.name') }}"
        reviews-enabled="{{ config('settings.has_reviews') }}" primary="var(--primary)" secondary="var(--secondary)"
        class="dynamic-container">
        <div class="dynamic-container" style="height: min(450px, 50vh)">
            <div class="row no-gutters h-100">
                <div class="col-3 pr-2">
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line"></div>
                </div>
                <div class="col-9 flex-centre">
                    <h1>Loading...</h1>
                </div>
            </div>
        </div>
        <template #register.header>
            <vue-message severity="info" :closable="false">
                If you wish to have a Trade Account please apply via the
                <a href="/trade-accounts" class="underline">Trade Accounts</a> page
            </vue-message>
        </template>
    </vue-user-account-app>
</x-ecom.layout.main>
