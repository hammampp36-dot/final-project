<nav x-data="{ open: false }" class="bg-white border-b {{ Auth::user()->isAdmin() ? 'border-indigo-100 shadow-xs' : 'border-emerald-100 shadow-xs' }}">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-5 sm:-my-px sm:ms-8 sm:flex items-center">
                    @if (Auth::user()->isAdmin())
                        <!-- MENU KHUSUS ROLE ADMIN -->
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard Admin') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Data Petani') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.crops.index')" :active="request()->routeIs('admin.crops.*')">
                            {{ __('Master Tanaman') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.lands.index')" :active="request()->routeIs('admin.lands.*')">
                            {{ __('Supervisi Lahan') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.schedules.index')" :active="request()->routeIs('admin.schedules.*')">
                            {{ __('Supervisi Jadwal') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')">
                            {{ __('Master Kas Global') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.calculator')" :active="request()->routeIs('admin.calculator')">
                            {{ __('Kalkulator Makro') }}
                        </x-nav-link>
                    @else
                        <!-- MENU KHUSUS ROLE USER (PETANI) -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('lands.index')" :active="request()->routeIs('lands.*')">
                            {{ __('Lahan & QR Saya') }}
                        </x-nav-link>

                        <x-nav-link :href="route('crops.index')" :active="request()->routeIs('crops.*')">
                            {{ __('Katalog Tanaman') }}
                        </x-nav-link>

                        <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                            {{ __('Jadwal Tanam') }}
                        </x-nav-link>

                        <x-nav-link :href="route('calculator.index')" :active="request()->routeIs('calculator.*')">
                            {{ __('Kalkulator Tani') }}
                        </x-nav-link>

                        <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                            {{ __('Keuangan Kas Saya') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown & Role Badge -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <!-- Role Badge -->
                @if (Auth::user()->isAdmin())
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                        🛡️ Administrator
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        👨‍🌾 Petani Mitra
                    </span>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border {{ Auth::user()->isAdmin() ? 'border-indigo-200 text-indigo-900 bg-indigo-50 hover:bg-indigo-100' : 'border-emerald-200 text-emerald-800 bg-emerald-50 hover:bg-emerald-100' }} text-sm leading-4 font-semibold rounded-xl focus:outline-none transition ease-in-out duration-150">
                            <div class="w-2 h-2 rounded-full {{ Auth::user()->isAdmin() ? 'bg-indigo-600' : 'bg-emerald-500' }} me-2"></div>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1.5">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 text-xs text-gray-500">
                            Peran: <strong class="text-gray-800 uppercase">{{ Auth::user()->role }}</strong>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil Akun') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-rose-600 hover:text-rose-700">
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md {{ Auth::user()->isAdmin() ? 'text-indigo-600 hover:bg-indigo-50' : 'text-emerald-600 hover:bg-emerald-50' }} focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard Admin') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Data Petani') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.crops.index')" :active="request()->routeIs('admin.crops.*')">
                    {{ __('Master Tanaman') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.lands.index')" :active="request()->routeIs('admin.lands.*')">
                    {{ __('Supervisi Lahan') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.schedules.index')" :active="request()->routeIs('admin.schedules.*')">
                    {{ __('Supervisi Jadwal') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')">
                    {{ __('Master Kas Global') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.calculator')" :active="request()->routeIs('admin.calculator')">
                    {{ __('Kalkulator Makro') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('lands.index')" :active="request()->routeIs('lands.*')">
                    {{ __('Lahan & QR Saya') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('crops.index')" :active="request()->routeIs('crops.*')">
                    {{ __('Katalog Tanaman') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('calculator.index')" :active="request()->routeIs('calculator.*')">
                    {{ __('Kalkulator Tani') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                    {{ __('Jadwal Tanam') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                    {{ __('Keuangan Kas Saya') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }} ({{ strtoupper(Auth::user()->role) }})</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil Akun') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-rose-600">
                        {{ __('Keluar (Log Out)') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
