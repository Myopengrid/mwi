<?php

class Navigation_Backend_Navigation_Groups_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->data['bar'] = array(
            'title'       => Lang::line('navigation::lang.Navigation')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/navigation',
            'description' => Lang::line('navigation::lang.Manage links on navigation menus and all the navigation groups they belong to')->get(ADM_LANG),
        );

        $this->data['section_bar'] = array(
            Lang::line('navigation::lang.Navigation Groups')->get(ADM_LANG)    => URL::base().'/'.ADM_URI.'/navigation',
            Lang::line('navigation::lang.New Navigation Group')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/navigation/groups/new',
        );
    }

    public function get_index()
    {
        $this->data['navigation_groups']  = Navigation\Model\Group::with(array(
                                'links' => function($query)
                                {
                                    //$query->with('page');
                                    $query->order_by('order', 'asc');
                                }))->get();

        $this->data['section_bar_active'] = __('navigation::lang.Navigation Groups')->get(ADM_LANG);
        return $this->theme->render('navigation::backend.groups.index', $this->data);
    }

    public function get_new()
    {
        $this->data['section_bar_active'] = __('navigation::lang.New Navigation Group')->get(ADM_LANG);
        return $this->theme->render('navigation::backend.groups.new', $this->data);
    }

    public function post_create()
    {
        $rules = array(
            'title' => 'required|min:4|max:50',
            'slug'  => 'required|min:4|max:50|alpha_dash',
        );

        $validation = Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $new_group        = new Navigation\Model\Group;
            $new_group->title = Input::get('title');
            $new_group->slug  = Input::get('slug');
            $new_group->save();

            Event::fire('mwi.navigation_group_created', array($new_group));

            $this->data['message']      = Lang::line('navigation::lang.Navigation Group was successfully created')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            return Redirect::to(ADM_URI.'/navigation')->with($this->data);
        }
        else
        {
            return Redirect::to(ADM_URI.'/navigation/groups/new')
                            ->with($this->data)
                            ->with_errors($validation)
                            ->with_input();
        }
    }

    public function delete_destroy($group_id)
    {
        if(ctype_digit($group_id))
        {    
            $group = Navigation\Model\Group::find($group_id);
            if(isset($group) and !empty($group))
            {
                if( ! $group->is_core)
                {
                    $group->links()->delete();
                    $group->delete();

                    Event::fire('mwi.navigation_group_deleted', array($group));

                    $this->data['message'] = Lang::line('navigation::lang.Navigation group was successfully deleted')->get(ADM_LANG);
                    $this->data['message_type'] = 'success';
                    if (Request::ajax())
                    {
                        return 'success';
                    }
                    else
                    {
                        return Redirect::to(ADM_URI.'/navigation')->with($this->data);
                    }
                }
                else
                {
                    $this->data['message'] = Lang::line('navigation::lang.Not authorized to delete a core navigation group')->get(ADM_LANG);
                    $this->data['message_type'] = 'error';
                    if (Request::ajax())
                    {
                        return 'error';
                    }
                    else
                    {
                        return Redirect::to(ADM_URI.'/navigation')->with($this->data);
                    }
                }
            }
            else
            {
                $this->data['message'] = Lang::line('navigation::lang.Could not delete navigation group The navigation group was not found')->get(ADM_LANG);
                $this->data['message_type'] = 'error';
                if (Request::ajax())
                {
                    return 'error';
                }
                else
                {
                    return Redirect::to(ADM_URI.'/navigation')->with($this->data);
                }
            }
        }
        else
        {
            $this->data['message'] = Lang::line('navigation::lang.Invalid navigation group id')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            if (Request::ajax())
            {
                return 'error';
            }
            else
            {
                return Redirect::to(ADM_URI.'/navigation')->with($this->data);
            }
        }
    }
}