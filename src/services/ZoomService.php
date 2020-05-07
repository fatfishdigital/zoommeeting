<?php

    namespace fatfish\zoom\services;
    use Craft;
    use craft\base\Component;
    use fatfish\zoom\Zoom;
    use Firebase\JWT\JWT;
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\ClientException;

    /**
     *
     * @property void $_settings
     */
    class ZoomService extends Component {


        private  $_JWT_Token;
        public $ApiKey;
        public $ApiSecret;
        public $HistoryToken;
        public $response;


        /*
         * Need to optimize this.
         */
        public function init() {
            $settings= Zoom::$plugin->getSettings();
            $this->ApiKey=$settings->Apikey;
            $this->ApiSecret=$settings->ApiSecret;
            $this->HistoryToken=$settings->HistoryToken;

            if(empty($this->ApiKey) || empty($this->ApiSecret) || empty($this->HistoryToken))
            {
                Craft::$app->session->setNotice("Please configure plugins setting first");
                return;

            }


            $payload=[
                    "aud"=> null,
                    "iss"=> $this->ApiKey,
                    "exp"=> time()+3600, //by default provide 1 hrs session
                    "iat"=> time()
            ];
            $this->_JWT_Token=JWT::encode($payload,$this->ApiSecret);
        }

        public function send_request($url,$method,$body=null)
        {
            if(is_null($this->_JWT_Token) || empty($this->_JWT_Token))
            {
                 Craft::$app->session->setNotice("Please configure plugins setting first");
                 return;
            }
            $client= new Client();
            $header=['Authorization'=>'Bearer '.$this->_JWT_Token];
            $result='';
            try {

                $result=$client->request($method,$url,['headers'=>$header,'json'=>$body]);
            }
            catch (ClientException $ex)
            {

                $Message = json_decode($ex->getResponse()->getBody());
                $errormessage = new ZoomError();
                if($ex->getCode()==400)
                {
                    return ($Message->message);
                }
                if($ex->getCode()==124)
                {
                    return $ex->getMessage();
                }
                return ($ex->getMessage());
            }
            if($result->getStatusCode()==204){

                return true; //successfully deleted meetings or updated.

            }

            return json_decode($result->getBody()->getContents());
        }

    }
