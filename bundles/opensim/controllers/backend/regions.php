<?php

class Opensim_Backend_Regions_Controller extends Admin_Controller {
    
    public function __construct()
    {
        parent::__construct();

        $this->data['bar'] = array(
            'title'       => Lang::line('opensim::lang.Opensim')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/opensim',
            'description' => Lang::line('opensim::lang.Provide a webinterface to manage opensim virtual worlds')->get(ADM_LANG),
        );

        $this->data['section_bar'] = array(
            Lang::line('opensim::lang.Settings')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/settings',
            Lang::line('opensim::lang.Database Settings')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/settings/database',
            Lang::line('opensim::lang.Regions')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/regions',
        );
    }

    public function get_index()
    {
        $db_is_ready = Config::get('settings::core.passes_db_settings');

        if( !(bool)$db_is_ready )
        {
            Session::flash('message_type', 'error');
            Session::flash('message', Lang::line('opensim::lang.Your opensim database needs to be configured!')->get(ADM_LANG));
            return Redirect::to(ADM_URI.'/opensim')->with($this->data);
        }
        
        $regions = DB::connection('opensim')->table('regions')
            ->left_join('UserAccounts', 'regions.owner_uuid', '=', 'UserAccounts.PrincipalID')
            ->paginate(Config::get('settings::core.records_per_page'));

        $this->data['regions'] = $regions->results;
        $this->data['pagination_links'] = $regions->links();

        $this->data['section_bar_active'] = Lang::line('opensim::lang.Regions')->get(ADM_LANG);
        return $this->theme->render('opensim::backend.regions.index', $this->data);
    }

    public function post_ajax()
    {
        $regions = DB::connection('opensim')->table('regions');
        $regions->left_join('UserAccounts', 'regions.owner_uuid', '=', 'UserAccounts.PrincipalID');
        
        $search_by = Input::get('search_by');
        $search_for = Input::get('search_for');

        if(isset($search_by) and !empty($search_by))
        {
            if(isset($search_for) and !empty($search_for))
            {
                if($search_by == 'ownerName')
                {
                    $regions->where('UserAccounts.firstName', 'LIKE', '%'.$search_for.'%');
                    $regions->or_where('UserAccounts.lastName', 'LIKE', '%'.$search_for.'%');
                }
                if($search_by == 'regionName')
                {
                    $regions->where('regions.regionName', 'LIKE', '%'.$search_for.'%');
                }
            }

        }
        
        $regions = $regions->paginate(Config::get('settings::core.records_per_page'));

        $this->data['regions'] = $regions->results;
        $this->data['pagination_links'] = $regions->links();

        return View::make('opensim::backend.regions.partials.ajax_regions_list', $this->data);
    }

    public function get_edit($region_uuid)
    {
        $this->data['section_bar'] = array(
            Lang::line('opensim::lang.Settings')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/settings',
            Lang::line('opensim::lang.Database Settings')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/settings/database',
            Lang::line('opensim::lang.Regions')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/regions',
            Lang::line('opensim::lang.View Region Details')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/regions/'.$region_uuid.'/edit',
        );
        $this->data['section_bar_active'] = Lang::line('opensim::lang.View Region Details')->get(ADM_LANG);


        if(\Opensim\UUID::is_valid($region_uuid))
        {
            $this->data['region'] = DB::connection('opensim')->table('regions')->where_uuid($region_uuid)->first();
            return $this->theme->render('opensim::backend.regions.view', $this->data);
        }
        else
        {
            $this->data['message'] = __('opensim::lang.Invalid region uuid')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/opensim/regions')->with($this->data);
        }

    }

    public function delete_destroy($region_uuid)
    {
        if(\Opensim\UUID::is_valid($region_uuid))
        {
            $affected = DB::connection('opensim')->table('regions')->where_uuid($region_uuid)->delete();
        }

        $this->data['message'] = __('opensim::lang.Region was successfully deleted')->get(ADM_LANG);
        $this->data['message_type'] = 'success';

        if(Request::ajax())
        {
            // the element that hold the user info 
            // to be removed by ajax
            $data = array(
                'flash_message'    => array(
                    'message_type' => 'success',
                    'messages'     => array(__('opensim::lang.Region was successfully deleted')->get(ADM_LANG)),
                ),
                'hide'           => array(
                    'identifier' => 'tr#'.$region_uuid,
                ),
            );
            return json_encode($data);
        }
        
        return Redirect::to(ADM_URI.'/opensim/regions')->with($this->data);
    }
}