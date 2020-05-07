<?php

    namespace fatfish\zoom\services;

    use craft\gql\base\ObjectType;
    use fatfish\zoom\Zoom;
    use yii\base\Component;
    use craft\helpers\DateTimeHelper;

    class MeetingService extends Component {

        public $Meeting='https://api.zoom.us/v2/users/';
        private $_meeting_endpoints='https://api.zoom.us/v2/users/';
        private $MeetingUrl='https://api.zoom.us/v2/meetings/';
        private $_topic;
        private $_type;
        private $_userid;
        private $_start_time;
        private $_agenda;
        private $_data;
        private $_timezone;
        private $_meetingId;
        private $_duration="45"; //by default it will provide you 45 min
        private $_recurrance_type;
        private $_repeat_interval;
        private $_weekly_days;
        private $_monthly_day;
        private $_monthly_week;
        private $_monthly_week_day;
        private $_end_date_time;





        public function list_meetings()
        {

            $url=$this->Meeting.$this->get_userid().'/meetings';
            $meetings=Zoom::$plugin->zoomservice->send_request($url,'GET');
            return $meetings;

        }

        /**
         * send post request to host meeting
         * @return response
         */
        public function create_meeting()
        {

            $url=$this->_meeting_endpoints.$this->_userid.'/meetings';
            if(!empty($this->_meetingId) && $this->_meetingId!="")
            {
                $updateurl=$this->MeetingUrl.$this->_meetingId;
                $meetings = Zoom::$plugin->zoomservice->send_request($updateurl,'PATCH',$this->get_data());
                return $meetings;
            }

            $meetings = Zoom::$plugin->zoomservice->send_request($url,'POST',$this->get_data());
            return $meetings;
        }

        /**
         * @param $request
         */
        public function set_meeting_data($request)
        {

            if(!is_null($request->getRequiredParam('meetingid')) || !empty($request->getRequiredParam('meetingid'))){
                $this->_meetingId=$request->getRequiredParam('meetingid');
            }
        $this->_topic=$request->getRequiredParam('topic');
        $this->_type=$request->getRequiredParam('type');;
        $this->_userid=$request->getRequiredParam('userid');;
        $this->_start_time=$request->getRequiredParam('start_time');;
        $this->_agenda=$request->getRequiredParam('agenda');;
        $this->_timezone=$request->getRequiredParam('timezone');
            if (!is_null($request->getRequiredParam('duration')))
            {
                $this->_duration = $request->getRequiredParam('duration');
            }
        }

        /*
         * sets data as array
         * to send
         */
        public function get_data()
        {


            $date = DateTimeHelper::toDateTime($this->_start_time);
            get_object_vars($date);
            $this->_data=[
                         "topic"=> $this->_topic,
                          "type"=> $this->_type,
                          "start_time"=> $date->format('Y-m-d\TH:i:s.u\Z'),
                          "duration"=> $this->_duration,
                          "timezone"=> $this->_timezone,
                          "agenda"=> $this->_agenda,
                    ];
                return $this->_data;
        }
        /*
         * returns user id
         */
        /**
         * @return mixed
         */
        public function get_userid()
    {
         return (zoom::$plugin->zoomuser->get_userid());
    }

        /**
         * @param $MeetingId
         * @return mixed
         */
        public function get_meeting_details($MeetingId)
    {

        $url=$this->MeetingUrl.$MeetingId;
        $result=Zoom::$plugin->zoomservice->send_request($url,'GET',null);
        return $result;

    }

    public function delete($id)
    {
        $url=$this->MeetingUrl.$id;
        $result=Zoom::$plugin->zoomservice->send_request($url,'DELETE');
        return $result;
    }

    }
