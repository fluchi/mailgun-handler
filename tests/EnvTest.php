<?php

/*
 * This file is part of the fluchi/mailgun-handler.
 *
 * (c) Felipe Luchi <luchifelipe@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use Symfony\Component\Dotenv\Dotenv;

class EnvTest extends \PHPUnit\Framework\TestCase
{
    public function testNoEnvFile()
    {
        $this->assertTrue(file_exists(getcwd() . '/.env'), 'Create the .env file on the root of your project');
    }

    public function testEnvIndexes()
    {
        $dotenv = new Dotenv();
        $dotenv->load(getcwd() . '/.env');

        $this->assertArrayHasKey('MAILGUN_API_KEY', $_SERVER);
        $this->assertArrayHasKey('MAILGUN_DOMAIN', $_SERVER);
        $this->assertArrayHasKey('MAILGUN_SENDER', $_SERVER);

        $this->assertIsString('string', $_SERVER['MAILGUN_API_KEY']);
        $this->assertIsString('string', $_SERVER['MAILGUN_DOMAIN']);
        $this->assertIsString('string', $_SERVER['MAILGUN_SENDER']);

        if (key_exists('MAILGUN_TO', $_SERVER)) {
            $this->assertIsString('string', $_SERVER['MAILGUN_TO']);
        }
    }
}
