<?php
namespace framework\modules\Asset;

class Init
{
    /**
     * A list of authorized assets to add.
     *
     * @var array
     */
    protected $allowedAssets = ['script', 'style', 'js', 'css'];
    
    /**
     * Add an asset to the application.
     *
     * NOTE : By default the path is relative to one of the registered
     * paths. Make sure your asset is unique by handle and paths/url.
     * You can also pass an external url.
     *
     * @param string      $handle  The asset handle name.
     * @param string      $path    The URI to the asset or the absolute URL.
     * @param array|bool  $deps    An array with asset dependencies or false.
     * @param string      $version The version of your asset.
     * @param bool|string $mixed   Boolean if javascript file | String if stylesheet file.
     * @param string      $type    'script' or 'style'.
     *
     * @return Asset|\WP_Error
     *
     * @throws AssetException
     */
    public function add($handle, $path, $deps = [], $version = '1.0', $mixed = null, $type = '')
    {
        if (!is_string($handle) && !is_string($path)) {
            throw new AssetException('Invalid parameters for [Asset::add] method,');
        }

        // Init type.
        $t = '';

        // Group arguments.
        $args = compact('handle', 'path', 'deps', 'version', 'mixed');

        // Define the asset type.
        if (!empty($type) && in_array($type, $this->allowedAssets)) {
            $t = $type;
        }

        /*
         * Check the asset type is defined.
         */
        if (empty($t)) {
            return new \WP_Error('asset', sprintf('%s: %s. %s', __("Can't load your asset", FRAMEWORK_TEXTDOMAIN), $handle, __('If your asset has no file extension, please provide the type parameter.', FRAMEWORK_TEXTDOMAIN)));
        }

        $asset = new Asset($t, $args);

        return $asset;
    }
}