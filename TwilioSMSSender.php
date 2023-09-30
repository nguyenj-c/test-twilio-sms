<?php

use Twilio\Rest\Client;

class TwilioSMSSender{

    private Client $client;

    public function __construct(
        public string $sid,
        public string $token,
        public string $messagingServiceSid
    ) {
        $this->client = new Client($sid, $token);
    }

    public function send(string $to, string $message, DateTimeImmutable $now, ?string $type = null, array $images = []){
        $body = match (true){
            $type === 'scheduled' =>                 [
                "Body" => $message,
                "MessagingServiceSid" => $this->messagingServiceSid,
                "SendAt" => $now->format(DateTimeInterface::ISO8601),
                "ScheduleType" => "fixed",
                "StatusCallback" => "https://ens8kebcama.x.pipedream.net/"
            ],
            $type === 'whatsapp' => [
                "Body" => $message,
                "From" => "whatsapp:".$this->messagingServiceSid,
                "StatusCallback" => "https://ens8kebcama.x.pipedream.net/"
            ],
            $type === 'withMedia' => [
                "Body" => $message,
                "MediaUrl" => $images,
                "From" => $this->messagingServiceSid,
                "StatusCallback" => "https://ens8kebcama.x.pipedream.net/"
            ],
            $type === "shortUrl" => [
                "Body" => $message,
                "ShortenUrls" => true,
                "MessagingServiceSid" => $this->messagingServiceSid,
                "StatusCallback" => "https://ens8kebcama.x.pipedream.net/"
            ],
            default => [
                "Body" => $message,
                "From" => $this->messagingServiceSid,
                "StatusCallback" => "https://ens8kebcama.x.pipedream.net/"
            ]
        };

        if($type === 'whatsapp'){
            $to = "whatsapp:".$to;
        }

        $message = $this->client->messages
            ->create($to, // to
                $body
            );


        print($message->status . PHP_EOL);
        print($message->sid);
    }
    public function canceled(string $messageId){
        $message = $this->client->messages($messageId)
            ->update(["status" => "canceled"]);

        print($message->status. PHP_EOL);
        print($message->sid);
    }

    public function remove(string $messageId){
        $message = $this->client->messages($messageId)
            ->remove();

        print($message->status. PHP_EOL);
        print($message->sid);
    }
}