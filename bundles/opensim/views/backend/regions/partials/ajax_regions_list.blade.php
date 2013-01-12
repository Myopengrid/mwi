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
    <tr class="opensim-region-row"> 
    <td colspan="4">No Regions Found</td>
    </tr>
@endif