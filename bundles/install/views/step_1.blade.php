
<section class="title">
    <h3>{{ __('install::lang.Step 1 - Check Requirements')->get(Session::get('adm_lang')) }}</h3>
  
    <p>{{ __('install::lang.Mwi is very easy to install and should only take a few minutes, but there are a few questions that may appear confusing if you do not have a technical background; If at any point you get stuck please ask your web hosting provider;')->get(Session::get('adm_lang')) }}</p>
    <p>{{__('install::lang.The first step in the installation process is to check whether your server supports Mwi; Most servers should be able to run it without any trouble')->get(Session::get('adm_lang'))}}.</p>

</section>

<section>
   
    <h3>{{__('install::lang.Core')->get(Session::get('adm_lang'))}}</h3>

        <table class="table table-bordered">
        </thead>
            <tr>
                <td></td>
                <td></td>
                <td>{{__('install::lang.Version')->get(Session::get('adm_lang'))}}</td>
                <td>{{__('install::lang.Status')->get(Session::get('adm_lang'))}}</td>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($requeriments) and ! empty($requeriments)): ?>
            <?php foreach ($requeriments as $extention => $requeriment): ?>
            <?php if($requeriment['type'] == 'Core'): ?>
            <tr>
                <td><h5>{{$requeriment['title']}}</h5></td>
                <td><p><?php echo $requeriment['requeriment'] ?></p></td>
                <td><span class="label label-{{ $requeriment['passed'] ? 'success' : 'warning'  }}">{{ $requeriment['version'] }}</span></td>
                    <td><span class="label label-{{ $requeriment['passed'] ? 'success' : 'error'  }}">{{ $requeriment['passed'] ? __('install::lang.OK')->get(Session::get('adm_lang')) : __('install::lang.FAIL')->get(Session::get('adm_lang')) }}</span></td>
            </tr>
            <?php endif ?>
            <?php endforeach ?>
            <?php endif ?>
        </tbody>
        </table>
   
</section>

<section>
   
    <h3>{{__('install::lang.Required')->get(Session::get('adm_lang'))}}</h3>
    
        <table class="table table-bordered">
        </thead>
            <tr>
                <td>{{__('install::lang.Extension')->get(Session::get('adm_lang'))}}</td>
                <td>{{__('install::lang.Description')->get(Session::get('adm_lang'))}}</td>
                <td>{{__('install::lang.Version')->get(Session::get('adm_lang'))}}</td>
                <td>{{__('install::lang.Status')->get(Session::get('adm_lang'))}}</td>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($requeriments) and ! empty($requeriments)): ?>
            <?php foreach ($requeriments as $extention => $requeriment): ?>
            <?php if($requeriment['type'] == 'Required'): ?>
            <tr>
                <td><h5>{{$requeriment['title']}}</h5></td>
                <td><p><?php echo $requeriment['requeriment'] ?></p></td>
                @if($requeriment['version'] == 'Not checked yet')
                <td><span class="label label-warning">{{ $requeriment['version'] }}</span></td>
                @else
               <td><span class="label label-{{ $requeriment['passed'] ? 'success' : 'warning'  }}">{{ $requeriment['version'] }}</span></td>
                @endif
                    <td><span class="label label-{{ $requeriment['passed'] ? 'success' : 'error'  }}">{{ $requeriment['passed'] ?__('install::lang.OK')->get(Session::get('adm_lang')) : __('install::lang.FAIL')->get(Session::get('adm_lang')) }}</span></td>
            </tr>
            <?php endif ?>
            <?php endforeach ?>
            <?php endif ?>
        </tbody>
        </table>
    
</section>

<section>
    <h3>{{__('install::lang.Recommended')->get(Session::get('adm_lang'))}}</h3>
   
        <table class="table table-bordered">
            <thead>
                <td>{{__('install::lang.Extension')->get(Session::get('adm_lang'))}}</td>
                <td>{{__('install::lang.Description')->get(Session::get('adm_lang'))}}</td>
                <td>{{__('install::lang.Version')->get(Session::get('adm_lang'))}}</td>
                <td>{{__('install::lang.Status')->get(Session::get('adm_lang'))}}</td>
            </thead>
            <tbody>
                <?php if(isset($requeriments) and ! empty($requeriments)): ?>
                <?php foreach ($requeriments as $extention => $requeriment): ?>
                <?php if($requeriment['type'] == 'Recomended'): ?>
                <tr>
                    <td><h5>{{$requeriment['title']}}</h5></td>
                    <td><p><?php echo $requeriment['requeriment'] ?></p></td>
                    <td><span class="label label-{{ $requeriment['passed'] ? 'success' : 'warning'  }}">{{ $requeriment['version'] }}</span></td>
                    <td><span class="label label-{{ $requeriment['passed'] ? 'success' : 'error'  }}">{{ $requeriment['passed'] ? __('install::lang.OK')->get(Session::get('adm_lang')) : __('install::lang.FAIL')->get(Session::get('adm_lang')) }}</span></td>
                </tr>
                <?php endif ?>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
</section>

<section>
   <h3>{{__('install::lang.Summary')->get(Session::get('adm_lang'))}}</h3>
  
    @if($requeriments_passed)
    <p>{{__('install::lang.Your server meets all the requirements for Mwi to run properly, go to the next step by clicking the button below')->get(Session::get('adm_lang'))}}.</p>
    <form action="{{URL::base()}}/index.php/install/step_1" id="install_frm" method="post" accept-charset="utf-8">
        <input type="hidden" value="step_1" name="installation_step">
        <input type="submit" class="btn btn-primary" value="{{__('install::lang.Step 2')->get(Session::get('adm_lang'))}}" id="next_step">
    </form>
    @else
    <p>{{__('install::lang.Your server does not meets all the requirements for Mwi to run properly, please review the list and fix the items in red that failed')->get(Session::get('adm_lang'))}}.</p>
    <a class="btn btn-warning" id="next_step" href="{{URL::base()}}/index.php/install/step_1" title="Proceed to the next step">{{__('install::lang.Try Again')->get(Session::get('adm_lang'))}}</a>
    @endif
</section>
