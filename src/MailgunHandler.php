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

    public function __construct(string $subject, string $to = null, $level = Logger::WARNING, bool $bubble = true)
    {
        $dotenv = new Dotenv();
        $dotenv->load(getcwd() . '/.env');
        $this->mg = Mailgun::create($_ENV['MAILGUN_API_KEY']);
        $this->domain = $_ENV['MAILGUN_DOMAIN'];
        $this->from = $_ENV['MAILGUN_SENDER'];
        $this->to = $to ?? $_ENV['MAILGUN_TO'];
        $this->subject = $subject;
        parent::__construct($level, $bubble);
    }

    protected function send($content, array $record) : void
    {
        $f = function ($item) {
            $h = new HtmlFormatter();
            return $h->format($item);
        };
        $this->mg->messages()->send($this->domain, [
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
            'html' => array_map($f, $record),
        ]);
    }
}
