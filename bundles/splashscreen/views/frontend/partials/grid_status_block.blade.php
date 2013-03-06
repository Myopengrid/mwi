<div id="gridstatus">
  <table style="width:100%; border:0; border-spacing:0; border-collapse:collapse;">
    <tbody>
        <tr>
          <td class="gridbox_tl"></td>
          <td class="gridbox_t"></td>
          <td class="gridbox_tr"></td>
        </tr>
        <tr>
          <td class="gridbox_l"></td>
            <td class="black_content">
              <div style="padding:2px;">
                <strong>{{ __('splashscreen::lang.GRID STATUS')->get(ADM_LANG) }}</strong> 
                @if(isset($grid_status_block['grid_status']))
                  @if(strtolower($grid_status_block['grid_status']) == 'online')
                    <span style="float:right" class="online">{{ Str::upper($grid_status_block['grid_status'])}}</span>
                  @else
                    <span style="float:right" class="offline">{{ Str::upper($grid_status_block['grid_status'])}}</span>
                  @endif
                @endif</div>

                @if(isset($grid_status_block['total_users']))
                  <div style="padding:2px; background-color:#151515">
                    {{ __('splashscreen::lang.Total Users:')->get(ADM_LANG) }}
                      <span style="float:right">{{$grid_status_block['total_users']}}</span>
                  </div>
                @endif

                @if(isset($grid_status_block['total_regions']))
                <div style="padding:2px">
                  {{ __('splashscreen::lang.Total Regions:')->get(ADM_LANG) }}
                  <span style="float:right">{{$grid_status_block['total_regions']}}</span>
                </div>
                @endif
                
                @if(isset($grid_status_block['active_users']))
                <div style="padding:2px; background-color:#151515">
                  {{ __('splashscreen::lang.Active Users Last 30 Days:')->get(ADM_LANG) }}
                  <span style="float:right">{{ $grid_status_block['active_users']}}</span>
                </div>
              @endif

              @if(isset($grid_status_block['users_online']))
              <div style="padding:2px">
                {{ __('splashscreen::lang.Users Online:')->get(ADM_LANG) }} 
                <span style="float:right">{{$grid_status_block['users_online']}}</span>
              </div>
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
</div>