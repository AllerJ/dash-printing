<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title') - Dash Printing Services</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    <link rel="manifest" href="/manifest.json" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="application-name" content="Print Pricer" />
    <meta name="apple-mobile-web-app-title" content="Print Pricer" />
    <meta name="msapplication-starturl" content="/admin" />
    <link rel="apple-touch-icon" href="/icon-384x384.png">
    
    
    <link href="https://printing.dashcg.com/iphone12.jpg" rel="apple-touch-startup-image" />
    
    
    <link rel="icon" type="image/png" sizes="32x32" href="https://printing.dashcg.com/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://printing.dashcg.com/favicon-72x72.png">


    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

    <link href="{{ route('lit.css') }}" rel="stylesheet" />

    @foreach(lit_app()->getStyles() as $path)
        <link href="{{ $path }}{{ asset_time() }}" rel="stylesheet">
    @endforeach

    @yield('pageStyle')
    
    @include('litstack::partials.google_analytics') 

    @if(Lit::usesLivewire())
        <livewire:styles />
    @endif

    <script>
    window.Lit = {
        bootingCallbacks: [],
        booting: function(cb) {
            this.bootingCallbacks.push(cb);
        },
    }
    </script>

</head>

<body style="overflow-x: hidden;">
    @php
        lit()->config('navigation')->topbar;
        lit()->config('navigation')->main;
    @endphp
    <div id="litstack">

        @include('litstack::partials.topbar.topbar') 
        @include('litstack::partials.navigation') 
        <b-button variant="primary" class="d-block d-lg-none btn-square lit-main-navigation-toggle">
            <lit-fa-icon icon="stream" />
        </b-button>

        <main>
            <div class="lit-content">
                @yield('content')  
            </div>

            @include('litstack::partials.spinner') 
        </main>

    </div>

    <!-- lit translations -->
    <script src="{{ lit()->route('lit-translations') }}"></script>

    @foreach(lit_app()->getScripts() as $src)

        <script src="{{ $src }}"></script>
    @endforeach

    <!-- lit app js -->
    <script src="{{ lit_js() }}" defer></script>

    @if(Lit::usesLivewire())
        <livewire:scripts />
        <script>
        document.addEventListener("livewire:load", () => {
            let hook = 'element.updating'
            if (!window.livewire.components.hooks.availableHooks.includes(hook)) {
                hook = 'beforeDomUpdate'
            }
            window.livewire.components.hooks.register(hook, function(component, dom) {
                dom.html = new Vue({
                        template: dom.html
                    })
                    .$mount()
                    .$el.outerHTML;
            });
        });
        </script>
    @endif

</body>

</html>