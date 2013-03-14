<table style="width:100%; border:0; border-spacing:0; border-collapse:collapse;">
  <tbody>
    <tr>
      <td class="gridbox_tl"></td>
      <td class="gridbox_t"></td>
      <td class="gridbox_tr"></td>
    </tr>
    <tr>
      <td class="gridbox_l"></td>
      <td class="black_content" style="vertical-align:top; text-align:left;">
        @if(isset($news))
        <strong>{{ $site_name }} News:</strong> 
        <?php $class = true ?>
        @foreach($news as $the_news)
        <div style="padding:2px; background-color:{{ (($class = !$class) ? '#000' : '#151515' ) }}">
          <a href="{{ $news_handler.$the_news->slug }}" target="{{ Config::get('settings::core.splashscreen_flash_news_link') }}">{{ Str::title($the_news->title) }}</a>
          <span style="float:right">{{ date(APP_DATE_FORMAT, strtotime($the_news->created_at)) }}</span>
        </div>
        @endforeach
        @endif
      </td>
      <td class="gridbox_r"></td>
    </tr>
    <tr>
      <td class="gridbox_bl"></td>
      <td class="gridbox_b"></td>
      <td class="gridbox_br"></td>
    </tr>
  </tbody>
</table>