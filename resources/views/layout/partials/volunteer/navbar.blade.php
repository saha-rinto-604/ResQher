<!-- Header s t a r t -->
<header class="header">
    <!-- Header Left -->
    <div class="left-content d-flex flex-wrap gap-10">
        <!-- Dashboard -->
        <a href="{{url('/')}}" class="btn-primary-fill btn-sm small-btna {{Route::currentRouteName() === 'volunteer.dashboard' ? 'active' : ''}}">Dashboard</a>
        <a href="{{route('volunteer.documents')}}" class="btn-primary-fill btn-sm small-btna {{Route::currentRouteName() === 'volunteer.documents' ? 'active' : ''}}">Documents</a>
    </div>
    <!-- / Left -->

    <!-- Header Right -->
    <ul class="header-right">
        @php
            $volunteer_details = \Illuminate\Support\Facades\DB::select('select * from volunteer_details where user_id = ?', [auth()->user()->id]);
            if (! empty($volunteer_details))
            {
                $volunteer_details = $volunteer_details[0];
            } else {
                $volunteer_details = null;
            }
        @endphp

        @if($volunteer_details?->approved)
            <li class="cart-list">
                <div class="switch-box-style d-flex align-items-center gap-6 min-w-150">
                    <input id="210" class="volunteer-availability" type="checkbox" {{ $volunteer_details->availability ? 'checked' : '' }} name="availability">
                    <label class="toggle-item" for="210"></label>
                    <p class="info-text hide-text">Unavailable</p>
                    <p class="info-text show-text">Available</p>
                </div>
            </li>
        @endif

        <!-- Login User -->
        <li class="cart-list dropdown">
            <!-- User Profile -->
            <div class="user-info dropdown-toggle toggle-arro-hidden" data-bs-toggle="dropdown"
                 aria-expanded="false" role="button">
                <div class="user-img">
                    <img src="{{ customAsset('assets/images/profile.png') }}" class="img-cover rounded-5" alt="img">
                </div>
            </div>
            <!-- Profile List -->
            <div class="dropdown-menu dropdown-list-style dropdown-menu-end white-bg with-248">
                <ul class="profileListing">
                    <li>
                        <!-- User info -->
                        <a href="#" class="user-sub-info">
                            <div class="user-details">
                                @php
                                    $user = auth()->user();
                                    $name = $user->name;
                                    $email = $user->email;
                                @endphp
                                <span class="name">{{ $name }}</span>
                                <p class="pera">{{ $email }}</p>
                            </div>
                            <div class="user-img">
                                <img src="{{ customAsset('assets/images/profile.png') }}" class="img-cover" alt="img">
                            </div>
                        </a>
                    </li>
                    <li class="list">
                        <a class="list-items dropdown-item" href="{{ route('logout') }}">
                            <span>logout</span>
                            <i class="ri-logout-box-line"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <!-- / Header Right -->
</header>
<!-- / Header -->
