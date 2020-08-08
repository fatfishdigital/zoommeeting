<?php
/**
 * Zoom plugin for Craft CMS 3.x
 *
 * Video Conferencing plugin for zoom
 *
 * @link      www.fatfish.com.au
 * @copyright Copyright (c) 2020 Fatfish
 */

namespace fatfish\zoom\models;

use fatfish\zoom\Zoom;

use Craft;
use craft\base\Model;

/**
 * @author    Fatfish
 * @package   Zoom
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $Apikey = '';
    public $ApiSecret = '';
    public $HistoryToken = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Apikey', 'ApiSecret','HistoryToken'],'required'],


        ];
    }
}
