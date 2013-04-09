<div id="details-container">

    <fieldset style="margin-bottom:0;">
        <legend>{{ Lang::line('navigation::lang.New Link')->get(ADM_LANG) }}</legend>
    </fieldset>
    
    <div class="hidden" id="title-value-{{$nav_group_id}}">{{ Lang::line('navigation::lang.New Link')->get(ADM_LANG) }}</div>
    
    {{ Form::open(URL::base().'/'.ADM_URI.'/'.'navigation/links', 'POST', array('id' => 'nav-create', 'class' => 'form_inputs')) }}

        <div style="display:none">
            {{ Form::token() }}
            <input type="hidden" name="group_id" value="{{$nav_group_id}}" />
        </div>  
        
        <ul>

            <li>
                <label for="title">{{ Lang::line('navigation::lang.Title')->get(ADM_LANG) }} <span>*</span></label>
                <input type="text" name="title" value="" maxlength="50" class="text" />
            </li>

            <li>
                <label for="link_type">{{ Lang::line('navigation::lang.Link Type')->get(ADM_LANG) }}</label>
                <div style="margin-bottom:10px;">
                    <input type="radio" name="link_type" value="url" />{{ Lang::line('navigation::lang.URL')->get(ADM_LANG) }}
                    <input type="radio" name="link_type" value="uri" />{{ Lang::line('navigation::lang.Site Link (URI)')->get(ADM_LANG) }}
                    @if(\Bundle::exists('pages'))
                    <input type="radio" name="link_type" value="page" />{{ Lang::line('navigation::lang.Page')->get(ADM_LANG) }}
                    @endif
                </div>
            </li>

            <li>
                <p>{{ Lang::line('navigation::lang.Please select a link type to be given more options to create your link')->get(ADM_LANG) }}</p>

                <div id="navigation-url" style="display:none">
                    <input type="text" id="url" name="url" value="http://" />
                </div>
    
                <div id="navigation-uri" style="display:none">
                    <input type="text" id="uri" name="uri" value="" />
                </div>
                @if(\Bundle::exists('pages'))
                <div id="navigation-page" style="display:none">
                    <select name="page_id">
                        <option value="">{{ Lang::line('navigation::lang.-- Select --')->get(ADM_LANG) }}</option>
                        @if(!empty($pages))
                        @foreach ($pages as $page)
                        <option value="{{$page->id}}">{{$page->title}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                @else
                {{ Form::hidden('page_id', 0) }}
                @endif
            </li>

            <li>
                <select name="target">
                    <option value="" selected="selected">{{ Lang::line('navigation::lang.Current window (default)')->get(ADM_LANG) }}</option>
                    <option value="_blank">{{ Lang::line('navigation::lang.New window (_blank)')->get(ADM_LANG) }}</option>
                </select>
            </li>

            <li>
                <label for="restricted_to[]">{{ Lang::line('navigation::lang.Restricted To')->get(ADM_LANG) }}</label>
                <select name="restricted_to[]" size="3" multiple="multiple">
                    <option value="0" selected="selected">{{ Lang::line('navigation::lang.-- Any --')->get(ADM_LANG) }}</option>
                    <?php foreach ($groups as $group): ?>

                    <option value="{{$group->id}}">{{ Str::title($group->name) }}</option>
                    <?php endforeach ?>
                </select>
            </li>
    
            <li>
                <label for="class">{{ Lang::line('navigation::lang.Class')->get(ADM_LANG) }}</label>
                <input type="text" name="class" value=""  />
            </li>
        </ul>

        <div class="btn-group">
            <button type="submit" name="btnAction" value="save" class="btn btn-success">
                <i class="icon-plus"></i> {{ Lang::line('navigation::lang.Create')->get(ADM_LANG) }}
            </button>                           
            <a href="{{ URL::base().'/'.ADM_URI }}/navigation" class="btn gray cancel">{{ Lang::line('navigation::lang.Cancel')->get(ADM_LANG) }}</a>
        </div>
    {{ Form::close() }}
</div>