<?php

    namespace fatfish\zoom\services;

    use Craft;
    use fatfish\zoom\Zoom;
    use yii\base\Component;


    class UserService extends Component {

        private $_Users='https://api.zoom.us/v2/users';
        private $_ListUsers=[];
        private $_userid;


        public function get_users()
        {

            $users=Zoom::$plugin->zoomservice->send_request($this->_Users,'GET');
            if(is_null($users) || empty($users))
            {
                Craft::$app->session->setError("Something Went Wrong");
                return;
            }

            foreach ($users->users as $user):
                $this->_ListUsers[$user->id]=$user->first_name.' '.$user->last_name;
                endforeach;
            return $this->_ListUsers;

        }


        public function create_users()
        {

        }

        public function get_userid()
        {


                $users=Zoom::$plugin->zoomservice->send_request($this->_Users,'GET');
                if(!is_object($users))
                {
                    return $users;
                }
                return $users->users[0]->id;

        }
        public function list_all_users()
        {
            $users=Zoom::$plugin->zoomservice->send_request($this->_Users,'GET');
            return $users;
        }

        public function save_user($userdata)
        {


            $result=Zoom::$plugin->zoomservice->send_request($this->_Users,'POST',($userdata));
            return $result;

        }

       public function update_user($userid,$data){
            $url=$this->_Users."/$userid";
           $result= Zoom::$plugin->zoomservice->send_request($url,'PATCH',$data);
           return $result;
       }

       public function delete_user($userid)
       {
            $url = $this->_Users."/$userid";
            $result=Zoom::$plugin->zoomservice->send_request($url,"DELETE");
            return $result;
       }

    }
