<?php $class = true ?>
@foreach($regions as $region)
  <div style="background-color:{{ (($class = !$class) ? '#000' : '#151515' ) }}">
    <div style="width:48%; float:left; cursor: pointer; overflow:hidden;">
      <a style="color:#FFA500;" href="#" onclick="document.location.href='secondlife://{{$region->regionname }}/128/128/50'">{{$region->regionname }}</a>
    </div>
    <div style="width:21%; margin-left:15px; overflow:hidden; float:left">{{ $region->locx/256 }}</div>
    <div style="width:21%; margin-left:10px; overflow:hidden; float:left">{{ $region->locy/256 }}</div>
    <div style="clear: both;"></div> 
  </div>
@endforeach