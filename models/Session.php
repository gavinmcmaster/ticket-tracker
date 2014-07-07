<?php
/**
 * Created by PhpStorm.
 * User: gavin
 * Date: 13/06/14
 * Time: 12:10
 */

class Session {

    const SESSION_STARTED = TRUE;
    const SESSION_NOT_STARTED = FALSE;

    // The state of the session
    private $sessionState = self::SESSION_NOT_STARTED;

    // singleton instance of the class
    private static $instance;

    private function __construct() {}

    /**
     *    Returns THE instance of 'Session'.
     *    The session is automatically initialized if it wasn't.
     *
     *    @return    object
     **/

    public static function getInstance()
    {
        if ( !isset(self::$instance))
        {
            self::$instance = new self;
        }

        self::$instance->startSession();

        return self::$instance;
    }

    /**
     *    (Re)starts the session.
     *
     *    @return    bool    TRUE if the session has been initialized, else FALSE.
     **/

    public function startSession()
    {
        if ( $this->sessionState == self::SESSION_NOT_STARTED )
        {
            $this->sessionState = session_start();
        }

        return $this->sessionState;
    }

    /**
     *    Stores data in the session.
     *    Example: $instance->foo = 'bar';
     *
     *    @param    name
     *    @param    value
     *    @return    void
     **/

    public function __set( $name , $value )
    {
        $_SESSION[$name] = $value;
    }

    /**
     *    Returns session data for the named value.
     *    Example: echo $instance->foo;
     *
     *    @param    name
     *    @return    mixed    value stored in session.
     **/

    public function __get( $name )
    {
        if ( isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
    }

    public function __isset( $name )
    {
        return isset($_SESSION[$name]);
    }


    public function __unset( $name )
    {
        unset( $_SESSION[$name] );
    }

    /**
     *    Destroys the current session.
     *
     *    @return    bool    TRUE if session successfully deleted
     **/

    public function destroy()
    {
        //echo "Session::destroy";
        if ( $this->sessionState == self::SESSION_STARTED )
        {
            $this->sessionState = !session_destroy();
            unset( $_SESSION );

            return !$this->sessionState;
        }

        return FALSE;
    }

} 