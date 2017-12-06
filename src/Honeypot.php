<?php

namespace Appel\Honeypot;

use Illuminate\Support\Facades\Crypt;

class Honeypot
{
    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $auto_complete;

    /**
     * @var string
     */
    protected $hide_mode;

    /**
     * Honeypot constructor.
     */
    public function __construct()
    {
        $this->enabled       = config('honeypot.enabled');
        $this->auto_complete = config('honeypot.auto_complete');
        $this->hide_mode     = config('honeypot.hide_mode');
    }

    /**
     * Make a new honeypot and return the HTML form.
     *
     * @param $name
     * @param $time
     * @return string
     */
    public function make($name, $time)
    {
        $encrypted = $this->getEncryptedTime();
        $html = '<div id="' . $name . '_wrap" style="' . $this->getDisplayStyle() . '">' . "\r\n" .
                    '<input type="text" name="' . $name . '" id="' . $name . '" value="" autocomplete="' . $this->auto_complete . '">' . "\r\n" .
                    '<input type="text" name="' . $time . '" id="' . $time . '" value="' . $encrypted .'" autocomplete="' . $this->auto_complete . '">' . "\r\n" .
                '</div>';
        return $html;
    }

    /**
     * Set the style attribute for the containing <div>
     * 
     * @return string
     * @throws \Exception
     */
    protected function getDisplayStyle()
    {
        if ($this->hide_mode == 'hide') {
            return 'display:none';
        }
        if ($this->hide_mode == 'off-screen') {
            return 'position:absolute;right:50000px';
        }
        throw new \Exception('Honeypot display not set');
    }

    /**
     * Validate honeypot if empty.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateHoneypot($attribute, $value, $parameters)
    {
        if (!$this->enabled) {
            return true;
        }
        return $value == '';
    }

    /**
     * Validate honey time withing the time limit.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateHoneytime($attribute, $value, $parameters)
    {
        if (!$this->enabled) {
            return true;
        }

        // Get the decrypted time.
        $value = $this->decryptTime($value);

        // The current time should be greater than the time the form was built + the speed option.
        return (is_numeric($value) && time() > ($value + $parameters[0]));
    }

    /**
     * Get the encrypted time.
     *
     * @return mixed
     */
    public function getEncryptedTime()
    {
        return Crypt::encrypt(time());
    }

    /**
     * Decrypt the given time.
     *
     * @param $time
     * @return null
     */
    public function decryptTime($time)
    {
        try {
            return Crypt::decrypt($time);
        } catch (\Exception $exception) {
            return null;
        }
    }
}