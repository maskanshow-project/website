<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <link href="/fonts/flaticon/flaticon.css" rel="stylesheet">
        <link rel="stylesheet" href="/vendors/linericon/style.css">
        <link rel="stylesheet" href="/fonts/fontawesome/css/all.css">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="/css/bootstrap.css">
        <link rel="stylesheet" href="/vendors/owl-carousel/owl.carousel.min.css">
        <link rel="stylesheet" href="/vendors/animate-css/animate.css">
        <!-- main css -->
        <link rel="stylesheet" href="/css/style.css">
        <link rel="stylesheet" href="/css/responsive.css">
    </head>
    <body>

        <div id="app" class="flex-center position-ref full-height">
            <App></App>
        </div>

        <script src="/js/app.js"></script>
        <script src="/js/jquery-3.2.1.min.js"></script>
        <script src="/js/stellar.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/vendors/owl-carousel/owl.carousel.min.js"></script>
    </body>
</html>
