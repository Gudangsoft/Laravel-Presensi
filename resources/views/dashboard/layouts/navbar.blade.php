<nav class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm h-14 flex items-center px-4 lg:px-6">
    <div class="flex w-full items-center justify-between gap-3">

        {{-- Kiri: App name (mobile) / Breadcrumb (desktop) --}}
        <div class="flex items-center gap-3 min-w-0">
            {{-- Hamburger — only for tablet (md–xl) sidebar --}}
            <a href="javascript:;" class="flex-shrink-0 hidden md:flex xl:hidden items-center justify-center w-9 h-9 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors" sidenav-trigger>
                <i class="ri-menu-line text-xl"></i>
            </a>

            {{-- Mobile: logo + app name --}}
            <div class="flex items-center gap-2 xl:hidden">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-600">
                    @if($brand?->logo)
                        <img src="{{ $brand->logoUrl() }}" alt="Logo" class="h-6 w-6 object-contain">
                    @else
                        <i class="ri-map-pin-time-fill text-base text-white"></i>
                    @endif
                </div>
                <span class="text-sm font-bold text-gray-800 truncate">{{ $brand?->nama_aplikasi ?? 'Presensi' }}</span>
            </div>

            {{-- Desktop: Breadcrumb --}}
            <div class="hidden xl:flex flex-col">
                <nav>
                    <ol class="flex items-center gap-1 text-xs text-gray-400">
                        <li><span class="hover:text-indigo-600 transition-colors">Pages</span></li>
                        <li class="flex items-center gap-1">
                            <i class="ri-arrow-right-s-line text-gray-300"></i>
                            <span class="text-gray-600 font-medium capitalize">{{ $title }}</span>
                        </li>
                    </ol>
                    <h6 class="text-sm font-semibold text-gray-800 capitalize leading-tight mt-0.5">{{ $title }}</h6>
                </nav>
            </div>
        </div>

        {{-- Kanan: Dark mode + Profile --}}
        <div class="flex items-center gap-1.5 flex-shrink-0">

            {{-- Dark Mode Toggle --}}
            <button
                class="flex items-center justify-center w-9 h-9 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors focus:outline-none"
                @click="toggleTheme"
                aria-label="Toggle color mode"
            >
                <template x-if="!dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-yellow-400" width="20" height="20" viewBox="0 0 24 24">
                        <rect x="0" y="0" width="24" height="24" fill="rgba(255, 255, 255, 0)" />
                        <g fill="none" stroke="currentColor" stroke-dasharray="2" stroke-dashoffset="2" stroke-linecap="round" stroke-width="2">
                            <path d="M0 0">
                                <animate fill="freeze" attributeName="d" begin="1.2s" dur="0.2s" values="M12 19v1M19 12h1M12 5v-1M5 12h-1;M12 21v1M21 12h1M12 3v-1M3 12h-1" />
                                <animate fill="freeze" attributeName="stroke-dashoffset" begin="1.2s" dur="0.2s" values="2;0" />
                            </path>
                            <path d="M0 0">
                                <animate fill="freeze" attributeName="d" begin="1.5s" dur="0.2s" values="M17 17l0.5 0.5M17 7l0.5 -0.5M7 7l-0.5 -0.5M7 17l-0.5 0.5;M18.5 18.5l0.5 0.5M18.5 5.5l0.5 -0.5M5.5 5.5l-0.5 -0.5M5.5 18.5l-0.5 0.5" />
                                <animate fill="freeze" attributeName="stroke-dashoffset" begin="1.5s" dur="1.2s" values="2;0" />
                            </path>
                            <animateTransform attributeName="transform" dur="30s" repeatCount="indefinite" type="rotate" values="0 12 12;360 12 12" />
                        </g>
                        <g fill="currentColor">
                            <path d="M15.22 6.03L17.75 4.09L14.56 4L13.5 1L12.44 4L9.25 4.09L11.78 6.03L10.87 9.09L13.5 7.28L16.13 9.09L15.22 6.03Z">
                                <animate fill="freeze" attributeName="fill-opacity" dur="0.4s" values="1;0" />
                            </path>
                            <path d="M19.61 12.25L21.25 11L19.19 10.95L18.5 9L17.81 10.95L15.75 11L17.39 12.25L16.8 14.23L18.5 13.06L20.2 14.23L19.61 12.25Z">
                                <animate fill="freeze" attributeName="fill-opacity" begin="0.2s" dur="0.4s" values="1;0" />
                            </path>
                        </g>
                        <g fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M7 6 C7 12.08 11.92 17 18 17 C18.53 17 19.05 16.96 19.56 16.89 C17.95 19.36 15.17 21 12 21 C7.03 21 3 16.97 3 12 C3 8.83 4.64 6.05 7.11 4.44 C7.04 4.95 7 5.47 7 6 Z" />
                            <set attributeName="opacity" begin="0.6s" to="0" />
                        </g>
                        <mask id="lineMdMoonFilledToSunnyFilledLoopTransition0">
                            <circle cx="12" cy="12" r="12" fill="#fff" />
                            <circle cx="18" cy="6" r="12" fill="#fff">
                                <animate fill="freeze" attributeName="cx" begin="0.6s" dur="0.4s" values="18;22" />
                                <animate fill="freeze" attributeName="cy" begin="0.6s" dur="0.4s" values="6;2" />
                                <animate fill="freeze" attributeName="r" begin="0.6s" dur="0.4s" values="12;3" />
                            </circle>
                            <circle cx="18" cy="6" r="10">
                                <animate fill="freeze" attributeName="cx" begin="0.6s" dur="0.4s" values="18;22" />
                                <animate fill="freeze" attributeName="cy" begin="0.6s" dur="0.4s" values="6;2" />
                                <animate fill="freeze" attributeName="r" begin="0.6s" dur="0.4s" values="10;1" />
                            </circle>
                        </mask>
                        <circle cx="12" cy="12" r="10" fill="currentColor" mask="url(#lineMdMoonFilledToSunnyFilledLoopTransition0)" opacity="0">
                            <set attributeName="opacity" begin="0.6s" to="1" />
                            <animate fill="freeze" attributeName="r" begin="0.6s" dur="0.4s" values="10;6" />
                        </circle>
                    </svg>
                </template>
                <template x-if="dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-indigo-300" width="20" height="20" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <g stroke-dasharray="2">
                                <path d="M12 21v1M21 12h1M12 3v-1M3 12h-1">
                                    <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.2s" values="4;2" />
                                </path>
                                <path d="M18.5 18.5l0.5 0.5M18.5 5.5l0.5 -0.5M5.5 5.5l-0.5 -0.5M5.5 18.5l-0.5 0.5">
                                    <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.2s" dur="0.2s" values="4;2" />
                                </path>
                            </g>
                            <path fill="currentColor" d="M7 6 C7 12.08 11.92 17 18 17 C18.53 17 19.05 16.96 19.56 16.89 C17.95 19.36 15.17 21 12 21 C7.03 21 3 16.97 3 12 C3 8.83 4.64 6.05 7.11 4.44 C7.04 4.95 7 5.47 7 6 Z" opacity="0">
                                <set attributeName="opacity" begin="0.5s" to="1" />
                            </path>
                        </g>
                        <g fill="currentColor" fill-opacity="0">
                            <path d="m15.22 6.03l2.53-1.94L14.56 4L13.5 1l-1.06 3l-3.19.09l2.53 1.94l-.91 3.06l2.63-1.81l2.63 1.81z">
                                <animate id="lineMdSunnyFilledLoopToMoonFilledLoopTransition0" fill="freeze" attributeName="fill-opacity" begin="0.6s;lineMdSunnyFilledLoopToMoonFilledLoopTransition0.begin+6s" dur="0.4s" values="0;1" />
                                <animate fill="freeze" attributeName="fill-opacity" begin="lineMdSunnyFilledLoopToMoonFilledLoopTransition0.begin+2.2s" dur="0.4s" values="1;0" />
                            </path>
                            <path d="M13.61 5.25L15.25 4l-2.06-.05L12.5 2l-.69 1.95L9.75 4l1.64 1.25l-.59 1.98l1.7-1.17l1.7 1.17z">
                                <animate fill="freeze" attributeName="fill-opacity" begin="lineMdSunnyFilledLoopToMoonFilledLoopTransition0.begin+3s" dur="0.4s" values="0;1" />
                                <animate fill="freeze" attributeName="fill-opacity" begin="lineMdSunnyFilledLoopToMoonFilledLoopTransition0.begin+5.2s" dur="0.4s" values="1;0" />
                            </path>
                            <path d="M19.61 12.25L21.25 11l-2.06-.05L18.5 9l-.69 1.95l-2.06.05l1.64 1.25l-.59 1.98l1.7-1.17l1.7 1.17z">
                                <animate fill="freeze" attributeName="fill-opacity" begin="lineMdSunnyFilledLoopToMoonFilledLoopTransition0.begin+0.4s" dur="0.4s" values="0;1" />
                                <animate fill="freeze" attributeName="fill-opacity" begin="lineMdSunnyFilledLoopToMoonFilledLoopTransition0.begin+2.8s" dur="0.4s" values="1;0" />
                            </path>
                            <path d="m20.828 9.731l1.876-1.439l-2.366-.067L19.552 6l-.786 2.225l-2.366.067l1.876 1.439L17.601 12l1.951-1.342L21.503 12z">
                                <animate fill="freeze" attributeName="fill-opacity" begin="lineMdSunnyFilledLoopToMoonFilledLoopTransition0.begin+3.4s" dur="0.4s" values="0;1" />
                                <animate fill="freeze" attributeName="fill-opacity" begin="lineMdSunnyFilledLoopToMoonFilledLoopTransition0.begin+5.6s" dur="0.4s" values="1;0" />
                            </path>
                        </g>
                        <mask id="lineMdSunnyFilledLoopToMoonFilledLoopTransition1">
                            <circle cx="12" cy="12" r="12" fill="#fff" />
                            <circle cx="22" cy="2" r="3" fill="#fff">
                                <animate fill="freeze" attributeName="cx" begin="0.1s" dur="0.4s" values="22;18" />
                                <animate fill="freeze" attributeName="cy" begin="0.1s" dur="0.4s" values="2;6" />
                                <animate fill="freeze" attributeName="r" begin="0.1s" dur="0.4s" values="3;12" />
                            </circle>
                            <circle cx="22" cy="2" r="1">
                                <animate fill="freeze" attributeName="cx" begin="0.1s" dur="0.4s" values="22;18" />
                                <animate fill="freeze" attributeName="cy" begin="0.1s" dur="0.4s" values="2;6" />
                                <animate fill="freeze" attributeName="r" begin="0.1s" dur="0.4s" values="1;10" />
                            </circle>
                        </mask>
                        <circle cx="12" cy="12" r="6" fill="currentColor" mask="url(#lineMdSunnyFilledLoopToMoonFilledLoopTransition1)">
                            <set attributeName="opacity" begin="0.5s" to="0" />
                            <animate fill="freeze" attributeName="r" begin="0.1s" dur="0.4s" values="6;10" />
                        </circle>
                    </svg>
                </template>
            </button>

            {{-- Profile Dropdown --}}
            <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                <button
                    @click="profileOpen = !profileOpen"
                    class="flex items-center gap-2 px-1.5 py-1 rounded-xl hover:bg-gray-100 transition-colors focus:outline-none"
                >
                    @if (Auth::guard("karyawan")->user()->foto)
                        <img
                            src="{{ asset("storage/unggah/karyawan/" . Auth::guard("karyawan")->user()->foto) }}"
                            alt="{{ Auth::guard("karyawan")->user()->nama_lengkap }}"
                            class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-100"
                        />
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center ring-2 ring-indigo-100">
                            <span class="text-white text-xs font-bold uppercase">
                                {{ mb_substr(Auth::guard("karyawan")->user()->nama_lengkap, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <i class="ri-arrow-down-s-line text-gray-400 text-sm hidden sm:block transition-transform duration-200" :class="profileOpen ? 'rotate-180' : ''"></i>
                </button>

                {{-- Dropdown Menu --}}
                <div
                    x-show="profileOpen"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                    class="absolute right-0 mt-2 w-60 rounded-xl shadow-lg border border-gray-100 bg-white overflow-hidden z-50"
                    style="display: none;"
                >
                    {{-- Header dropdown --}}
                    <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            @if (Auth::guard("karyawan")->user()->foto)
                                <img
                                    src="{{ asset("storage/unggah/karyawan/" . Auth::guard("karyawan")->user()->foto) }}"
                                    alt="{{ Auth::guard("karyawan")->user()->nama_lengkap }}"
                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm"
                                />
                            @else
                                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center ring-2 ring-white shadow-sm">
                                    <span class="text-white text-sm font-bold uppercase">
                                        {{ mb_substr(Auth::guard("karyawan")->user()->nama_lengkap, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex flex-col min-w-0">
                                <span class="text-sm font-semibold text-gray-800 truncate">{{ Auth::guard("karyawan")->user()->nama_lengkap }}</span>
                                <span class="rounded-full px-2 py-0.5 mt-0.5 text-xs font-medium bg-indigo-100 text-indigo-700 w-fit">Karyawan</span>
                            </div>
                        </div>
                    </div>

                    {{-- Menu items --}}
                    <div class="py-1.5">
                        <a href="{{ route("karyawan.profile") }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors group">
                            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                                <i class="ri-user-3-line text-gray-500 group-hover:text-indigo-600 text-sm"></i>
                            </span>
                            <span class="font-medium">Profil Saya</span>
                        </a>
                    </div>

                    <div class="border-t border-gray-100 py-1.5">
                        <form method="POST" action="{{ route("logout.auth") }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors group">
                                <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-red-50 group-hover:bg-red-100 transition-colors">
                                    <i class="ri-logout-box-r-line text-red-500 text-sm"></i>
                                </span>
                                <span class="font-medium">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>
