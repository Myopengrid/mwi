<table style="width:300px; border:0; border-spacing:0; border-collapse:collapse;">
  <tbody>
    <tr>
      <td class="box{{$block['color']}}_tl"></td>
      <td class="box{{$block['color']}}_t"></td>
      <td class="box{{$block['color']}}_tr"></td>
    </tr>
    <tr>
      <td class="box{{$block['color']}}_l"></td>
      <td class="black_content">
        <img style="vertical-align: middle;" src="<?php echo URL::base() ?>/bundles/splashscreen/img/icons/alert.png" alt="" />
        &nbsp;<strong>{{$block['title']}}</strong>
        <div id="{{$block['color']}}" style="margin: 1px 0px 0px">
          <img height="1" src="{{ URL::base() }}/bundles/splashscreen/img/icons/spacer.gif" width="1" alt="" />
        </div>
        <div class="boxtext">
          <p style="font-size: 11px">{{$block['text']}}</p>
        </div>
      </td>
      <td class="box{{$block['color']}}_r"></td>
    </tr>
    <tr>
      <td class="box{{$block['color']}}_bl"></td>
      <td class="box{{$block['color']}}_b"></td>
      <td class="box{{$block['color']}}_br"></td>
    </tr>
  </tbody>
</table>
           