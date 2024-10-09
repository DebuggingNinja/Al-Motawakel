@include('partials._header')

<body class="layout-light side-menu">
  <div class="mobile-search">
    <form action="/" class="search-form">
      <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg">
      <input class="form-control me-sm-2 box-shadow-none" type="search" placeholder="Search..." aria-label="Search">
    </form>
  </div>
  <div class="mobile-author-actions"></div>
  <header class="header-top">
    @include('partials._top_nav')
  </header>
  <main class="main-content">
    <div class="sidebar-wrapper">
      <aside class="sidebar sidebar-collapse" id="sidebar">
        @include('partials._menu')
      </aside>
    </div>
    <div class="contents">
      @yield('content')
    </div>
    <footer class="footer-wrapper">
      @include('partials._footer')
    </footer>
  </main>
  <div id="overlayer">
    <span class="loader-overlay">
      <div class="dm-spin-dots spin-lg">
        <span class="spin-dot badge-dot dot-primary"></span>
        <span class="spin-dot badge-dot dot-primary"></span>
        <span class="spin-dot badge-dot dot-primary"></span>
        <span class="spin-dot badge-dot dot-primary"></span>
      </div>
    </span>
  </div>
  <div class="overlay-dark-sidebar"></div>
  <div class="customizer-overlay"></div>
  <div class="customizer-wrapper">
  </div>

  <script>
    var env = {
            iconLoaderUrl: "{{ asset('assets/js/json/icons.json') }}",
            googleMarkerUrl: "{{ asset('assets/img/markar-icon.png') }}",
            editorIconUrl: "{{ asset('assets/img/ui/icons.svg') }}",
            mapClockIcon: "{{ asset('assets/img/svg/clock-ticket1.sv') }}g"
        }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDduF2tLXicDEPDMAtC6-NLOekX0A5vlnY"></script>
  <script src="{{ asset('assets/js/plugins.min.js') }}"></script>
  <script src="{{ asset('assets/js/script.min.js') }}"></script>
  <script src="{{ asset('js/print.min.js') }}"></script>
  <!-- toast v2.1.3 -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/app.min.js') }}"></script>
  @yield('scripts')
  @if(session('success'))
    <script>
      toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "swing",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      };
      toastr["success"](`{{session('success')}}`);
    </script>
  @endif
  @if(session('error'))
    <script>
      toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "swing",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      };
      toastr["error"](`{{session('error')}}`);
    </script>
  @endif
  @if($errors->any())
    <script>
      toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "swing",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      };
      toastr["error"](`{{ implode(', ', $errors->all()) }}`);
    </script>
  @endif
</body>

</html>
