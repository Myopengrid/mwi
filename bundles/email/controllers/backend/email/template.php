<?php 

class Email_Backend_Email_Template_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->data['section_bar'] = array(
            __('email::lang.Settings')->get(ADM_LANG)          => URL::base().'/'.ADM_URI.'/email',
            __('email::lang.Send Email')->get(ADM_LANG)        => URL::base().'/'.ADM_URI.'/email/new',
            __('email::lang.Email Templates')->get(ADM_LANG)   => URL::base().'/'.ADM_URI.'/email/template',
            __('email::lang.Add Template')->get(ADM_LANG)      => URL::base().'/'.ADM_URI.'/email/template/new',
        );

        $this->data['bar'] = array(
            'title'       => __('email::lang.Email')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/email',
            'description' => __('email::lang.Let users manage email module settings and create email templates')->get(ADM_LANG),
        );
    }

    public function get_index()
    {
        $this->data['custom_templates'] = Email\Model\Template::all();
        
        $this->data['section_bar_active'] = __('email::lang.Email Templates')->get(ADM_LANG);

        return $this->theme->render('email::template.index', $this->data);
    }

    public function get_new($template_id = null)
    {
        if(is_null($template_id))
        {
            $this->data['section_bar_active'] = __('email::lang.Add Template')->get(ADM_LANG);
            return $this->theme->render('email::template.new', $this->data);
        }
        else
        {
            if(ctype_digit($template_id))
            {
                $template_info = Email\Model\Template::find($template_id);
                if( !is_null($template_info))
                {
                    $this->data['template'] = $template_info;
                    $this->data['section_bar_active'] = __('email::lang.Add Template')->get(ADM_LANG);
                    return $this->theme->render('email::template.copy', $this->data);
                } 
                else
                {
                    $this->data['message']      = __('email::lang.Cannot find template to clone')->get(ADM_LANG);
                    $this->data['message_type'] = 'error';
                    return Reditect::back()->with($this->data);
                }
            }
            else
            {
                $this->data['message']      = __('email::lang.Cannot clone Invalid email template id')->get(ADM_LANG);
                $this->data['message_type'] = 'error';
                return Reditect::back()->with($this->data);
            }

        }
    }

    public function post_create()
    {
        $rules = array(
            'name'          => 'required|max:100',
            'slug'          => 'required|max:100|alpha_dash|unique:email_templates',
            'lang'          => 'required|min:2',
            'subject'       => 'required|max:255',
            'body'          => 'required',
        );

        $validation = Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $template = new Email\Model\Template;
            $template->name        = Input::get('name');
            $template->slug        = Input::get('slug');
            $template->lang        = Input::get('lang');
            $template->description = Input::get('description');
            $template->subject     = Input::get('subject');
            $template->body        = Input::get('body');
            $template->save();
            
            Event::fire('mwi.email_template_created', array($template));

            $this->data['message']      = __('email::lang.New E-mail template successfully created')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            return Redirect::to(ADM_URI.'/email/template')->with($this->data);
        }
        else
        {
            return Redirect::back()
                            ->with_input()
                            ->with_errors($validation);
        }
    }

    public function get_edit($template_id)
    {
        if( ! ctype_digit($template_id))
        {
            $this->data['message'] = __('email::lang.Invalid template id')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::back()->with($this->data);
        }
        
        $template = Email\Model\Template::find($template_id);
        
        if( ! isset($template) or empty($template))
        {
            $this->data['message'] = __('email::lang.Could not find email template to edit')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::back()->with($this->data);
        }

        $this->data['template'] = $template;

        $this->data['section_bar'] = array(
            __('email::lang.Settings')->get(ADM_LANG)          => URL::base().'/'.ADM_URI.'/email/settings',
            __('email::lang.Send Email')->get(ADM_LANG)        => URL::base().'/'.ADM_URI.'/email/new',
            __('email::lang.Email Templates')->get(ADM_LANG)   => URL::base().'/'.ADM_URI.'/email/template',
            __('email::lang.Add Template')->get(ADM_LANG)      => URL::base().'/'.ADM_URI.'/email/template/new',
            __('email::lang.Edit Template')->get(ADM_LANG)     => URL::base().'/'.ADM_URI.'/email/template/'.$template_id. '/edit',
        );

        $this->data['section_bar_active'] = __('email::lang.Edit Template')->get(ADM_LANG);
        return $this->theme->render('email::template.edit', $this->data);
    }

    public function put_update($template_id)
    {
        if( ! ctype_digit($template_id))
        {
            $this->data['message']      = __('email::lang.Invalid template id')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::back()->with($this->data);
        }
        
        $template = Email\Model\Template::find($template_id);
        
        if( ! isset($template) or empty($template))
        {
            $this->data['message']      = __('email::lang.Could not find email template to edit')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::back()->with($this->data);
        }

        $rules = array(
            'name'        => 'required|max:255',
            'description' => 'required|max:255',
            'subject'     => 'required|max:255',
            'body'        => 'required',
        );

        $validation = Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $template->name        = Input::get('name');
            $template->description = Input::get('description');
            $template->subject     = Input::get('subject');
            $template->body        = Input::get('body');
            $template->save();

            Event::fire('mwi.email_template_updated', array($template));

            $this->data['message']      = __('email::lang.Email template successfully updated')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            return Redirect::to(ADM_URI.'/email/template')->with($this->data);
        }
        else
        {
            return Redirect::back()
                            ->with_input()
                            ->with_errors($validation);
        }
    }

    public function delete_destroy($template_id)
    {
        $templates_ids = Input::get('action_to');
        
        if(isset($templates_ids) and !empty($templates_ids) and $template_id == '0')
        {
            $templates = Email\Model\Template::where_in('id', Input::get('action_to'))
                                                ->where('is_core', '=', 0)
                                                ->delete();

            Event::fire('mwi.email_template_delete', array($templates));
            $this->data['message'] = __('email::lang.E-mail template(s) were successfully deleted')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
        }
        else
        {
            if(ctype_digit($template_id) and $template_id != 0)
            {
                $template = Email\Model\Template::find($template_id)->where('is_core', '=', 0);
                $template->delete();

                Event::fire('mwi.email_template_delete', array($template));
                $this->data['message'] = __('email::lang.E-mail template was successfully deleted')->get(ADM_LANG);
                $this->data['message_type'] = 'success';
            }
            else
            {
                if($template_id == 0)
                {
                    $this->data['message'] = __('email::lang.Please select a template to delete')->get(ADM_LANG);
                }
                else
                {
                    $this->data['message'] = __('email::lang.Invalid template id')->get(ADM_LANG);
                }
                
                $this->data['message_type'] = 'error';
            }
        }

        if (Request::ajax())
        {
            $json_response = json_encode(array(
                'success'     => $this->data['message_type'],
                'message'     => $this->data['message'],
                'redirect_to' => ''
            ));
            
            return $json_response;
        }
        else
        {
            return Redirect::back()->with($this->data);
        }
    }

    // TODO
    public function get_preview_template($template_id)
    {
        if( ! ctype_digit($template_id))
        {
            return '<h1>'.__('email::lang.Invalid template id')->get(ADM_LANG).'</h1>';
        }
        
        $template = Email\Template::find($template_id);
        
        if( ! isset($template) or empty($template))
        {
            return '<h1>'.__('email::lang.Could not find email template to preview')->get(ADM_LANG).'</h1>';
        }

        return $template->body;
    }
    
}