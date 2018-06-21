<?php

namespace common\models;

use Yii;
use yii\base\Model;
use adLDAP\adLDAP;
use common\models\User;

/**
 * Login form
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $ds = ldap_connect('10.0.99.33');
            $bind = @ldap_bind($ds);
            $search = ldap_search($ds, "ou=khonkaen,o=kku", "uid=$this->username");

            if (ldap_count_entries($ds, $search) != 1) {
                $this->addError('password', 'Incorrect username or password.');
            }
            $info = ldap_get_entries($ds, $search);

            //Now, try to rebind with their full dn and password.
            $bind = @ldap_bind($ds, $info[0][dn], $this->password);
            if (!$bind || !isset($bind)) {
                if (!$user) {
                    $this->addError('password', 'Incorrect username or password.');
                }
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

}
