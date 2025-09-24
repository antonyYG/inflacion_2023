@php
    $links = [
        [
            'icon'=>'fa-solid fa-gauge',
            'name'=>'Dashboard',
            'route'=>route('dashboard.grafico'),
            'active' => request()->routeIs('dashboard.grafico')

        ],
    ]
@endphp

<aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-[100dvh] pt-20 transition-transform -translate-x-full bg-slate-600 border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
        aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-slate-600 dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                @foreach ($links as $link )
                    <li>
                        @isset($link['header'])

                            <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">
                                {{$link['header']}}
                            </div>
                        
                        @else

                            <a href="{{$link['route']}}"
                                class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-100 hover:text-black dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-blue-500' : '' }}">
                                <span class="inline-flex w-6 h-6 justify-center items-center">
                                    <i class="{{$link['icon']}} text-gray-500"></i>
                                </span>
                                <span class="ml-2">
                                    {{$link['name']}}
                                </span>
                            </a>

                        @endisset
                    </li>
                @endforeach
            </ul>
        </div>
</aside>