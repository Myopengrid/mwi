<div id="details-container">

    <fieldset style="margin-bottom:0;">
        <legend>{{ Lang::line('navigation::lang.Edit')->get(ADM_LANG) }} {{ Str::title($link->title) }}</legend>
    </fieldset>
    
    <div class="hidden" id="title-value-{{$link->id}}"></div>

    {{ Form::open(URL::base().'/'.ADM_URI.'/navigation/links/'.$link->id, 'PUT', array('id' => 'nav-create', 'class' => 'form_inputs')) }}

        <div style="display:none">
            {{ Form::token() }}

            <input type="hidden" name="link_id" value="{{$link->id}}" />
            <input type="hidden" name="group_id" value="{{$link->group_id}}" />
            <input type="hidden" name="edit_link" value="1" />
        </div>  
        
        <ul>

            <li>
                <label for="title">{{ Lang::line('navigation::lang.Title')->get(ADM_LANG) }} <span>*</span></label>
                <input type="text" name="title" value="{{ Str::title($link->title) }}" maxlength="50" class="text" />
            </li>

            <li>
                <label for="group_id">{{ Lang::line('navigation::lang.Group')->get(ADM_LANG) }}</label>
                <select name="group_id">
                    <?php foreach($navigation_group as $group): ?>
                    <?php if($link->group_id == $group->id): ?>
                    <option value="{{$group->id}}" selected="selected">{{$group->title}}</option>
                    <?php else: ?>
                    <option value="{{$group->id}}">{{ Str::title($group->title) }}</option>
                    <?php endif ?>
                    <?php endforeach ?>
                </select>
            </li>
                
            <li>
                <label for="link_type">{{ Lang::line('navigation::lang.Link Type')->get(ADM_LANG) }}</label>
                <span class="spacer-right">
                    <input type="radio" name="link_type" value="url" {{ @$link->link_type == 'url' ? 'checked="checked"' : '' }} />{{ Lang::line('navigation::lang.URL')->get(ADM_LANG) }}
                    <input type="radio" name="link_type" value="uri" {{ @$link->link_type == 'uri' ? 'checked="checked"' : '' }} />{{ Lang::line('navigation::lang.Site Link (URI)')->get(ADM_LANG) }}
                    @if(Bundle::exists('pages'))
                    <input type="radio" name="link_type" value="page" {{ @$link->link_type == 'page' ? 'checked="checked"' : '' }} />{{ Lang::line('navigation::lang.Page')->get(ADM_LANG) }}
                    @endif
                </span>
            </li>

            <li>
                <p style="{{ !empty($link->link_type) ? 'display:none' : '' }}">{{ Lang::line('navigation::lang.Please select a link type to be given more options to create your link')->get(ADM_LANG) }}</p>

                <div id="navigation-url" style="{{ @$link->link_type == 'url' ? '' : 'display:none' }}">
                    <input type="text" id="url" name="url" value="{{ @$link->link_type == 'url' ? $link->url : 'http://' }}" />
                </div>

                <div id="navigation-uri" style="{{ @$link->link_type == 'uri' ? '' : 'display:none' }}">
                    <input type="text" id="uri" name="uri" value="{{ @$link->link_type == 'uri' ? $link->uri : '' }}" />
                </div>
                @if(Bundle::exists('pages'))
                <div id="navigation-page" style="{{ @$link->link_type == 'page' ? '' : 'display:none' }}">
                    <select name="page_id">
                        <option value="">{{ Lang::line('navigation::lang.-- Select --')->get(ADM_LANG) }}</option>
                        <?php foreach ($pages as $page): ?>

                        @if(isset($link->page->id))
                        <?php if($link->page->id == $page->id): ?>
                        <option value="{{$page->id}}" selected="selected">{{ Str::title($page->title)}}</option>
                        <?php else: ?>
                        <option value="{{$page->id}}">{{ Str::title($page->title)}}</option>
                        <?php endif ?>
                        @else
                        <option value="{{$page->id}}">{{ Str::title($page->title)}}</option>
                        @endif
                        <?php endforeach ?>               
                    </select>
                </div>
                @else
                <p style="">
                    {{ Lang::line('navigation::lang.Please select a link type to be given more options to create your link')->get(ADM_LANG) }}
                </p>
                @endif
            </li>

            <li>
                <label for="target">{{ Lang::line('navigation::lang.Target')->get(ADM_LANG) }}</label>
                <select name="target">
                    <?php if($link->target == ''): ?>
                    <option value="" selected="selected">{{ Lang::line('navigation::lang.Current window (default)')->get(ADM_LANG) }}</option>
                    <option value="_blank">{{ Lang::line('navigation::lang.New window (_blank)')->get(ADM_LANG) }}</option>
                    <?php else: ?>
                    <option value="" >{{ Lang::line('navigation::lang.Current window (default)')->get(ADM_LANG) }}</option>
                    <option value="_blank" selected="selected">{{ Lang::line('navigation::lang.New window (_blank)')->get(ADM_LANG) }}</option>
                    <?php endif ?>
                    
                </select>
            </li>

            <li>
                <label for="restricted_to[]">{{ Lang::line('navigation::lang.Restricted To')->get(ADM_LANG) }}</label>
                <select name="restricted_to[]" size="3" multiple="multiple">
                    <?php $groups_allowed = array_flip(explode(',', $link->restricted_to)); ?>
                    
                    <?php //lets see if the ANY option is in this link ?>
                    <?php if(isset($groups_allowed['0'])): ?>
                        <option value="0" selected="selected">{{ Lang::line('navigation::lang.-- Any --')->get(ADM_LANG) }}</option>
                    <?php else: ?>
                        <option value="0">{{ Lang::line('navigation::lang.-- Any --')->get(ADM_LANG) }}</option>
                    <?php endif ?>
                    
                    <?php // Guests? ?>
                    <?php if(isset($groups_allowed['-1'])): ?>
                    <option selected="selected" value="-1">{{ Lang::line('navigation::lang.Guests')->get(ADM_LANG) }}</option>
                    <?php else: ?>
                    <option value="-1">{{ Lang::line('navigation::lang.Guests')->get(ADM_LANG) }}</option>
                    <?php endif; ?>

                    <?php // Authenticated? ?>
                    <?php if(isset($groups_allowed['-2'])): ?>
                    <option selected="selected" value="-2">{{ Lang::line('navigation::lang.Authenticated')->get(ADM_LANG) }}</option>
                    <?php else: ?>
                    <option value="-2">{{ Lang::line('navigation::lang.Authenticated')->get(ADM_LANG) }}</option>
                    <?php endif; ?>

                    <?php // Groups? ?>
                    <?php foreach ($groups as $group): ?>
                    <?php if(isset($groups_allowed[$group->id])): ?>
                        <option value="{{$group->id}}" selected="selected">{{$group->name}}</option>
                    <?php else: ?>
                        <option value="{{$group->id}}">{{ Str::title($group->name) }}</option>
                    <?php endif ?>
                    <?php endforeach ?>
                </select>
            </li>

            <li>
                <label for="class">{{ Lang::line('navigation::lang.Class')->get(ADM_LANG) }}</label>
                <input type="text" name="class" value="{{$link->class}}"  />
            </li>
        </ul>
        
        <div class="btn-group" style="margin-left:25px; margin-top:25px;">
            <button type="submit" name="btnAction" value="save" class="btn btn-primary"><i class="icon-ok"></i> {{ Lang::line('navigation::lang.Save')->get(ADM_LANG) }}
            </button>
            <a href="{{URL::base().'/'.ADM_URI}}/navigation" class="btn cancel">{{ Lang::line('navigation::lang.Cancel')->get(ADM_LANG) }}</a>
        </div>
    {{ Form::close() }}
</div>