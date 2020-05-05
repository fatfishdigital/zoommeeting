<?php

    namespace fatfish\zoom\controllers;
    use Craft;
    use craft\web\Controller;
    use fatfish\zoom\Zoom;

    class UserController extends Controller{

        private $_usertype=[

                1=>'Basic',
                2=>'Licensed',
                3=>'On-prem',
        ];
        private $_firstname;
        private $_lastname;
        private $_email;
        private $_password;
        private $_userType;
        private $_allUsers;

        public function init(){
            $users=Zoom::$plugin->zoomuser->list_all_users();
            $this->_allUsers=$users->users;
        }

        public function actionIndex()
        {

                $this->renderTemplate('zoom/user',['users'=>$this->_allUsers]);
        }

        public function actionAddUser()
        {
                    $this->renderTemplate('zoom/useradd',['usertype'=>$this->_usertype]);
        }

        public function actionSaveUser()
        {


            $request=Craft::$app->getRequest();
            $this->_firstname=$request->getRequiredParam('first_name');
            $this->_lastname=$request->getRequiredParam('last_name');
            $this->_email=$request->getRequiredParam('email');
            $this->_userType=$request->getRequiredParam('usertype');
            $this->_password=$request->getRequiredParam('password');

            if(empty($this->_firstname) || empty($this->_lastname) || empty($this->_email) || empty($this->_password))
            {

                Craft::$app->session->setError('Form field cannot be left empty');
                $this->renderTemplate('zoom/useradd',['usertype'=>$this->_usertype]);
                return;
            }
            $data=[
                    "action"=>'create',
                    "user_info"=>[
                            "email"=>$this->_email,
                            "type"=>$this->_userType,
                            "first_name"=>$this->_firstname,
                            "last_name"=>$this->_lastname,
                            "password"=>$this->_password,
                    ],
            ];
            $result=Zoom::$plugin->zoomuser->save_user($data);
            Craft::$app->session->setNotice($result);
            $this->renderTemplate('zoom/user',['users'=>$this->_allUsers]);

        }
        public function actionUpdateUser()
        {
            if(Craft::$app->request->isAjax)
            {
                $data=Craft::$app->getRequest()->getRequiredParam('data');
                $userid=$data['userid'];
                $userdata=[

                         "first_name"=> trim($data["fname"]),
                          "last_name"=> trim($data["lname"]),
                          "type"=> trim($data["usertype"]),
                ];

                $result=Zoom::$plugin->zoomuser->update_user($userid,$userdata);
                return $this->asJson($result);

            }

        }

        public function actionDeleteUser()
        {
            if(Craft::$app->request->isAjax)
            {
                $userid=Craft::$app->getRequest()->getBodyParams('data');
                $result=Zoom::$plugin->zoomuser->delete_user($userid["data"]);
                return $this->asJson($result);


            }
        }


    }
