<body class="vertical-layout subscriber-layout vertical-menu-modern {{ $configData['showMenu'] === true ? '2-columns' : '1-column' }}
{{ $configData['blankPageClass'] }} {{ $configData['bodyClass'] }}
{{ $configData['verticalMenuNavbarType'] }}
{{ $configData['sidebarClass'] }} {{ $configData['footerType'] }}" data-menu="vertical-menu-modern" data-col="{{ $configData['showMenu'] === true ? '2-columns' : '1-column' }}" data-layout="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}" style="{{ $configData['bodyStyle'] }}" data-framework="laravel" data-asset-path="{{ asset('/')}}">

  {{-- Include Sidebar --}}
  @if((isset($configData['showMenu']) && $configData['showMenu'] === true))
  @include('panels.sidebar')
  @endif

  {{-- @Amandeep - Include Navbar --}}
  @include('panels/subscriber_dashboard/navbar')

  <!-- BEGIN: Content-->
  <div class="app-content content {{ $configData['pageClass'] }}">
    <!-- BEGIN: Header-->
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>

    @if(($configData['contentLayout']!=='default') && isset($configData['contentLayout']))
    <div class="content-area-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container p-0' : '' }}">
      <div class="{{ $configData['sidebarPositionClass'] }}">
        <div class="sidebar">
          {{-- Include Sidebar Content --}}
          @yield('content-sidebar')
        </div>
      </div>
      <div class="{{ $configData['contentsidebarClass'] }}">
        <div class="content-wrapper">
          <div class="content-body">
            {{-- Include Page Content --}}
            @yield('content')
          </div>
        </div>
      </div>
    </div>
    @else
    <div class="content-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container p-0' : '' }}">
      {{-- Include Breadcrumb --}}
      @if($configData['pageHeader'] === true && isset($configData['pageHeader']))
        @include('panels.breadcrumb')
      @endif

      <div class="content-body">
        {{-- @Amandeep - Include Page Content --}}
		@yield('content')
      </div>
    </div>
    @endif

  </div>
  <!-- End: Content-->

  @include('inc.modal.side')

  <div class="sidenav-overlay"></div>
  <div class="drag-target"></div>

  {{-- @Amandeep - include footer --}}
  @include('panels/subscriber_dashboard/footer')

  {{-- include default scripts --}}
  @include('panels/scripts')

  @if(auth()->check())
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
      <input type="hidden" id="app_role" value="{{ auth()->user()->hasRole('super_admin') ? 'super_admin' : 'admin' }}">
    @endif
  @endif
  <script type="text/javascript">
    $(window).on('load', function() {
      if (feather) {
        feather.replace({
          width: 14
          , height: 14
        });
      }
    })

  </script>
  <script>
	jQuery(window).scroll(function(){
		if (jQuery(this).scrollTop() > 250) {
		   jQuery('#masthead').addClass('scroll-head');
		} else {
		   jQuery('#masthead').removeClass('scroll-head');
		}
	});
	</script>

</body>

</html>
