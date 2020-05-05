<?php

    namespace fatfish\zoom\controllers;
    use craft\web\Controller;
    use Craft;
    use fatfish\zoom\Zoom;

    class WebinarController extends Controller{

        protected $_WebinarUrl='https://api.zoom.us/v2/users/';

        public function actionIndex()
    {
            $userid=Zoom::$plugin->zoomuser->get_userid();
            $url=$this->_WebinarUrl.$userid.'/webinars';
            $webinars=Zoom::$plugin->zoomservice->send_request($url,'GET');
    } }

