<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Splash Screen - {{ $site_name }}</title>

  <link href="{{ URL::base() }}/bundles/splashscreen/css/style.css" type="text/css" rel="stylesheet" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

  <link rel="shortcut icon" href="{{ URL::base() }}/bundles/splashscreen/img/ico/favicon.ico">

  <script type="text/javascript">
    EFFECT = "{{ $effect }}";
    BKG_IMAGES = {{ json_encode($images) }};
    BKG_DELAY_TIME = {{ $effect_delay }};
  </script>
</head>

<body>
  <div id="top_image">
    <img height="240" src="{{ URL::base() }}/bundles/splashscreen/img/logo/{{$logo}}" width="340" alt="" />
  </div>

  <div id="bottom_left">
    @if(isset($grid_status_block['status_top_left_block']) and !empty($grid_status_block['status_top_left_block']))
    
    {{ View::make('splashscreen::frontend.partials.top_left_block', array('block' => $grid_status_block['status_top_left_block'])) }}
    
    @endif
    <br />
    @if(isset($regions) and !empty($regions))

    {{ View::make('splashscreen::frontend.partials.region_box_list_block', array('regions' => $regions)) }}

    @endif
  </div>

  <img id="background_image" src="#" alt="" />

  <div id="bottom">
    <div id="news">
      @if(isset($news) and !empty($news))
      <?php
        $partial_data = array(
          'site_name'    => $site_name,
          'news_handler' => $news_handler,
          'news'         => $news,
        );
      ?>

      {{ View::make('splashscreen::frontend.partials.news_block', $partial_data) }}
      @endif
    </div>
  </div>

  <div id="topright">
    <br />
    <div id="updatebox"></div>
    <br />
    <br />
    @if(isset($grid_status_block) and !empty($grid_status_block))

    {{ View::make('splashscreen::frontend.partials.grid_status_block', array('grid_status_block' => $grid_status_block)) }}

    @endif
    <br />
    <div id="Infobox">
      @if(isset($status_message_block) and !empty($status_message_block))

      {{ View::make('splashscreen::frontend.partials.grid_status_message_block', array('block' => $status_message_block)) }}

      @endif
    </div>
  </div>

  <script src="{{ URL::base() }}/bundles/splashscreen/js/imageswitch.js" type="text/javascript"></script>
</body>
</html>