<?php

class EmailConfig {
    
    /*
     * http://support.google.com/a/bin/answer.py?hl=en&answer=166852
     * 
     * Messages per day: 2000
     * Recipients per message (sent via SMTP by POP/IMAP users): 99
     * Total recipients per day: 10,000
     * External recipients per day: 3000
     * Unique recipients per day: 3000 (2000 external)
     */

    public $gmail = array(
        'host' => 'ssl://smtp.gmail.com',
        'port' => 465,
        'username' => '',
        'password' => '',
        'transport' => 'Smtp'
    );
    
    public $local = array();

}
