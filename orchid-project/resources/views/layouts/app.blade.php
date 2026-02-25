<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php($seo = current_seo_meta())
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seo['title'] }}</title>
    <link rel="shortcut icon" href="{{ asset('ico.ico') }}" type="image/x-icon" />
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="keywords" content="{{ $seo['keywords'] }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['og_description'] }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    @if($seo['og_image'])
        <meta property="og:image" content="{{ $seo['og_image'] }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['twitter_description'] }}">
    @if($seo['og_image'])
        <meta name="twitter:image" content="{{ $seo['og_image'] }}">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Fonts и стили --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="
https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
" rel="stylesheet">
    <link href="{{ asset('css/screen.css') }}?v={{ filemtime(public_path('css/screen.css')) }}" rel="stylesheet">
</head>

<body
    class=""
    data-barba="wrapper"
    data-home-path="{{ parse_url(page_url('home'), PHP_URL_PATH) }}"
    data-about-path="{{ parse_url(page_url('about'), PHP_URL_PATH) }}"
    data-works-path="{{ parse_url(page_url('works'), PHP_URL_PATH) }}"
    data-material-path="{{ parse_url(page_url('material'), PHP_URL_PATH) }}"
    data-contacts-path="{{ parse_url(page_url('contacts'), PHP_URL_PATH) }}">
    {{-- Header --}}
    @include('partials.header')
    <div class="preloader">
        <div class="logo">
            <svg width="458" height="120" viewBox="0 0 458 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="letters"
                    d="M175.009 55.369C167.504 53.8518 162.304 51.8483 159.412 49.3643C156.659 47.0194 155.283 43.7063 155.283 39.4239C155.283 35.1451 157.039 31.8665 160.548 29.5891C163.854 27.4492 168.398 26.3778 174.182 26.3778C179.553 26.3778 183.718 27.4834 186.68 29.6921C189.639 31.9708 191.534 35.5604 192.36 40.4606C192.497 41.5651 192.944 42.3944 193.703 42.9446C194.46 43.4987 195.285 43.7394 196.182 43.6693C197.145 43.5331 197.868 43.0847 198.35 42.3229C198.97 41.4962 199.177 40.4264 198.97 39.1142C198.144 34.0064 196.145 29.8985 192.98 26.7931C188.711 22.6521 182.582 20.5807 174.595 20.5807C166.541 20.5807 160.204 22.2381 155.591 25.5511C151.047 28.933 148.774 33.6268 148.774 39.6315C148.774 45.8436 151.012 50.6765 155.488 54.127C159.275 56.9574 165.403 59.3736 173.873 61.3747C182.686 63.3759 188.572 65.8254 191.534 68.7245C194.013 71.1422 195.252 74.905 195.252 80.0102C195.252 84.708 192.877 88.3967 188.125 91.087C184.2 93.3696 179.759 94.5007 174.802 94.5007C168.192 94.5007 163.199 93.1964 159.826 90.5723C156.039 87.7445 153.903 83.2202 153.422 77.0092C153.284 75.8374 152.871 74.9355 152.184 74.3188C151.563 73.7661 150.805 73.5242 149.91 73.5905C149.086 73.6617 148.397 73.9724 147.846 74.5228C147.157 75.2157 146.881 76.1123 147.018 77.2129C147.706 85.2224 150.53 91.1227 155.488 94.9184C160.032 98.5106 166.541 100.304 175.009 100.304C182.514 100.304 188.745 98.4699 193.703 94.8167C199.074 90.9493 201.759 85.7728 201.759 79.2866C201.759 72.6579 199.797 67.5527 195.872 63.9631C191.808 60.2347 184.854 57.3714 175.009 55.369Z"
                    fill="black" />
                <path class="letters"
                    d="M266.724 92.748H228.817C227.303 92.748 226.133 92.2589 225.308 91.296C224.619 90.4706 224.273 89.431 224.273 88.1929V61.9951H261.769C262.8 61.9951 263.624 61.6857 264.244 61.0654C264.793 60.5113 265.072 59.8591 265.072 59.0974C265.072 58.2681 264.793 57.5791 264.244 57.0261C263.624 56.4746 262.8 56.1983 261.769 56.1983H224.273V32.7976C224.273 31.4883 224.619 30.4169 225.308 29.5891C226.133 28.6234 227.303 28.1382 228.817 28.1382H265.382C266.343 28.1382 267.136 27.8288 267.756 27.2071C268.305 26.6556 268.584 26.0009 268.584 25.2391C268.584 24.4124 268.305 23.7232 267.756 23.1691C267.136 22.619 266.343 22.3411 265.382 22.3411H227.681C224.515 22.3411 222.037 23.3768 220.246 25.4468C218.662 27.3115 217.869 29.6246 217.869 32.3849V88.6056C217.869 91.296 218.662 93.5734 220.246 95.4383C222.037 97.5068 224.515 98.5411 227.681 98.5411H266.724C267.756 98.5411 268.584 98.2354 269.199 97.6137C269.753 97.0583 270.028 96.4062 270.028 95.6421C270.028 94.8167 269.753 94.1288 269.199 93.5734C268.584 93.018 267.756 92.748 266.724 92.748Z"
                    fill="black" />
                <path class="letters"
                    d="M326.729 92.748H294.917C293.402 92.748 292.234 92.2588 291.41 91.296C290.719 90.4706 290.373 89.431 290.373 88.1929V23.9984C290.373 22.8941 290.028 22.0304 289.342 21.4088C288.722 20.8586 288 20.5807 287.171 20.5807C286.277 20.5807 285.519 20.8586 284.9 21.4088C284.279 22.0304 283.969 22.8941 283.969 23.9984V88.6056C283.969 91.296 284.762 93.5734 286.348 95.4383C288.137 97.5068 290.617 98.5411 293.784 98.5411H326.729C327.761 98.5411 328.589 98.2354 329.209 97.6137C329.758 97.0583 330.033 96.4061 330.033 95.6421C330.033 94.8167 329.758 94.1287 329.209 93.5734C328.589 93.018 327.761 92.748 326.729 92.748Z"
                    fill="black" />
                <path class="letters"
                    d="M371.45 25.4468C370.896 23.8596 370.073 22.6521 368.97 21.8241C367.938 21.0649 366.835 20.6854 365.667 20.6854C364.355 20.6854 363.186 21.0649 362.155 21.8241C361.051 22.6521 360.228 23.8596 359.674 25.4468L334.165 95.6421C333.682 96.7476 333.651 97.7157 334.063 98.5411C334.475 99.3717 335.095 99.9219 335.923 100.197C336.747 100.477 337.54 100.411 338.297 99.9931C339.191 99.5092 339.847 98.7501 340.259 97.7157L349.036 73.5905H382.195L390.866 97.7157C391.278 98.7501 391.903 99.5092 392.726 99.9931C393.549 100.335 394.378 100.37 395.206 100.1C396.03 99.8199 396.614 99.3002 396.96 98.5411C397.377 97.6445 397.407 96.6764 397.061 95.6421L371.45 25.4468ZM351.105 67.8991L365.458 28.5535H365.773L380.025 67.8991H351.105Z"
                    fill="black" />
                <path class="letters"
                    d="M456.141 23.1691C455.526 22.619 454.698 22.3411 453.661 22.3411H404.396C402.744 22.3411 401.92 23.3092 401.92 25.2391C401.92 27.1727 402.744 28.1382 404.396 28.1382H426.291V64.1556C426.286 64.2574 426.246 64.3377 426.246 64.446V119.436H432.746V64.446C432.746 64.344 432.706 64.2728 432.695 64.176V28.1382H453.661C454.698 28.1382 455.526 27.8288 456.141 27.2071C456.761 26.6556 457.071 26.0009 457.071 25.2391C457.071 24.4124 456.761 23.7232 456.141 23.1691Z"
                    fill="black" />
                <path class="letters"
                    d="M124.607 23.3768V88.3967L88.3539 27.9318L84.8419 22.7551C83.7403 21.0305 82.1571 20.3058 80.0922 20.5808C78.0263 20.9275 76.9932 22.307 76.9932 24.7218V119.436H83.4975V96.6968C83.4975 96.6916 83.5001 96.6814 83.5001 96.6764V32.3849L118.513 90.2617L124.298 98.7501C125.122 100.197 126.466 100.645 128.325 100.1C130.184 99.6112 131.112 98.1997 131.112 95.8511V0.514954L124.607 7.10556V23.3768Z"
                    fill="black" />
                <path class="letters"
                    d="M39.6618 27.8695L36.2529 37.261L47.3518 67.8991H18.5069L42.9693 0.514954H36.0457L18.4078 49.1019L1.49301 95.6421C1.01159 96.7476 0.977244 97.7157 1.39022 98.5411C1.80451 99.3717 2.42437 99.9219 3.24903 100.197C4.07629 100.477 4.8666 100.411 5.62516 99.9931C6.51983 99.5092 7.17404 98.7501 7.58702 97.7157L16.3673 73.5905H49.5208L58.1968 97.7157C58.6098 98.7501 59.2286 99.5092 60.0543 99.9931C60.8816 100.335 61.7075 100.37 62.5335 100.1C63.3607 99.8199 63.9452 99.3003 64.2895 98.5411C64.7025 97.6445 64.7368 96.6764 64.3936 95.6421L39.6618 27.8695Z"
                    fill="black" />
                <path class="green-lines"
                    d="M131.112 0.514954H37.6885V0.516258H36.0457L28.7827 20.525H35.7063L40.602 7.03565L124.607 7.10556L131.112 0.514954Z"
                    fill="#B8F13C" />
                <path class="green-lines" d="M426.245 112.915H83.4975L76.9932 119.436H432.746L426.245 112.915Z" fill="#B8F13C" />
            </svg>
        </div>
    </div>
    {{-- Контент --}}
    <main class="main" data-barba="container" data-barba-namespace="@yield('barba', 'home')">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/@barba/core"></script>
    <script src="https://unpkg.com/lenis@1.1.7/dist/lenis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
    <script src="
https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/DrawSVGPlugin.min.js"></script>
    <script src="{{ asset('js/prodaction.js') }}?v={{ filemtime(public_path('js/prodaction.js')) }}"></script>
</body>

</html>
