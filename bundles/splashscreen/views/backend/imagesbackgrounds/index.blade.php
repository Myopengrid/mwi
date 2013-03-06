<?php themes\add_asset('background-thumbnails.css', 'mod: splashscreen/css', array(), 'header') ?>
<?php themes\add_asset('jquery.form.js', 'mod: splashscreen/js', array('jquery'), 'footer') ?>



<div class="row">
    <div style="margin-top:15px;" class="span12">

        <ul class="nav nav-tabs" id="myTab">
            <li><a data-toggle="tab" href="#backgrounds">Loop Background Effect</a></li>
            <li><a data-toggle="tab" href="#daytimebkg">Day of Time Effect</a></li>
            <li><a data-toggle="tab" href="#logo">Logo</a></li>
        </ul>

        <div class="tab-content" style="overflow:visible;">

            <div class="tab-pane" id="backgrounds">

                <div style="margin:20px" class="offset5">
                    <h3>Upload the images that will be used by the loop background effect here.</h3>
                    <p> When using the "Loop Background" option, all images here will be looping on the splash screen, you can also set the time between each image in the settings section.</p>
                </div>
                <div>
                <div style="margin:20px" class="offset5">
                    {{ Form::open_for_files(URL::base().'/'.ADM_URI.'/'.'splashscreen/images_backgrounds', 'POST', array('class' => 'form-horizontal background')) }}
                    <div style="display:none">
                        {{ Form::token() }}
                        {{ Form::hidden('action', 'background') }}
                    </div> 
                    <input id="background" name="background" type="file">
                    {{ Form::close() }}
                </div>
                    <ul class="thumbnails background">
                        @if(isset($images) and !empty($images))
                        @foreach($images as $path => $image)
                        <?php $image_id = explode('.', $image); ?>
                        {{ View::make('splashscreen::backend.imagesbackgrounds.partials.image', array('path' => $path, 'image_name' => $image, 'action' => 'background'))->render() }}
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="tab-pane" id="daytimebkg">

                <div style="margin:20px" class="offset5">
                    <h3>Upload the images that will be used by the time of day effect here.</h3>
                    <p>When using the "Time of Day" option, the background image on the splash screen will automatically match the time of day and display the appropriate image. You will need to upload 4 images: (sunrise, mid-day, sunset, mid-night) named as (image1, image2, image3, image4) respectively. You must change the name of the image before uploading, don't change the image extension.</p>
                </div>

                <div>
                    {{ Form::open_for_files(URL::base().'/'.ADM_URI.'/'.'splashscreen/images_backgrounds', 'POST', array('class' => 'form-horizontal daytimebkg')) }}
                    <div style="display:none">
                        {{ Form::token() }}
                        {{ Form::hidden('action', 'daytimebkg') }}
                    </div> 
                    <input id="daytimebkg" name="daytimebkg" type="file">
                    {{ Form::close() }}

                    <ul class="thumbnails daytimebkg">
                        @if(isset($day_time_images) and !empty($day_time_images))
                        @foreach($day_time_images as $path => $image)
                        {{ View::make('splashscreen::backend.imagesbackgrounds.partials.day_time_bkgs', array('path' => $path, 'image_name' => $image, 'action' => 'daytimebkg'))->render() }}
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="tab-pane" id="logo">

                <div style="margin:20px" class="offset5">
                    <h3>Upload the logo image to be displayed in the splash screen here.</h3>
                    <p> <span class="label label-warning">WARNING</span> Your current logo will be overwritten by the new one.</p>
                </div>

                {{ Form::open_for_files(URL::base().'/'.ADM_URI.'/'.'splashscreen/images_backgrounds', 'POST', array('class' => 'form-horizontal logo')) }}
                <div style="display:none">
                    {{ Form::token() }}
                    {{ Form::hidden('action', 'logo') }}
                </div>
                <input id="logo_image" name="logo_image" type="file">
                {{ Form::close() }}

                <ul class="thumbnails logo">
                    @if(isset($logo) and !empty($logo))
                        @foreach($logo as $path => $image)
                        <?php $image_id = explode('.', $image); ?>
                        {{ View::make('splashscreen::backend.imagesbackgrounds.partials.logo', array('path' => $path, 'image_name' => $image))->render() }}
                        @endforeach
                        @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready( function()
    {
        $(function () {
            $('#myTab a:first').tab('show');
        })

        $('#logo_image').change(function() {

            $('.form-horizontal.logo').ajaxForm( 
            {
                success:    function(data) {
                    
                    // Some browsers will actually honor .val('')
                    // So I'm adding it back onto the solution
                    var file_input = $("#logo_image");
                    file_input.replaceWith( file_input.val('').clone( true ) );

                    $('.thumbnails.logo').html(data);
                }
            }).submit();

        });

        $('#background').change(function() {

            $('.form-horizontal.background').ajaxForm( 
            {
                success:    function(data) {
                    // Clean the file input
                    var b_file_input = $("#background");
                    b_file_input.replaceWith( b_file_input.val('').clone( true ) );
                    
                    $('.thumbnails.background').prepend(data);
                }
            }).submit();

            $("#background").val('');

        });

        $('#daytimebkg').change(function() {

            $('.form-horizontal.daytimebkg').ajaxForm( 
            {
                success:    function(data) {
                    // Clean the file input
                    var b_file_input = $("#daytimebkg");
                    b_file_input.replaceWith( b_file_input.val('').clone( true ) );
                    
                    $('.thumbnails.daytimebkg').prepend(data);
                }
            }).submit();

            $("#daytimebkg").val('');

        });
    });
</script>

