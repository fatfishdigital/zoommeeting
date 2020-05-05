<?php

    namespace fatfish\zoom\services;

    class ZoomError {

        private $_errorlist=[
                400 => 	'Bad Request Invalid/missing data',
                401 => 	'Unauthorized 	Invalid/missing credentials',
                404 =>	'Not Found 	The resource dosen’t exists, ex. invalid/non-existent user id',
                409 =>	'Conflict 	Trying to overwrite a resource, ex. when creating a user with an email that already exists',
                429 =>	'Too Many Requests 	Hit an API rate limit',
                124 =>  'Invalid Token Code',
                200=>   'Invalid api key or secret.',
        ];

        public function get_error_message($code)
        {
            return $this->_errorlist[$code];
        }

    }
