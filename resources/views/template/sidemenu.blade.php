<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
@php
    $listAccess = Auth::user()->checkAccess;
    $allowedAccess = $listAccess->filter(fn($item) => $item->access_list === 'Y');

    // 2. Pisahkan data: Ber-Grup vs Tanpa Grup
    $groupedAccess = $allowedAccess
        ->groupBy(fn($item) => $item->group_menu_id ?? 'single') // 'single' untuk menu tanpa group_menu_id
        ->map(function ($items, $key) {
            if ($key === 'single') {
                return [
                    'type' => 'single',
                    'menus' => $items->sortBy(fn($item) => $item->menu->order_position ?? 0)->values(),
                ];
            }

            $firstItem = $items->first();
            return [
                'type' => 'group',
                'group_menu' => $firstItem ? $firstItem->groupMenu : null,
                'menus' => $items->sortBy(fn($item) => $item->menu->order_position ?? 0)->values(),
            ];
        })
        ->filter(function ($group) {
            // Hapus grup jika tipe 'group' tapi tidak memiliki data groupMenu atau daftar menunya kosong
            if ($group['type'] === 'group') {
                return !is_null($group['group_menu']) && $group['menus']->isNotEmpty();
            }
            return $group['menus']->isNotEmpty();
        });
    // Grouping berdasarkan group_menu_id dan urutkan menu di setiap grup
    $resultArray = $groupedAccess->values()->toArray();
@endphp
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ url('/dashboard') }}"><img
                src="{{ asset('assets/img/brand/logo.png') }}"class="main-logo" alt="logo"></a>

        <a class="logo-icon mobile-logo icon-light active" href="{{ url('/dashboard') }}"><img
                src="../../backdoor-sukalelang/assets/img/brand/logo-apps.png" class="logo-icon" alt="logo"></a>

    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround" src="{{ asset('avatar/default.png') }}"
                        onerror="this.onerror=null;this.src='{{ asset('avatar/default.png') }}';"><span
                        class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="fw-semibold mt-3 mb-0">{{ Auth::user()->username }}</h4>

                    <span class="mb-0 text-muted">{{ Auth::user()->roles->name }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">Main</li>

            @foreach ($groupedAccess as $group)
                {{-- KONDISI 1: MENU TANPA GROUP (Single Menu) --}}
                @if ($group['type'] === 'single')
                    @foreach ($group['menus'] as $singleItem)
                        <li class="slide">
                            <a class="side-menu__item" href="{{ url($singleItem->menu->url) }}">
                                @if (!empty($singleItem->menu->icon))
                                    <span class="side-menu__icon"><i class="{{ $singleItem->menu->icon }}"></i></span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                        <path d="M0 0h24v24H0V0z" fill="none" />
                                        <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z" />
                                    </svg>
                                @endif
                                <span class="side-menu__label">{{ $singleItem->menu->name }}</span>
                            </a>
                        </li>
                    @endforeach

                    {{-- KONDISI 2: MENU BER-GROUP (Expandable / Dropdown Menu) --}}
                @elseif ($group['type'] === 'group')
                    <li class="slide">
                        <a class="side-menu__item" data-bs-toggle="slide" href="#">
                            @if (!empty($group['group_menu']->icon))
                                <span class="side-menu__icon"><i class="{{ $group['group_menu']->icon }}"></i></span>
                           
                            @endif

                            <span class="side-menu__label">{{ $group['group_menu']->name }}</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>

                        <ul class="slide-menu">
                            @foreach ($group['menus'] as $subItem)
                                <li>
                                    <a class="slide-item" href="{{ url($subItem->menu->url) }}">
                                        {{ $subItem->menu->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach

            <li class="slide">
                <a class="side-menu__item" href="{{ url('logout') }}">
                    <span class="side-menu__icon "><i class="bx bx-log-out"></i></span>
                    <span class="side-menu__label">Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
