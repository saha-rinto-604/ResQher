<!-- Header s t a r t -->
<header class="header">
    <!-- Header Left -->
    <div class="left-content d-flex flex-wrap gap-10">
        <a href="{{url('/')}}" class="btn-primary-fill btn-sm small-btna bg-primary text-white">
            <i class="ri-dashboard-line"></i>
        </a>

        <button class="half-expand-toggle sidebar-toggle">
            <i class="ri-arrow-left-right-fill"></i>
        </button>
    </div>
    <!-- / Left -->

    <!-- Header Right -->
    <ul class="header-right">
        <!-- Login User -->
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
                            @php
                                $user = auth()->user();
                                $name = $user->name ?? 'Admin';
                                $email = $user->email ?? '';
                            @endphp
                            <div class="user-details">
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
