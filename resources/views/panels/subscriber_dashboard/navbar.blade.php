<nav id="masthead" class="navbar navbar-expand-lg navbar-light" style="background-color: #fff;">
    <a href="{{ route('home') }}" class="navbar-brand"><img src="{{ asset('images/logo/Logo_black_nw.png') }}"
            alt="{{ config('app.url') }}" class="head-logo" /></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse head-menu" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                @if(
                    (
                        auth()->user()->hasRole('subscriber') &&
                        auth()->user()->hasRole('presenter')
                    ) ||
                        auth()->user()->hasRole('subscriber') || 
                        auth()->user()->hasRole('super_admin') ||
                        auth()->user()->hasRole('admin')
                    )
                
                    <li class="nav-item">
                        <a class="nav-link" href="{{ config('setting.about_url') }}">About</a>
                    </li>
                    <li class="nav-item {{ Route::currentRouteName() === 'subscriber.events.index' ? 'active' : '' }}">
                        <a class="nav-link {{ Route::currentRouteName() === 'subscriber.events.index' ? 'active' : '' }}" href="{{ route('subscriber.events.index') }}">Events</a>
                    </li>
                    <li class="nav-item {{ Route::currentRouteName() === 'subscriber.videos.index' ? 'active' : '' }}">
                        <a class="nav-link {{ Route::currentRouteName() === 'subscriber.videos.index' ? 'active' : '' }}" href="{{ route('subscriber.videos.index') }}">Videos</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="{{ config('setting.faq_url') }}">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ config('setting.contact_url') }}">Contact</a>
                    </li>
                @elseif( auth()->user()->hasRole('presenter') )
                    
                    <li class="nav-item {{ Route::currentRouteName() === 'presenter.events.index' ? 'active' : '' }}">
                        <a class="nav-link {{ Route::currentRouteName() === 'presenter.events.index' ? 'active' : '' }}" href="{{ route('presenter.events.index') }}">My Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ config('setting.about_url') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ config('setting.faq_url') }}">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ config('setting.contact_url') }}">Contact</a>
                    </li>
                @endif
            
        </ul>
    </div>


    <div class="collapse navbar-collapse head-profile" id="navbarSupportedContent">
        <div class="new-head-right">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ auth()->user()->profileImage() }}" alt="{{ auth()->user()->fullName() }}"
                            class="admin-img rounded-circle">
                        {{ auth()->user()->fullName() }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item {{ Route::currentRouteName() }} {{ Route::currentRouteName() === 'account' ? 'active' : '' }}"
                            href="{{ route('account') }}">
                            <i class="mr-50" data-feather="user"></i> My Account
                        </a>
                        @if(!auth()->user()->hasRole('super_admin') && !auth()->user()->hasRole('admin'))
                            {{-- Presenter Links --}}
                            @if (auth()->user()->hasRole('presenter'))
                                <a class="dropdown-item {{ Route::currentRouteName() }} {{ Route::currentRouteName() === 'presenter.events.index' ? 'active' : '' }}"
                                    href="{{ route('presenter.events.index') }}">
                                    <i class="mr-50" data-feather="home"></i> Presenter Dashboard
                                </a>
                            @else
                                <a class="dropdown-item " href="{{ route('apply.presenter') }}">
                                    <i class="mr-50" data-feather="airplay"></i> Interested in Presenting?
                                </a>
                            @endif

                            {{-- Subscriber Link --}}
                            @if (!auth()->user()->hasRole('subscriber'))
                                <a class="dropdown-item " href="{{ route('subscriber.setup') }}">
                                    <i class="mr-50" data-feather="user"></i> Become a Subscriber
                                </a>
                            @endif
                        @endif

                        @if(auth()->user()->hasRole('super_admin'))
                            <a class="dropdown-item " href="{{ route('super-admin.home') }}">
                                <i class="mr-50" data-feather="home"></i> Admin Dashboard
                            </a>
                        @endif
                        @if(auth()->user()->hasRole('admin'))
                            <a class="dropdown-item " href="{{ route('events.index') }}">
                                <i class="mr-50" data-feather="home"></i> Admin Dashboard
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="dropdown-item" href="{{ url('auth/login-v2') }}">
                            <i class="mr-50" data-feather="power"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
            <form action="{{  route('subscriber.events.index') }}" class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0 cursor-pointer" type="submit">
                    <img src="{{ asset('images/app/search-icon.png') }}" alt="serch-icon"
                        class="srch-icon" />
                </button>
            </form>
        </div>
    </div>
</nav>
