<?php

class User {

    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();

        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if(!$user) {
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);
				// echo $user;
				if($this->find('trainees', $user)) {
					$this->_isLoggedIn = true;
				} else {
					// process logout
				}
			}
		} else {
			$this->find('trainees', $user);
		}
    }

    public function create($table, $fields = array()) {
		if(!$this->_db->insert($table, $fields)) {
			throw new Exception('There was a problem creating this account.');
		}
    }

    public function update($fields = array(), $id = null) {

		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
        }
        
        echo $id;

		if(!$this->_db->update('trainees', $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

    public function find($table, $user = null) {
		if($user) {
			// if user had a numeric email this FAILS...
			$field = (is_numeric($user)) ? 'id' : 'email'; 
			$data = $this->_db->get($table, array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		// return false;
	}
    
    public function login($table, $email = null, $password = null, $remember = false) {

        if(!$email && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($table, $email);

            if($user) {
                if($this->data()->password === Hash::make($password, $this->data()->salt)) {
                    Session::put($this->_sessionName, $this->data()->id);
                    
                    if($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

                        if(!$hashCheck->count()) {
                            $this->_db->insert('users_session', array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));

                    }

                    return true;
                }
            }
        }
        return false;
    }

    public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

    public function logout() {
		$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

    public function data() {
		return $this->_data;
    }
    
    public function isLoggedIn() {
		return $this->_isLoggedIn;
	}	

}