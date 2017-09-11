<?php
namespace framework\modules\Asset;

use framework\modules\Hook\Hook;

Class Asset
{
    /**
     * The default area where to load assets.
     *
     * @var string
     */
    protected $area = 'front';

    /**
     * Allowed areas.
     *
     * @var array
     */
    protected $allowedAreas = ['admin', 'login', 'customizer'];
    
    /**
     * Type of the asset.
     *
     * @var string
     */
    protected $type;

    /**
     * WordPress properties of an asset.
     *
     * @var array
     */
    protected $args;

    /**
     * Asset key name.
     *
     * @var string
     */
    protected $key;

    /**
     * @var \Themosis\Hook\ActionBuilder
     */
    protected $action;
    
    public function __construct($type, array $args)
    {
        $this->type = $type;
        $this->args = $this->parse($args);
        $this->key = strtolower(trim($args['handle']));
        $this->action = new Hook;
       // $this->html = $html;
       // $this->filter = $filter;
 
       $this->install();
    }

    /**
     * Parse defined asset properties.
     * 
     * @param array $args The asset properties.
     *
     * @return mixed
     */
    protected function parse(array $args)
    {
        /*
         * Parse version.
         */
        $args['version'] = $this->parseVersion($args['version']);

        /*
         * Parse mixed.
         */
        $args['mixed'] = $this->parseMixed($args['mixed']);

        return $args;
    }

    /**
     * Parse the version number.
     *
     * @param string|bool|null $version
     *
     * @return mixed
     */
    protected function parseVersion($version)
    {
        if (is_string($version)) {
            if (empty($version)) {
                // Passing empty string is equivalent to set it to null.
                return;
            }
            // Return the defined string version.
            return $version;
        } elseif (is_null($version)) {
            // Return null.
            return;
        }

        // Version can only be a string or null. If anything else, return false.
        return false;
    }

    /**
     * Parse the mixed argument.
     *
     * @param $mixed
     *
     * @return string|bool
     */
    protected function parseMixed($mixed)
    {
        if ('style' === $this->type) {
            $mixed = (is_string($mixed) && !empty($mixed)) ? $mixed : 'all';
        } elseif ('script' === $this->type) {
            $mixed = is_bool($mixed) ? $mixed : false;
        }

        return $mixed;
    }

    /**
     * Install the appropriate asset depending of its area.
     */
    public function install()
    {

        switch ($this->area) {
            // Front-end assets.
            case 'front':

                $this->action->add('wp_enqueue_scripts', [$this, 'register']);
                break;

            // WordPress admin assets.
            case 'admin':

                $action->add('admin_enqueue_scripts', [$this, 'register']);
                break;

            // Login assets.
            case 'login':

                $action->add('login_enqueue_scripts', [$this, 'register']);
                break;
            
            // Customizer assets.
            case 'customizer':
                
                $action->add('customize_preview_init', [$this, 'register_style']);
                break;
        }
    }

    /**
     * Register the asset.
     *
     * @param Asset $asset
     */
    public function register()
    {
        if ($this->type === 'script') {
            $this->registerScript();
        } else {
            $this->registerStyle();
        }
    }

    /**
     * Register a 'script' asset.
     *
     * @param Asset $asset
     */
    protected function registerScript(Asset $asset)
    {
        $args = $asset->getArgs();
        wp_enqueue_script($args['handle'], $args['path'], $args['deps'], $args['version'], $args['mixed']);

        // Add localized data for scripts.
        if (isset($args['localize']) && !empty($args['localize'])) {
            foreach ($args['localize'] as $objectName => $data) {
                wp_localize_script($args['handle'], $objectName, $data);
            }
        }

        // Pass the asset instance and register inline code.
        $this->registerInline($asset);
    }

    /**
     * Register a 'style' asset.
     *
     * @param Asset $asset
     */
    protected function registerStyle()
    {
        $args = $this->args;
        wp_enqueue_style($args['handle'], $args['path'], $args['deps'], $args['version'], $args['mixed']);
    }
}