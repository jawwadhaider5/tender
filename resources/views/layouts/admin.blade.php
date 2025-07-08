<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">

        <title>{{ config('app.name', 'Tender') }}</title>

          <!-- Scripts -->
        <!-- <script src="{{ asset('js/app.0aec786d.js') }}" defer></script> -->

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">


         <!-- <link href="{{ asset('build/assets/app.5380b351.css') }}" rel="stylesheet"> -->
         <!-- <link href="{{ asset('build/assets/app-5380b351.css') }}" rel="stylesheet"> -->


        @include('layouts.partials.css')
        @yield('css')

    </head>
    <body class="font-sans antialiased">
    <section class="print_section" id="mybill"></section>
        <div class="min-h-screen bg-gray-100 no-print">
            <div class="container-fluid page-body-wrapper ">
                <div id="app" style="background: rgb(90, 88, 88);">
                @include('layouts.partials.header')
                @include('layouts.partials.sidebar')
                </div>
                @yield('content')
             </div>
                @include('layouts.partials.footer')
        </div>

        <div class="modal fade" id="view_register_modal" role="dialog"
           aria-labelledby="gridSystemModalLabel">
          </div>

          <div class="modal fade" id="notification_modal" tabindex="-1" role="dialog"
          aria-labelledby="gridSystemModalLabel">
         </div>



    @include('layouts.partials.javascript')

    @yield('javascript')

    <script src="{{ asset('js/main.js') }}"></script>

    <script>

$(document).on('click', 'a.view-notification', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            success: function(result) {
                $('#notification_modal').html(result).modal('show');


            }
        });

    });
    </script>


    </body>
</html>














