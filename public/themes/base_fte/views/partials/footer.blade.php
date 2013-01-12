<p>&copy; {{ Config::get('settings::core.site_name') }} 2012 - Powered by <a href="http://myopengrid.com">Myopengrid MWI</a></p>
<ul style="float:right;" class="footer-links">
    {{ \IoC::resolve('Menu')->get_nav('footer') }}
</ul>