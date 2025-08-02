<!-- Header s t a r t -->
<header class="header">
    <!-- Header Left -->
    <div class="left-content d-flex flex-wrap gap-10">
        <!-- Dashboard -->
        <a href="{{url('/')}}" class="btn-primary-fill btn-sm small-btna bg-primary text-white">
            <i class="ri-home-line"></i>
        </a>

        <!-- Sidebar Toggle Button -->
{{--        <button class="half-expand-toggle sidebar-toggle">--}}
{{--            <i class="ri-arrow-left-right-fill"></i>--}}
{{--        </button>--}}
        <a href="{{ route('user.history') }}" class="btn btn-primary d-flex justify-content-between align-items-center gap-5">
            <i class="ri-chat-history-line"></i>
            <span>Incident History</span>
        </a>
    </div>
    <!-- / Left -->

    <!-- Header Right -->
    <ul class="header-right">
        <!-- Login User -->
        <li class="cart-list dropdown stop-tracking-wrapper" style="display: none">
            <a href="javascript:void(0)" class="stop-tracking-btn" id="stopTracking" data-bs-toggle="modal" data-bs-target="#stopSosAlertModal">
                <div class="text-white p-2 d-flex justify-content-between align-items-center gap-2 fw-bold" style="background-color: #FF0000">
                    <i class="ri-stop-line"></i>
                    <p class="text-white fw-bold">Stop Tracking</p>
                </div>
            </a>
        </li>
        <li class="cart-list dropdown">
            <!-- User Profile -->
            <div class="user-info dropdown-toggle toggle-arro-hidden" data-bs-toggle="dropdown"
                 aria-expanded="false" role="button">
                <div class="user-img">
                    <img src="{{ customAsset('assets/images/profile.png') }}" class="img-cover" alt="img" style="border-radius: 50%">
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
                                    $name = $user->name ?? 'User';
                                    $email = $user->email ?? ''
                                @endphp
                                <span class="name">{{ $name }}</span>
                                <p class="pera">{{ $email }}</p>
                            </div>
                        </a>
                    </li>
                    <li class="list">
                        <a class="list-items dropdown-item" href="{{ route('user.profile') }}">
                            <span>Profile</span>
                            <i class="ri-account-circle-line"></i>
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
