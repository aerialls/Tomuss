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

use Symfony\Component\Console\Output\OutputInterface;
use Madalynn\Tomuss\Note;

/**
 * Notifies a note via directly in the console
 *
 * @author Julien Brochet <mewt@madalynn.eu>
 */
class ConsoleNotifier extends Notifier
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * Constructor
     *
     * @param OutputInterface $output The console output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritDoc}
     */
    public function notify(Note $note)
    {
        $message = $this->format('  > <info>%note%</info> in <comment>%name%</comment> by %author%', $note);
        $this->output->writeln($message);
    }
}
