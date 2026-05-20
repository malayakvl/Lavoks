{{-- Header React Component Mount Point --}}
<div id="header-root"></div>

{{-- Pass data to React via window object --}}
<script>
    window.headerProps = {
        categories: @json($categories ?? []),
        auth: {
            user: @json(auth()->user()),
            canRegister: @json(Route::has('register')),
            loginUrl: @json(route('login')),
            registerUrl: @json(Route::has('register') ? route('register') : null),
            dashboardUrl: @json(url('/dashboard')),
            homeUrl: @json(route('home')),
            catalogUrl: @json(route('catalog')),
            cartUrl: @json(route('cart'))
        },
        routes: {
            catalogCategory: '{{ route("catalog.category", ["slug" => "__slug__"]) }}'
        }
    };
</script>
