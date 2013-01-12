<?php themes\add_asset('region_filter.js', 'mod: opensim/js', array('scripts'), 'footer') ?>

<fieldset id="filters">
        <legend>Search</legend>
        <div class="span10 offset0">
        <form accept-charset="UTF-8" action="{{ URL::base().'/'.ADM_URI }}/opensim/regions" method="POST" class="form-inline">
            <div style="display:none">
                {{ Form::token() }}
            </div>  

            <input type="hidden" value="opensim" name="f_module">
            
            <ul style="float:left">
                <li style="float:left; margin:10px 5px">
                    <select name="search_by">
                        <option value="ownerName">Owner Name</option>
                        <option value="regionName">Region Name</option>
                    </select>
                </li>
                
                <li style="float:left; margin:10px 5px">
                    <input type="text" value="" name="search_for">
                </li>
                <li style="float:left; margin:10px 5px">
                    <a class="btn cancel" href="{{ URL::base().'/'.ADM_URI}}/opensim/regions">Reset</a>
                </li>
            </ul>
        </form>
    </div>
    </fieldset>

    <div class="row">
    <div class="span10 offset2">
        {{ $pagination_links }}
    </div>
</div>

<table id="opensim-regions-list" class="table table-bordered">
    <thead>
        <tr>
            <th>Region Name</th>
            <th>Owner Name</th>
            <th>Last Seen</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="filter-content">
        @if(isset($regions) and !empty($regions))
            @foreach($regions as $region)
            <tr id="{{ $region->uuid }}" class="opensim-region-row"> 
                    <td>{{ $region->regionname }}</td>
                    <?php
                    $owner_name = 'Unknown'; 
                    if(isset($region->firstname) and !empty($region->firstname))
                    {
                        $owner_name = $region->firstname . ' ' . $region->lastname;
                    }
                    ?>
                    <td>{{ $owner_name }}</td>
                    <td>{{ date('m-d-Y h:j:s', $region->last_seen); }}</td>
                    <td class="region-action-buttons">
                        <a href="{{ URL::base().'/'.ADM_URI }}/opensim/regions/{{ $region->uuid }}/edit" class="btn btn-mini"><i class="icon-eye-open"></i> View Details</a>

                        <a href="{{ URL::base().'/'.ADM_URI }}/opensim/regions/{{ $region->uuid }}" class="btn btn-mini btn-danger confirm" data-title="Are you sure you wanto to remove &quot;{{ $region->regionname }}&quot;?" data-verb="DELETE" data-module="modules"><i class="icon-trash icon-white"></i> Remove</a>
                    </td>
                </tr>
            @endforeach
        @else
        No regions
        @endif
    </tbody>
</table>
<div class="row">
    <div class="span10 offset2">
        {{ $pagination_links }}
    </div>
</div>
