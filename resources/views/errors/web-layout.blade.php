@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
{{-- {!! Helper::applClasses() !!} --}}
@php
$configData = Helper::applClasses();
$configData['showMenu'] = false;
@endphp

<html lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif" data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}" class="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="noindex" >
  <title>@yield('title')</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/logo/favicon.png')}}">
  {{-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet"> --}}

  {{-- Include core + vendor Styles --}}
  @include('panels/styles')
  @include('inc/variables')

</head>


<body class="vertical-layout subscriber-layout vertical-menu-modern {{ $configData['showMenu'] === true ? '2-columns' : '1-column' }}
{{ $configData['blankPageClass'] }} {{ $configData['bodyClass'] }}
{{ $configData['verticalMenuNavbarType'] }}
{{ $configData['sidebarClass'] }} {{ $configData['footerType'] }}" data-menu="vertical-menu-modern" data-col="{{ $configData['showMenu'] === true ? '2-columns' : '1-column' }}" data-layout="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}" style="{{ $configData['bodyStyle'] }}" data-framework="laravel" data-asset-path="{{ asset('/')}}">

  {{-- Include Sidebar --}}
  @if((isset($configData['showMenu']) && $configData['showMenu'] === true))
  @include('panels.sidebar')
  @endif

  {{-- @Amandeep - Include Navbar --}}
  <nav id="masthead" class="navbar navbar-expand-lg navbar-light" style="background-color: #fff;">
    <a href="{{ route('home') }}" class="navbar-brand"><img src="{{ asset('images/logo/rl_logo_black.png') }}"
            alt="{{ config('app.url') }}" class="head-logo" /></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse head-menu" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link href="{{ config('setting.wp_url') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link href="{{ config('setting.wp_url') }}">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link href="{{ route('subscriber.events.index') }}">Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Pricing</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ config('setting.contact_url') }}">FAQ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ config('setting.contact_url') }}">Contact</a>
            </li>
        </ul>
    </div>


    <div class="collapse navbar-collapse head-profile" id="navbarSupportedContent">
        <div class="new-head-right">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item mr-2">
                    <a href="{{ route('account') }}" class="nav-link">My Account</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><img
                        src="{{ asset('images/app/search-icon.png') }}" alt="serch-icon"
                        class="srch-icon" /></button>
            </form>
        </div>
    </div>
</nav>


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
          <div class="row align-items-center justify-content-center" style="height: 70vh;">
              <div class="col-12 text-center">
                  <h1 class="display-1 font-weight-bold text-dark">@yield('code', __('Oh no'))</h1>
                  <h2>
                      @yield('message')
                  </h2>
                  <p class="mt-2 ">
                      <a href="{{ app('router')->has('home') ? route('home') : url('/') }}">
                          <button class="btn btn-lg btn-primary">
                              {{ __('Go Home') }}
                          </button>
                      </a>

                  </p>
              </div>
              
          </div>
      </div>
    </div>
    @endif

  </div>
  <!-- End: Content-->

 

  <div class="sidenav-overlay"></div>
  <div class="drag-target"></div>

  {{-- @Amandeep - include footer --}}
  @include('panels/subscriber_dashboard/footer')

  {{-- include default scripts --}}
  @include('panels/scripts')

  
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
