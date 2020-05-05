<?php
/**
 * Zoom plugin for Craft CMS 3.x
 *
 * Video Conferencing plugin for zoom
 *
 * @link      www.fatfish.com.au
 * @copyright Copyright (c) 2020 Fatfish
 */

namespace fatfish\zoom\assetbundles\indexcpsection;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Fatfish
 * @package   Zoom
 * @since     1.0.0
 */
class IndexCPSectionAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@fatfish/zoom/assetbundles/indexcpsection/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Index.js',
            'https://unpkg.com/sweetalert/dist/sweetalert.min.js',

        ];

        $this->css = [
            'css/Index.css',
        ];

        parent::init();
    }
}
