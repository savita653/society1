@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
{{-- {!! Helper::applClasses() !!} --}}
@php
$configData = Helper::applClasses();
@endphp

<html lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif" data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}" class="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">


  <title>@yield('title')</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/logo/cropped-logo-head.png')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
  <a href="{{ route('home') }}" class="navbar-brand"><img src="{{ asset('images/logo/main-logo.jpg') }}" alt="{{ config('app.url') }}" class="head-logo" /></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse head-menu" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Dashboard <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Events</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="#">Video</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
      </li>
	  </ul>
	</div>
	
	
	<div class="collapse navbar-collapse head-profile" id="navbarSupportedContent">
	  <div class="new-head-right">
		  <ul class="navbar-nav mr-auto">
		  <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  <img src="{{ config('setting.wp_url') }}app/public/images/app/man-img.png" alt="admin-img" class="admin-img">
			  Netz Admin
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			  <a class="dropdown-item" href="#">Account</a>
			  <div class="dropdown-divider"></div>
			  <a class="dropdown-item" href="#">Logout</a>
			</div>
		  </li>
		</ul>
		<form class="form-inline my-2 my-lg-0">
		  <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
		  <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><img src="{{ config('setting.wp_url') }}app/public/images/app/search-icon.png" alt="serch-icon" class="srch-icon" /></button>
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
        {{-- @Amandeep - Include Page Content --}}
		<div class="event-main">
			<div class="section-inner">
				<div class="home-event-sec">
					<div class="event-head-main">
						<h2>Upcoming Presentations</h2>
						<ul class="arrow-icons-list">
							<li><a href="#" class="left-icon"></a></li>
							<li><a href="#" class="right-icon"></i></a></li>
						</ul>
						<div class="subject-main">
						<ul class="subject-list">
							<li><span>All Subjects</span>
							<ul class="sub-sujbect-list">
							<li>Subject 1</li>
							<li>Subject 2</li>
							<li>Subject 3</li>
							<li>Subject 4</li>
							<li>Subject 5</li>
							</ul>
							</li>
						</ul>
						<input type="submit" value="search" />
						</div>
					</div>
					<div class="event-content-sec">
						<div class="container">
						  <div class="row">
							<div class="col-md-4 col-sm-12">
								<div class="event-new-list">
								<div class="event-img-sec">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/event-1.jpg" class="events-img" alt="event-1"/>
									<span class="time"><img src="{{ config('setting.wp_url') }}app/public/images/app/timer-icon.png"/>07:30 PM</span>
								</div>
								<div class="event-head-sec">
									<h3>Upcoming Events Heading 1, Could Have Two Lines</h3>
									<h4>05<span>FRI March</span></h4>
								</div>
							</div>
							</div>
							<div class="col-md-4 col-sm-12">
							<div class="event-new-list">
							  <div class="event-img-sec">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/event-3.jpg" class="events-img" alt="event-2"/>
									<span class="time"><img src="{{ config('setting.wp_url') }}app/public/images/app/timer-icon.png"/>08:00 PM</span>
								</div>
								<div class="event-head-sec">
									<h3>Upcoming Events Heading 2, Could Have Two Lines</h3>
									<h4>10<span>WED March</span></h4>
								</div>
							</div>
							</div>
							<div class="col-md-4 col-sm-12">
							<div class="event-new-list">
								<div class="event-img-sec">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/event-2.jpg" class="events-img" alt="event-3"/>
									<span class="time"><img src="{{ config('setting.wp_url') }}app/public/images/app/timer-icon.png"/>08:00 PM</span>
								</div>
								<div class="event-head-sec">
									<h3>Upcoming Events Heading 3, Could Have Two Lines</h3>
									<h4>12<span>FRI March</span></h4>
								</div>
							</div>
							</div>
						  </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="feat-subcibe-main">
			<div class="section-inner">
				<div class="home-event-sec feat-video-sec">
					<div class="event-head-main feat-video-main">
						<h2>Recent Videos</h2>
						<ul class="arrow-icons-list">
							<li><a href="#" class="left-icon"></a></li>
							<li><a href="#" class="right-icon"></i></a></li>
						</ul>
						<div class="subject-main">
							<ul class="subject-list">
								<li><span>All Subjects</span>
								<ul class="sub-sujbect-list">
								<li>Subject 1</li>
								<li>Subject 2</li>
								<li>Subject 3</li>
								<li>Subject 4</li>
								<li>Subject 5</li>
								</ul>
								</li>
							</ul>
							<input type="submit" value="search" />
						</div>
					</div>
					<div class="event-content-sec feat-video-content-sec">
						<div class="feat-video-list one">				
						<div class="container">
							<div class="row">
								<div class="col-md-3 col-sm-6">
								<div class="video-new-list">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/vid-1.jpg" class="featured-video-img" alt="vid-1"/>
									<h3>Video Heading 1</h3>
									<p>Lorem ipsum dolor sit amet.</p>
									<h4>Posted <span>March 05</span></h4>
								</div>
								</div>
								
								<div class="col-md-3 col-sm-6">
								<div class="video-new-list">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/vid-2.jpg" class="featured-video-img" alt="vid-2"/>
									<h3>Video Heading 2</h3>
									<p>Lorem ipsum dolor sit amet.</p>
									<h4>Posted <span>March 10</span></h4>
								</div>
								</div>
								
								<div class="col-md-3 col-sm-6">
								<div class="video-new-list">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/vid-3.jpg" class="featured-video-img" alt="vid-3"/>
									<h3>Video Heading 3</h3>
									<p>Lorem ipsum dolor sit amet.</p>
									<h4>Posted <span>March 15</span></h4>
								</div>
								</div>
								
								<div class="col-md-3 col-sm-6">
								<div class="video-new-list">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/vid-4.jpg" class="featured-video-img" alt="vid-4"/>
									<h3>Video Heading 4</h3>
									<p>Lorem ipsum dolor sit amet.</p>
									<h4>Posted <span>March 20</span></h4>
								</div>
								</div>
								
							</div>
						</div>
						</div>
						<div class="feat-video-list two">
						<div class="container">
							<div class="row">
								<div class="col-md-3 col-sm-6">
								<div class="video-new-list">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/vid-5.jpg" class="featured-video-img" alt="vid-5"/>
									<h3>Video Heading 5</h3>
									<p>Lorem ipsum dolor sit amet.</p>
									<h4>Posted <span>March 05</span></h4>
								</div>
								</div>
								
								<div class="col-md-3 col-sm-6">
								<div class="video-new-list">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/vid-6.jpg" class="featured-video-img" alt="vid-6"/>
									<h3>Video Heading 6</h3>
									<p>Lorem ipsum dolor sit amet.</p>
									<h4>Posted <span>March 10</span></h4>
								</div>
								</div>
								
								<div class="col-md-3 col-sm-6">
								<div class="video-new-list">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/vid-7.jpg" class="featured-video-img" alt="vid-7"/>
									<h3>Video Heading 7</h3>
									<p>Lorem ipsum dolor sit amet.</p>
									<h4>Posted <span>March 15</span></h4>
								</div>
								</div>
								
								<div class="col-md-3 col-sm-6">
								<div class="video-new-list">
									<img src="{{ config('setting.wp_url') }}app/public/images/app/vid-8.jpg" class="featured-video-img" alt="vid-8"/>
									<h3>Video Heading 8</h3>
									<p>Lorem ipsum dolor sit amet.</p>
									<h4>Posted <span>March 20</span></h4>
								</div>
								</div>
								
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
      </div>
    </div>
    @endif

  </div>
  <!-- End: Content-->

  @include('inc.modal.side')

  <div class="sidenav-overlay"></div>
  <div class="drag-target"></div>

  {{-- @Amandeep - include footer --}}
  <footer id="colophon" class="site-footer">
		<div class="section-inner">
			<div class="footer-list-main">
				<div class="row">
				<div class="col-md-4 col-sm-12 footer-list one">
					<a href="http://netzworkforce.com/n10/researcherslive" class="footer-logo"><img src="{{ asset('images/logo/logo_png_white.png') }}" alt="researcherslive-logo" class="head-logo" /></a>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
				</div>
				<div class="col-md-2 col-sm-6 footer-list two">
					<h3 class="foot-heading">Links</h3>
					<nav>
						<ul class="nav flex-column foot-menu-list">
						  <li class="nav-item">
							<a class="nav-link active" href="#">Dashboard</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#">Events</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#">Video</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#">Contact</a>
						  </li>
						</ul>
					</nav>
				</div>
				<div class="col-md-2 col-sm-6 footer-list three">
					<h3 class="foot-heading">Quick Links</h3>
					<nav>
						<ul class="nav flex-column quick-link-list">
						  <li class="nav-item">
							<a class="nav-link" href="#">Promotional Link 1</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#">Feautred Link 2</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#">Upcoming Link 3</a>
						  </li>
						  <li class="nav-item">
							<a class="nav-link" href="#">Event Link 4</a>
						  </li>
						</ul>
					</nav>
				</div>
				<div class="col-md-4 col-sm-12 footer-list four">
					<h3 class="foot-heading">Sign Up to Researchers LIVE News and Events</h3>
					<form class="newsletter-form">
						<input type="email" name="EMAIL" placeholder="Email Address">
						<input type="submit" value="" class="news-btn">
					</form>
				</div> 
				</div>
			</div>
			</div>
			<div class="copyright-main">
				<div class="section-inner">
					<div class="copyright-content">
						<p class="copyright-text">Copyright © 2021 ResearchersLIVE.</p>

						<ul class="foot-social-list">
							<li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
							<li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						</ul>

						<ul class="copy-links">
							<li><a href="/n10/researcherslive/terms-conditions/">Terms & Conditions</a></li>
						<li><a href="/n10/researcherslive/privacy-policy/">Privacy Policy</a></li>
						</ul>
					</div>
				</div>
			</div>
	</footer><!-- #colophon -->

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
