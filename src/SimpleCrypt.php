<?php

/*
 * This file is part of the dorantor/simple-crypt package.
 *
 * All credits goes to its original authors - Paragon Initiative Enterprises
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * @link https://paragonie.com/blog/2015/05/if-you-re-typing-word-mcrypt-into-your-code-you-re-doing-it-wrong
 */

namespace Dorantor;

class SimpleCrypt
{
    const METHOD = 'aes-256-cbc';

    /**
     * @var string
     */
    private $key = null;

    /**
     * SimpleCrypt constructor.
     *
     * @param string $key
     *
     * @throws \Exception
     */
    public function __construct($key)
    {
        if (mb_strlen($this->key, '8bit') !== 32) {
            throw new \Exception('Needs a 256-bit key!');
        }
        $this->key = $key;
    }

    /**
     * Encrypt given message
     *
     * @param string $message
     *
     * @throws \Exception
     * @return string
     */
    public function encrypt($message)
    {
        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = openssl_random_pseudo_bytes($ivsize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $iv . $ciphertext;
    }

    /**
     * Try to decrypt message
     *
     * @param string $message
     *
     * @throws \Exception
     * @return string|null
     */
    public function decrypt($message)
    {
        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = mb_substr($message, 0, $ivsize, '8bit');
        $ciphertext = mb_substr($message, $ivsize, null, '8bit');

        return openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }

    /**
     * Prepare encrypted message from array
     *
     * @param array $message
     *
     * @return string
     */
    public function encArray(array $message)
    {
        return $this->encrypt(json_encode($message));
    }

    /**
     * Restore previously encrypted array
     *
     * @param $message
     *
     * @return mixed
     */
    public function decArray($message)
    {
        return json_decode($this->decrypt($message), true);
    }
}