<?php

    namespace fatfish\zoom\controllers;

    use craft\helpers\DateTimeHelper;
    use craft\web\Controller;
    use Craft;
    use craft\web\View;
    use fatfish\zoom\Zoom;

    class MeetingController extends Controller {



        private $_meetingType=[
                '1'=>'Instant Meeting',
                '2'=>'Scheduled Meeting',
                '3'=>'Recurring Meeting with no fixed time',
                //'8'=>'Recurring Meeting with fixed time',

        ];
        private $_recurrance=[
          'Daily'=>'Daily',
          'Weekly'=>'Weekly',
          'Monthly'=>'Monthly',
        ];
        private $_repeat_interval=[
                'dailyInterval1'=>1,
                'dailyInterval2'=>2,
                'dailyInterval3'=>3,
                'dailyInterval4'=>4,
                'dailyInterval5'=>5,
                'dailyInterval6'=>6,
                'dailyInterval7'=>7,
                'dailyInterval8'=>8,
                'dailyInterval9'=>9,
                'dailyInterval10'=>10,
                'dailyInterval11'=>11,
                'dailyInterval12'=>12,
                'dailyInterval13'=>13,
                'dailyInterval14'=>14,
                'dailyInterval15'=>15,
        ];
        private $_users;
        private $_timezones=[];

        public function init()
        {
            parent::init();
            $all_timezone=[];
            $this->_users=Zoom::$plugin->zoomuser->get_users();
            $timezone= \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
            foreach($timezone as $tz):
                $all_timezone[$tz]=$tz;
            endforeach;
            $this->_timezones=$all_timezone;
        }


        public function actionIndex()
        {

               return $this->renderTemplate('zoom/meeting',['user'=>$this->_users,'meetingType'=>$this->_meetingType,'timezones'=>$this->_timezones,
                       'recurrance'=>$this->_recurrance,'repeat'=>$this->_repeat_interval]);
        }



        public function actionCreateMeeting()
        {

            $request = Craft::$app->getRequest();
            Zoom::$plugin->zoomeeting->set_meeting_data($request);
            $meetingcreated=Zoom::$plugin->zoomeeting->create_meeting();
            if($request->getRequiredParam('type')==="1")
            {
                return $this->renderTemplate('zoom/instantmeeting',['instantmeeting'=>$meetingcreated]);
            }
             return  $this->renderTemplate('zoom/index',['meeting'=>$meetingcreated]);

        }

        public function actionInstantMeeting()
        {
            $this->renderTemplate('zoom/instantmeeting');

        }
        /*
         * Zoom Instant meetings.
         */

        public function actionLiveMeeting()
        {

            $userid=Zoom::$plugin->zoomuser->get_userid();
            $result=Zoom::$plugin->zoomeeting->instant_meeting($userid);
            return $result;
        }

        public function actionDeleteMeeting()
        {
            if(Craft::$app->request->isAjax)
            {
                $MeetingId=Craft::$app->getRequest()->getBodyParams('data');
                $result=Zoom::$plugin->zoomeeting->delete($MeetingId["data"]);
                return $this->asJson($result);
            }
        }

        public function actionUpdateMeeting($id)
        {

                $Meeting=new \stdClass();
                $meetingDetail=Zoom::$plugin->zoomeeting->get_meeting_details($id);
                $Meeting->id=$meetingDetail->id;
                $Meeting->topic=$meetingDetail->topic;
                $Meeting->type=$meetingDetail->type;
                $Meeting->start_time=$meetingDetail->start_time;
                $Meeting->agenda=$meetingDetail->agenda;
                $Meeting->timezone=$meetingDetail->timezone;
                $Meeting->host=$meetingDetail->host_id;
                $Meeting->duration=$meetingDetail->duration;
                $this->renderTemplate('zoom/meeting',
                        [       'meetingdata'=>$Meeting,
                                'user'=>$this->_users,
                                'meetingType'=>$this->_meetingType,
                                'timezones'=>$this->_timezones
                        ]);


        }

    }
