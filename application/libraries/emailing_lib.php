<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailing_lib {

    // Codeigniter instance
    private $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
    * Sends the email to the user
    * @param receiver who gets the email
    * @param mailid id of the mail pattern
    * @param array with email data which are replaced in the pattern key is the replaced one and value is the new value
    * @return true / false
    */	
    public function emailUser(
        $receiver, 
        $mailId, 
        $data, 
        $attachments = array(), 
        $customSubject = '', 
        $readconfirmation = '', 
        $cc = null,
        $useSecondary = false,
        $user_id = null) {

        $ret = false;

        $config = Array(
            "protocol" => $this->CI->config->item('SENDER_PROTOCOL'),
            "smtp_host" => $this->CI->config->item('SENDER_HOST'),
            "smtp_port" => $this->CI->config->item('SENDER_PORT'),
            "smtp_user" => $this->CI->config->item('SENDER_EMAIL'),
            "smtp_pass" => $this->CI->config->item('SENDER_EMAIL_PASSWORD'),
            "mailtype" => "html"
        );        
        
        if ($useSecondary) {
            if ($user_id % 2 == 0) {
                $config["smtp_user"] = $this->CI->config->item('SENDER_EMAIL_SECONDARY');
                $config["smtp_pass"] = $this->CI->config->item('SENDER_EMAIL_SECONDARY_PASSWORD');
            } else {
                $config["smtp_user"] = $this->CI->config->item('SENDER_EMAIL_TERTIARY');
                $config["smtp_pass"] = $this->CI->config->item('SENDER_EMAIL_TERTIARY_PASSWORD');
            }
        }

        // when special sender is used it overrides any other senders set
        $useSpecialSender = in_array($user_id, $this->CI->config->item('SENDER_EMAIL_SPECIAL_USERS'));
        if ($useSpecialSender) {
            $config["smtp_user"] = $this->CI->config->item('SENDER_EMAIL_SPECIAL');
            $config["smtp_pass"] = $this->CI->config->item('SENDER_EMAIL_SPECIAL_PASSWORD');
        }
        
        $this->CI->load->library('email');
        $this->CI->email->clear(true);
        $this->CI->email->initialize($config);
        $this->CI->email->from($config['smtp_user'], $this->CI->config->item('SENDER_NAME'));
        $this->CI->email->to($receiver);

        if ($cc != null && is_string($cc) && strlen($cc) > 0) {
            $this->CI->email->cc($cc);
        }

        $email = $this->CI->common_model->get_mail_by_id($mailId);

        // Set custom readconfirmation
        if ($readconfirmation != '') {
            $this->CI->email->set_custom_header('Disposition-Notification-To', $readconfirmation);			
        }

        // Set custom subject if needed
        $mailsubject = $email->subject;
        if ($customSubject != '') {
            $mailsubject = $customSubject;
        }

        $this->CI->email->subject($mailsubject);

        // attach the header and footer to the message
        $header = $this->CI->common_model->get_mail_by_id($this->CI->config->item('EMAIL_HEADER'));
        $footer = $this->CI->common_model->get_mail_by_id($this->CI->config->item('EMAIL_FOOTER'));

        // replace placeholders
        $data['[logoUrl]'] = base_url() . 'images/logo.png';
        $data['[advert_text]'] = '';
        
        $this->replacePlaceholders($data, $email);
        $this->replacePlaceholders($data, $header);
        $this->replacePlaceholders($data, $footer);

        $message = $header->body . $email->body . $footer->body;
        $message_text = $header->body_text . $email->body_text . $footer->body_text;

        $this->CI->email->message($message);								
        $this->CI->email->set_alt_message($message_text);										

        // assign attachments
        foreach ($attachments as $i) {
            $this->CI->email->attach($i);
        }

        $ret = $this->CI->email->send();

        // add a record to the emails sent database
        $sentdata = array(
            "date" => date("Y-m-d H:i:s"),
            "from" => $config["smtp_user"],
            "to" => $receiver,
            "cc" => $cc,
            "subject" => $mailsubject,
            "message" => $message,
            "message_plain" => $message_text
        );
        
        if (sizeof($attachments) > 0) {
            $sentdata["attachment"] = $attachments[0];
        }
        
        if ($user_id != null) {
            $sentdata["user_id"] = $user_id;
        }
        
        $this->CI->common_model->add_sent_email($sentdata);

        // when we collect emails sent in this request, add it to collection
        if ($this->CI->session->userdata($this->CI->config->item("SESS_COLLECT_EMAILS") != null)
            && $this->CI->session->userdata($this->CI->config->item("SESS_COLLECT_EMAILS")) == $this->CI->config->item("SESS_COLLECT_EMAILS_ON")) {

            $current_collection = $this->CI->session->userdata($this->CI->config->item("SESS_COLLECTED_EMAILS")) != null
                ? $this->CI->session->userdata($this->CI->config->item("SESS_COLLECTED_EMAILS"))
                : array();

            array_push($current_collection, $sentdata);

            $this->CI->session->set_userdata($this->CI->config->item("SESS_COLLECTED_EMAILS"), $current_collection);
        }

        return $ret;		
    }

    /**
    * Emails users with their additional emails
    */
    public function emailUsersNotification($user, $mailId, $data, $attachments = array()) {
        $receiver = $user['email'];
        $additionalReceivers = $user['additional_emails'];
        $user_id = $user['id'];
        
        $emails = array();
        array_push($emails, $receiver);

        // attach extra emails
        if (strlen($additionalReceivers) > 0) {
            $mails = explode('|', $additionalReceivers);
            foreach ($mails as $i) {
                array_push($emails, $i);
            }
        }
        
        $emails = array_unique($emails);

        // keep these properties at their default values, they're here to ease readibility
        $customSubject = '';
        $readconfirmation = '';
        $cc = null;
        
        $useSecondary = false;
        
        // email it everywhere		
        foreach ($emails as $i) {
            $this->emailUser($i, $mailId, $data, $attachments, $customSubject, $readconfirmation, $cc, $useSecondary, $user_id);
        }
    }

    private function replacePlaceholders($data, $email) {
        foreach ($data as $key => $value) {
            $email->body = str_replace($key, $value, $email->body);
            
            $plainValue = strip_tags(replaceBreaks($value));
            $email->body_text = str_replace($key, $plainValue, $email->body_text);
        }
    }

}

/* End of file daz_image.php */
/* Location: ./system/application/libraries/daz_image.php */