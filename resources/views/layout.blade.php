<html lang="en" class="bg-gray-800">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:url" content="https://bettermontgomery.us/">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Coalition for Better Montgomery">
    <meta property="og:description" content="Advocate for public policies for a better Montgomery County, Maryland.">

    @yield('top-scripts')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.1.4/tailwind.min.css">

    <title>
        @section('title')
            Coalition for Better Montgomery
        @show
    </title>
</head>
<body class="antialiased font-sans">
<div class="relative min-h-screen overflow-hidden bg-gray-800">
    <div class="hidden lg:block absolute scroll-bg"></div>
    <div class="relative min-h-screen lg:min-w-3xl xl:min-w-4xl lg:flex lg:items-center lg:justify-center lg:py-10 lg:pl-8 lg:pr-8 bg-no-repeat">
        <div class="lg:pb-16">
            <div class="px-6 pt-16 pb-12 md:max-w-4xl md:mx-auto lg:max-w-full lg:pt-0">
                @yield('content')
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-500 leading-tight mb-2">Contact@BetterMontgomery.us</p>
                <p class="text-sm text-gray-500 leading-tight">© 2019 Coalition for Better Montgomery (a political action committee)</p>
            </div>
        </div>
    </div>
</div>
@yield('bottom-scripts')
</body>
</html>
