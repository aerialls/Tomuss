<?php

/*
 * This file is part of the Tomuss application.
 *
 * (c) Julien Brochet <mewt@madalynn.eu>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Madalynn\Tomuss\Notifier;

use Madalynn\Tomuss\Note;
use Madalynn\Growl\Growl;
use Madalynn\Growl\Notification\Type;

/**
 * Notifies note via a Growl
 *
 * @author Julien Brochet <mewt@madalynn.eu>
 */
class GrowlNotifier extends Notifier
{
    protected $growl;

    protected $notification;

    protected $title;

    protected $text;

    public function __construct($title = '%name%', $text = "%note% by %author%")
    {
        $this->growl = new Growl('Tomuss');
        $this->notification = new Type('New note');
        $this->title = $title;
        $this->text = $text;

        $this->growl->addNotificationType($this->notification);
    }

    public function notify(Note $note)
    {
        $message = $this->notification->create($this->format($this->title, $note), $this->format($this->text, $note));
        $this->growl->sendNotify($message);
    }
}
