<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="/img/favicon/site.webmanifest">
        <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">

        @php
            $path = request()->getPathInfo();

            if ( \Illuminate\Support\Str::startsWith($path, '/estate/') )
            {
                $id = \Illuminate\Support\Str::replaceFirst('/estate/', '', $path);

                $estate = \App\Models\Estate\Estate::select('id', 'area', 'street_id', 'assignment_id', 'estate_type_id')
                    ->with(['assignment:id', 'estate_type:id', 'street:id,area_id,name', 'street.area:id,name'])
                    ->find( $id );

                if ( $estate )
                {
                    if ( $estate->title )
                        $title = $estate->title;

                    else {
                        $title = $estate->assignment ? $estate->assignment->title : null;
                        $title .= $estate->estate_type ? ' ' . $estate->estate_type->title : null;
                        $title .= $estate->area ? ' ' . $estate->area . ' متری' : null;
                    }

                    $address = '';
                    
                    if ( $estate->street )
                    {
                        if ( $estate->street->area )
                            $address .= ' ' . $estate->street->area->name . ' ،';

                        $address .= ' خیابان ' . $estate->street->name . ' ،';
                    }

                    $address .= ' ' . $estate->address;
                    $address .= $estate->description ? ' | ' . $estate->description : null;
                    
                    \SEO::setTitle( trim($title) );
                    \SEO::setDescription( trim($address) );
                }
            }
        @endphp

        {!! \SEO::generate(false) !!}

        
        <link rel="stylesheet" href="/css/app.css">
        <!-- Fonts -->
        <link href="/fonts/flaticon/flaticon.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/vendors/linericon/style.min.css">
        <link rel="stylesheet" href="/fonts/fontawesome/css/all.min.css">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/vendors/owl-carousel/owl.carousel.min.css">
        <link rel="stylesheet" href="/vendors/animate-css/animate.min.css">
        <!-- main css -->
        <link rel="stylesheet" href="/css/style.min.css">
        <link rel="stylesheet" href="/css/responsive.min.css">
    </head>
    <body>

        <div id="app" class="flex-center position-ref full-height">
            <App></App>
        </div>

        <script src="/js/manifest.js"></script>
        <script src="/js/vendor.js"></script>
        <script src="/js/app-1.3.1.js"></script>
        <script src="/js/jquery-3.2.1.min.js"></script>
        <script src="/js/stellar.js"></script>
        <script src="/vendors/owl-carousel/owl.carousel.min.js"></script>
    </body>
</html>
