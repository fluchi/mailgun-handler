<?php

/*
 * This file is part of the fluchi/mailgun-handler.
 *
 * (c) Felipe Luchi <luchifelipe@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MailgunHandler;

use Monolog\Logger;
use Mailgun\Mailgun;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\MailHandler;
use Symfony\Component\Dotenv\Dotenv;

class MailgunHandler extends MailHandler
{

    /**
     * @var Mailgun
     */
    protected $mg;

    /**
     * Maingun domain
     * @var string
     */
    protected $domain;

    /**
     * Mailgun sender email
     * @var string
     */
    protected $from;

    /**
     * Mailgun receiver email
     * @var string
     */
    protected $to;

    /**
     * Message subject
     * @var string
     */
    protected $subject;

    public function __construct(string $subject, string $from = null, string $to = null, $level = Logger::ERROR, bool $bubble = true)
    {
        $dotenv = new Dotenv();
        $dotenv->load(getcwd() . '/.env');
        if(!key_exists("MAILGUN_API_KEY", $_ENV)){
            throw new \Exception('"MAILGUN_API_KEY" is not set on .env file');
        }
        $this->mg = Mailgun::create($_ENV['MAILGUN_API_KEY']);

        if(!key_exists("MAILGUN_DOMAIN", $_ENV)){
            throw new \Exception('"MAILGUN_DOMAIN" is not set on .env file');
        }

        $this->domain = $_ENV['MAILGUN_DOMAIN'];
        $this->from = $from ?? $_ENV['MAILGUN_FROM'];
        $this->to = $to ?? $_ENV['MAILGUN_TO'];
        $this->subject = $subject;
        parent::__construct($level, $bubble);
    }

    protected function send($content, array $record) : void
    {
        $f = function($item){$h = new HtmlFormatter(); return $h->format($item);};
        $this->mg->messages()->send($this->domain, [
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
            'html' => array_map($f, $record),
        ]);
    }
}
