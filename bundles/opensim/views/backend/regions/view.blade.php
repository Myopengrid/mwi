<h3 style="margin-bottom:20px;">Region Details</h3>

<form class="bs-docs-example form-horizontal"> 
    <div class="control-group">
        <label for="regionname" class="control-label">Region Name</label>
        <div class="controls">
            <input disabled="disabled" type="text" id="regionname" value="{{ $region->regionname }}">
            <span class="help-inline"></span>
        </div>
    </div>

    <div class="control-group">
        <label for="serveruri" class="control-label">Server URI</label>
        <div class="controls">
            <input disabled="disabled" type="text" id="serveruri" value="{{ $region->serveruri }}">
            <span class="help-inline"></span>
        </div>
    </div>

    <div class="control-group">
        <label for="locx" class="control-label">X Coordenate</label>
        <div class="controls">
            <input disabled="disabled" type="text" id="locx" value="{{ $region->locx/256 }}">
            <span class="help-inline"></span>
        </div>
    </div>
    
    <div class="control-group">
        <label for="locy" class="control-label">Y Coordenate</label>
        <div class="controls">
            <input disabled="disabled" type="text" id="locy" value="{{ $region->locy/256 }}">
            <span class="help-inline"></span>
        </div>
    </div>

    <div class="control-group">
        <label for="last_seen" class="control-label">Last Seen</label>
        <div class="controls">
            <input disabled="disabled" type="text" id="last_seen" value="{{ date('m-d-Y h:j:s', $region->last_seen); }}">
            <span class="help-inline"></span>
        </div>
    </div>
</form>