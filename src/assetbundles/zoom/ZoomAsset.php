<?php
/**
 * Zoom plugin for Craft CMS 3.x
 *
 * Video Conferencing plugin for zoom
 *
 * @link      www.fatfish.com.au
 * @copyright Copyright (c) 2020 Fatfish
 */

namespace fatfish\zoom\assetbundles\zoom;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Fatfish
 * @package   Zoom
 * @since     1.0.0
 */
class ZoomAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@fatfish/zoom/assetbundles/zoom/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Zoom.js',
        ];

        $this->css = [
            'css/Zoom.css',
        ];

        parent::init();
    }
}
