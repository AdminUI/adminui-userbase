@if ($title)
    <h4 class="font-weight-bold text-uppercase mb-2">{{ $title }}</h4>
@endif

<ul class="{{ $class ?? '' }} account-menu">
    @auth
        <li><a href="/user/account/details">Account Details</a></li>
        <li><a href="/user/account/addresses" class="underline">My Addresses</a></li>
        <li><a href="/user/account" class="underline">My Notifications</a></li>
        <li><a href="/user/account/wishlist" class="underline">Wishlist</a></li>
        <li><a href="/user/account/history" class="underline">Order History</a></li>
        <li><a href="/user/account/returns" class="underline">Returns</a></li>
        <li><a href="/user/account/returns" class="underline">Returns</a></li>
        <li><a href="/user/account/logout" class="underline">Logout</a></li>
    @else
        <li><a href="javascript:void(0)" v-on:click="launchLogin" class="underline">Login</a></li>
        <li><a href="user/account/register" class="underline">Register New Account</a></li>
    @endauth
</ul>
