<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $manages;
    public $user;
    public $shop;
    public $data;
    public $service;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($manages, $user, $shop, $service, $data)
    {
        $this->manages = $manages;
        $this->user = $user;
        $this->shop = $shop;
        $this->data = $data;
        $this->service = $service;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【TakeEats】お客様よりご注文がありました')
        ->from('info@take-eats.jp', 'TakeEats')
        ->text('emails.manage.thanks')
        ->with([
            'manages' => $this->manages,
            'user' => $this->user,
            'shop' => $this->shop,
            'data' => $this->data,
            'service' => $this->service,
        ]);
    }
}
