 <div id="regionbox">
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
              <div>
                <div style="color: #93A9D5; float:left"><strong>Region Name:</strong></div>
                <div style="color: #93A9D5; margin-left:85px; float:left"><strong>X</strong></div>
                <div style="color: #93A9D5; margin-left:65px; float:left"><strong>Y</strong></div>
              </div>

              <div style="border:hidden; color:#ffffff; padding:0px; width:300px; height:170px; overflow:auto; ">
                {{ View::make('splashscreen::frontend.partials.regions_list_content', array('regions' => $regions)) }}
              </div>
            </td>
            <td class="gridbox_r"></td>
        </tr>
        <tr>
          <td class="gridbox_bl"></td>
            <td class="gridbox_b"><span class="gridbox_br"></span></td>
            <td class="gridbox_br"></td>
        </tr>
    </tbody>
</table>
</div>
            