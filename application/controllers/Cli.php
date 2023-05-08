<?php

class Cli extends MX_Controller
{
    public $CI;
	protected $cache_settings;

    public function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
		//$this->load->helper('moodlelib');
		//$this->load->model('moodle_model');
		//$this->CI->load->model('smarty_acl_model', 'smartyacl_model');

		$this->CI->load->config('smarty_acl', TRUE);
		$this->cache_settings = $this->CI->config->item('cache_settings', 'smarty_acl');
        if ($this->cache_settings['status'] === TRUE) {
            $this->CI->load->driver('cache', array('adapter' => $this->cache_settings['driver'], 'backup' => $this->cache_settings['driver_fallback']));
        }
    }

	public function index()
	{
		if (is_cli())
		{
			echo 'You can not run this via CLI';
			exit;
		}
		echo 'Okay';
	}

    public function purgecaches()
	{
        //if (is_cli())
		//{
			//$this->load->driver('cache', array('adapter' => 'redis','backup' => 'file'));
			//debugBreak();
            //if($this->cache->redis->is_supported()) {
			if ($this->cache_settings['status'] === TRUE) {
                $this->cache->clean();
				//$this->purge_all_caches();
            }
			//exit;
		//}
		echo 'Clean.';
	}

	function purge_all_caches() {
		global $CFG;
		//reset_text_filters_cache();
		$this->js_reset_all_caches();
		//theme_reset_all_caches();
	}

	function js_reset_all_caches() {
		global $CFG;
		$next = time();
		if (isset($CFG->jsrev) and $next <= $CFG->jsrev and $CFG->jsrev - $next < 60*60) {
			// This resolves problems when reset is requested repeatedly within 1s,
			// the < 1h condition prevents accidental switching to future dates
			// because we might not recover from it.
			$next = $CFG->jsrev+1;
		}
		$this->moodle_model->set_config('jsrev', $next);
	}
	
}
