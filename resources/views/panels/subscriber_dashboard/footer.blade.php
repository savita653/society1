<footer id="colophon" class="site-footer">
    <div class="section-inner">
        <div class="footer-list-main">
            <div class="row">
            <div class="col-4 col-md footer-list one">
                <a href="{{ url('/') }}" class="footer-logo foot-log"><img src="{{ asset('images/logo/Logo_white_nw.png') }}" alt="{{ config('app.name') }}" class="head-logo foot-logo" /></a>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>
            <div class="col-2 col-md-2 footer-list two">
                <h3 class="foot-heading">Links</h3>
                <nav>
                    <ul class="nav flex-column foot-menu-list">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() === 'home' ? 'active' : '' }}" href="{{  route('home') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() === 'subscriber.events.index' ? 'active' : '' }}" href="{{ route('subscriber.events.index') }}">Events</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Video</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ config('setting.contact_url') }}">Contact</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-2 col-md-2 footer-list three">
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
            <div class="col-4 col-md footer-list four">
                <h3 class="foot-heading">Sign Up to {{ config('app.name') }} News and Events</h3>
                <form method="post" action="{{ route('subscribe-newsletter') }}" class="newsletter-form">
                    <input required type="email" name="email" placeholder="Email Address">
                    <span class="pt-1 text-white form-submission-status invisible d-block"></span>
                    <input type="submit" value="" class="news-btn">
                </form>
            </div> 
            </div>
        </div>
    </div>
    <div class="copyright-main">
        <div class="section-inner">
            <div class="copyright-content">
                <p class="copyright-text">Copyright © {{ date('Y') }} {{ config('app.name') }}</p>

                <ul class="foot-social-list">
                    <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                </ul>

                <ul class="copy-links">
                    <li><a href="{{ config('setting.terms_url') }}">Terms & Conditions</a></li>
                    <li><a href="{{ config('setting.privacy_url') }}">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->
