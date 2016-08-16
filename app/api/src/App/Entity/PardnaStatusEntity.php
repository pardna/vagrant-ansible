<?php

namespace App\Entity;

/**
 * Class containing constants to determine which server the API client should call.
 */
class PardnaStatusEntity
{
    /**
     * Pardna Status when the user needs to set up payments
     */
    const SETTINGUP = array(
      'code' => '1',
      'decode' => 'Set up required'
    );

    /**
     * Pardna Status when the user is awaiting for others join or to set up payments
     * before the starting date of the pardna
     */
    const AWAITING = array(
      'code' => '2',
      'decode' => 'awaiting'
    );

    /**
     * Pardna Status when the user is awaiting for others to complete a certain action
     * after the starting date of the pardna has passed
     */
    const ONHOLD = array(
      'code' => '3',
      'decode' => 'onhold'
    );

    /**
     * Pardna Status when the pardna is cancelled before the pardna has started
     */
    const CANCELLED = array(
      'code' => '4',
      'decode' => 'cancelled'
    );

    /**
     * Pardna Status when the pardna is cancelled or stopped after the pardna has started
     */
    const STOPPED = array(
      'code' => '5',
      'decode' => 'stopped'
    );

    /**
     * Pardna Status for when the pardna is active
     */
    const ACTIVE = array(
      'code' => '6',
      'decode' => 'active'
    );

    /**
     * Pardna Status when the pardna has successfully ended
     */
    const ENDED = array(
      'code' => '7',
      'decode' => 'successfully ended'
    );
}
