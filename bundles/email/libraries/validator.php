<?php

namespace Email;

class Validator extends \Laravel\Validator {

    public function validate_mail_protocol($attribute, $value, $parameters)
    {
        if( ! $this->validate_required($this->attributes['mail_protocol'], $this->attributes['mail_protocol']))
        {
            $this->messages['mail_protocol'] = 'Please select an email protocol.';
            return false;
        }

        if($value == 'mail')
        {
            return true;
        }
        
        if($value == 'smtp')
        {
            $this->messages['mail_protocol'] = '';
            $passed = $this->validate_required($this->attributes['mail_smtp_host'], $this->attributes['mail_smtp_host']);
            if( !$passed )
            {
                $this->errors->add('mail_smtp_host', __('email::lang.Please provide SMTP host name')->get(ADM_LANG));
                //return false;
            }
            $passed = $this->validate_required($this->attributes['mail_smtp_pass'], $this->attributes['mail_smtp_pass']);
            if( !$passed )
            {
                $this->errors->add('mail_smtp_pass', __('email::lang.Please provide SMTP password')->get(ADM_LANG));
                //return false;
            }
            $passed = $this->validate_required($this->attributes['mail_smtp_port'], $this->attributes['mail_smtp_port']);
            if( !$passed )
            {
                $this->errors->add('mail_smtp_port', __('email::lang.Please provide SMTP port')->get(ADM_LANG));
                //return false;
            }
            $passed = $this->validate_required($this->attributes['mail_smtp_user'], $this->attributes['mail_smtp_user']);
            if( !$passed )
            {
                $this->errors->add('mail_smtp_user', __('email::lang.Please provide SMTP username')->get(ADM_LANG));
                //return false;
            }
            
            $passed = $this->validate_email($this->attributes['mail_smtp_user'], $this->attributes['mail_smtp_user']);
            if( !$passed )
            {
                $this->errors->add('mail_smtp_user', __('email::lang.SMTP username must be the full username email address')->get(ADM_LANG));
                //return false;
            }
            
            // Set the server email with same value as smtp user
            $this->attributes['server_email'] = $this->attributes['mail_smtp_user'];
            
            // We passed all checkings. We are good to pass validation
            return $passed;
        }
        
        if($value == 'sendmail')
        {
            $this->messages['mail_protocol'] = '';
            
            $passed = $this->validate_required($this->attributes['mail_sendmail_path'], $this->attributes['mail_sendmail_path']);
            if( !$passed )
            {
                $this->errors->add('mail_sendmail_path', __('email::lang.Please provide the sendmail path')->get(ADM_LANG));
            }
            
            $passed = $this->validate_required($this->attributes['mail_smtp_host'], $this->attributes['mail_smtp_host']);
            if( !$passed )
            {
                $this->errors->add('mail_smtp_host', __('email::lang.Please provide the sendmail hostname')->get(ADM_LANG));
            }

            return $passed;
        }
        
        $this->messages['mail_protocol'] = '';
        return false;
    }

    public function validate_send_email($attribute, $value, $parameters)
    {
        $this->language = ADM_LANG;
        $this->messages['send_email'] = '';
        $this->attributes['email_list'] = array();
        // check for specific users
        if( ! isset($this->attributes['only_emails']) or empty($this->attributes['only_emails']))
        {
            // there is no specific user selected
            // lets get all user using the filter
            $status = $this->attributes['status'];
            $group = $this->attributes['group'];
            //  if not isset group or status
            //  lets get all users
            $users = \Users\Model\User::select('*');

            if(isset($group) and $group != '0' and ! empty($group))
            {
                $users->where('group_id', '=', $group);
            }
            if(isset($status) and $status != '0' and ! empty($status))
            {
                $users->where('status', '=', $status);
            }

            $users = $users->get(array('email', 'avatar_first_name', 'avatar_last_name'));
            if(isset($users) and ! empty($users))
            {
                foreach ($users as $user)
                {
                    $this->attributes['email_list'][] = $user->email;
                }

            }  
        }
        else
        {
            //we got some users selected
            foreach ($this->attributes['only_emails'] as $email)
            {
               $this->attributes['email_list'][] = $email;
            }
        }
        
        // check for custom emails
        $custom_emails = $this->attributes['alt_emails'];
        if(isset($custom_emails) and !empty($custom_emails))
        {
            $email_tmp_list = explode(',', $custom_emails);
            if(is_array($email_tmp_list) and ! empty($email_tmp_list))
            {
                foreach ($email_tmp_list as $email) 
                {
                    $email = trim($email);
                    if( ! empty($email))
                    {
                        $this->attributes['email_list'][] = $email;
                    }
                }
            }
        }

        //keep just unique values
        $this->attributes['email_list'] = array_unique($this->attributes['email_list']);
        if( ! empty($this->attributes['email_list']))
        {
            //validate if all emails are valid
            foreach ($this->attributes['email_list'] as $email)
            {
                $passed = $this->validate_email($this->attributes['subject'], $email);
                if( ! $passed)
                {
                    \Session::flash('message', __('email::lang.The email :email is invalid', array('email' => $email))->get(ADM_LANG));
                    \Session::flash('message_type', 'error');
                    return false;
                }
            }
        }
        else
        {
            // we dont have any email to send
            \Session::flash('message', __('email::lang.Please provide at least one email as recepient for this message')->get(ADM_LANG));
            \Session::flash('message_type', 'error');
            return false;
        }
        
        $template_id = $this->attributes['template'];
        
        if($template_id == 0)
        {
            // This is a custom message
            // 
            //check subject
            $passed = $this->validate_required($this->attributes['subject'], $this->attributes['subject']);
            if( ! $passed)
            {
                $this->errors->add('subject', __('validation.required', array('attribute' => __('email::lang.Subject')->get(ADM_LANG)))->get(ADM_LANG));
                return false;
            }
            
            //check email type
            $passed = $this->validate_required($this->attributes['email_type'], $this->attributes['email_type']);
            if( ! $passed)
            {
                $this->errors->add('email_type', __('validation.required', array('attribute' => __('email::lang.Email Type')->get(ADM_LANG)))->get(ADM_LANG));
                return false;
            }
            
            // check for email body
            $passed = $this->validate_required($this->attributes['email_body'], $this->attributes['email_body']);
            if( ! $passed)
            {
                $this->errors->add('email_body', __('validation.required', array('attribute' => __('email::lang.Body')->get(ADM_LANG)))->get(ADM_LANG));
                return false;
            }
            
            return true;// $passed;
        }
        else
        {
            // This message is using template
            // 
            $template = \Email\Model\Template::find($template_id);
            if( ! isset($template) or empty($template) )
            {
                \Session::flash('message', __('email::lang.Selected email template is invalid')->get(ADM_LANG));
                \Session::flash('message_type', 'false');
                return false;
            }
            
            // All templates should have this information
            $this->attributes['subject']    = $template->subject;
            $this->attributes['email_type'] = $template->type;
            $this->attributes['email_body'] = $template->body;

            return true;   
        }
        return false;
    }
}