#!/usr/bin/env php

<?php

use danog\MadelineProto\Logger;

use League\Uri\Contracts\UriException;

/*

 * Various ways to load MadelineProto

 */

if (\file_exists('vendor/autoload.php')) {

    include 'vendor/autoload.php';

} else {

    if (!\file_exists('madeline.php')) {

        \copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');

    }

    include 'madeline.php';

}

class EventHandler extends \danog\MadelineProto\EventHandler

{

    const START = "Send me a file URL and I will download it and send it to you!\n\n".

                "Usage: `https://example.com`\n".

                "Usage: `https://example.com file name.ext`\n\n".

                "I can also rename Telegram files, just send me any file and I will rename it!\n\n".

                "Max 1.5GB, parallel upload and download powered by @MadelineProto.";

    const ADMIN = 'danogentili';

    private $states = [];

    public function onUpdateNewChannelMessage($update)

    {

        yield $this->onUpdateNewMessage($update);

    }

    public function report($message)

    {

        try {

            $this->messages->sendMessage(['peer' => self::ADMIN, 'message' => $message]);

        } catch (\Throwable $e) {

            $this->logger("While reporting: $e", Logger::FATAL_ERROR);

        }

    }

    public function onUpdateNewMessage($update)

    {

        if ($update['message']['out'] ?? false) {

            return;

        }

        if ($update['message']['_'] !== 'message') {

            return;

        }

        try {

            $peer = yield $this->getInfo($update);

            $peerId = $peer['bot_api_id'];

            $messageId = $update['message']['id'];

            if ($update['message']['message'] === '/start') {

                return $this->messages->sendMessage(['peer' => $peerId, 'message' => self::START, 'parse_mode' => 'Markdown', 'reply_to_msg_id' => $update['message']['id']]);

            }

            if (isset($update['message']['media']['_']) && $update['message']['media']['_'] !== 'messageMediaWebPage') {

                $id = yield $this->messages->sendMessage(['peer' => $peerId, 'message' => 'Give me a new name for this file: ', 'reply_to_msg_id' => $messageId])['id'];

                $this->states[$peerId] = [$update['message']['media'], $id];

                return;

            }

            if (isset($this->states[$peerId])) {

                $name = $update['message']['message'];

                list($url, $id) = $this->states[$peerId];

                unset($this->states[$peerId]);

            } else {

                $url = \explode(' ', $update['message']['message'], 2);

                $name = \trim($url[1] ?? \basename($update['message']['message']));

                $url = \trim($url[0]);

                if (!$url) {

                    return;

                }

                if (\strpos($url, 'http') !== 0) {

                    $url = "http://$url";

                }

            }

            $id = yield $this->messages->sendMessage(['peer' => $peerId, 'message' => 'Preparing...', 'reply_to_msg_id' => $messageId])['id'];

            $url = new \danog\MadelineProto\FileCallback(

                $url,

                function ($progress, $speed, $time) use ($peerId, $id) {

                    $this->logger("Upload progress: $progress%");

                    static $prev = -1;

                    $progressR = (int) ($progress / 10);

                    if ($progressR === $prev) {

                        return;

                    }

                    $prev = $progressR;

                    try {

                        yield $this->messages->editMessage(['peer' => $peerId, 'id' => $id, 'message' => "Upload progress: $progress%\nSpeed: $speed mbps\nTime elapsed since start: $time"]);

                    } catch (\danog\MadelineProto\RPCErrorException $e) {

                    }

                }

            );

            yield $this->messages->sendMedia(

                [

                    'peer' => $peerId,

                    'reply_to_msg_id' => $messageId,

                    'media' => [

                        '_' => 'inputMediaUploadedDocument',

                        'file' => $url,

                        'attributes' => [

                            ['_' => 'documentAttributeFilename', 'file_name' => $name]

                        ]

                    ],

                    'message' => 'Powered by @MadelineProto!',

                    'parse_mode' => 'Markdown'

                ]

            );

            if (\in_array($peer['type'], ['channel', 'supergroup'])) {

                yield $this->channels->deleteMessages(['channel' => $peerId, 'id' => [$id]]);

            } else {

                yield $this->messages->deleteMessages(['revoke' => true, 'id' => [$id]]);

            }

        } catch (\Throwable $e) {

            if (\strpos($e->getMessage(), 'Could not connect to URI') === false && !($e instanceof UriException)) {

                $this->report((string) $e);

                $this->logger((string) $e, \danog\MadelineProto\Logger::FATAL_ERROR);

            }

            try {

                yield $this->messages->editMessage(['peer' => $peerId, 'id' => $id, 'message' => 'Error: '.$e->getMessage()]);

            } catch (\Throwable $e) {

                $this->logger((string) $e, \danog\MadelineProto\Logger::FATAL_ERROR);

            }

        }

    }

}

$settings = [

    'logger' => [

        'logger_level' => 4

    ],

    'serialization' => [

        'serialization_interval' => 30

    ],

    'connection_settings' => [

       'media_socket_count' => [

           'min' => 20,

           'max' => 1000,

       ]

    ],

    'upload' => [

        'allow_automatic_upload' => false // IMPORTANT: for security reasons, upload by URL will still be allowed

    ]

];

$MadelineProto = new \danog\MadelineProto\API('bot.madeline', $settings);

$MadelineProto->async(true);

while (true) {

    try {

        $MadelineProto->loop(function () use ($MadelineProto) {

            yield $MadelineProto->start();

            yield $MadelineProto->setEventHandler('\EventHandler');

        });

        $MadelineProto->loop();

    } catch (\Throwable $e) {

        try {

            $MadelineProto->logger("Surfaced: $e");

            $MadelineProto->getEventHandler(['async' => false])->report("Surfaced: $e");

        } catch (\Throwable $e) {

        }

    }

}

