<section>
    <h3> {{__('install::lang.Step 3 - Set Permissions')->get(Session::get('adm_lang')) }}</h3>

    <p>{{__('install::lang.Before Mwi can be installed you need to make sure that certain files and folders are writeable, these files and folders are listed below; Make sure any subfolders have the correct permissions too !')->get(Session::get('adm_lang'))}}</p>

    <h3>{{__('install::lang.Folder Permissions')->get(Session::get('adm_lang'))}}</h3>

        <?php foreach($permissions['directories'] as $path => $pass): ?>
        <p><code>{{$path}} <span class="label label-{{ $pass ? 'success' : 'error'}}"> {{ $pass ? __('install::lang.Writable')->get(Session::get('adm_lang')) : __('install::lang.Not Writable')->get(Session::get('adm_lang')) }}</code></span></p>
        <?php endforeach ?>
    
    <h3>{{__('install::lang.File Permissions')->get(Session::get('adm_lang'))}}</h3>

    <p>{{__('install::lang.The CHMOD values of the following file must be changed to 666; Its very important to change the file permissions of the config file <em>before</em> continuing with the installation')->get(Session::get('adm_lang'))}}</p>

    
        <?php foreach($permissions['files'] as $path => $pass): ?>
        <p><code>{{$path}} <span class="label label-{{ $pass ? 'success' : 'error'}}"> {{ $pass ? __('install::lang.Writable')->get(Session::get('adm_lang')) : __('install::lang.Not Writable')->get(Session::get('adm_lang')) }}</span></code></p>
        <?php endforeach ?>
    
    <?php if( ! $all_passed): ?>
    <p>
        <a style="display: none;" href="#" id="show-commands">+ {{__('install::lang.Show Commands')->get(Session::get('adm_lang'))}}</a>
        <a href="#" id="hide-commands" style="">- {{__('install::lang.Hide Commands')->get(Session::get('adm_lang'))}}</a>
    </p>

    <textarea id="commands" style="margin: 0px 0px 10px 10px; width: 98%; background-color: rgb(17, 17, 17); color: limegreen; padding: 0.5em;" rows="7">
    <?php foreach($permissions['directories'] as $path => $pass): ?>
    <?php if(!$pass):?>
    sudo chmod -R 777 {{ $path."\n" }}
    <?php endif ?>
    <?php endforeach ?>
    <?php foreach($permissions['files'] as $path => $pass): ?>
    <?php if(!$pass):?>
    sudo chmod 666 {{ $path."\n" }}
    <?php endif ?>
    <?php endforeach ?>
    </textarea>

    <br/>
    <a class="btn orange" id="next_step" href="{{URL::base()}}/index.php/install/step_3">{{__('install::lang.Try Again')->get(Session::get('adm_lang'))}}</a>
    <?php else: ?>
    <form action="{{URL::base()}}/index.php/install/step_3" id="install_frm" method="post" accept-charset="utf-8">
        <input type="hidden" value="step_1" name="installation_step">
        <input type="submit" class="btn btn-primary" value="{{__('install::lang.Step 4')->get(Session::get('adm_lang'))}}" id="next_step">
    </form>
    <?php endif ?>
</section>
<script>
$(function(){
    $('#show-commands').click(function(){
        $(this).hide();
        $('#hide-commands').show();

        $('#commands').slideDown('slow');

        return false;
    });

    $('#hide-commands').click(function(){
        $(this).hide();
        $('#show-commands').show();

        $('#commands').slideUp('slow');

        return false;
    });
});
</script>