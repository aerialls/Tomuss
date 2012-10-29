<?php

/*
 * This file is part of the Tomuss application.
 *
 * (c) 2012 - Julien Brochet <mewt@madalynn.eu>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Madalynn\Tomuss\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Madalynn\Tomuss\Tomuss;
use Madalynn\Tomuss\User;
use Madalynn\Tomuss\Notifier\ConsoleNotifier;

/**
 * Search command
 *
 * @author Julien Brochet <mewt@madalynn.eu>
 */
class SearchCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('search')
            ->setDefinition(array(
                new InputArgument('user-path', InputArgument::OPTIONAL, 'The user path', getenv('TOMUSS_USER_PATH') ?: getenv('HOME').'/.tomuss/user.php'),
            ))
            ->setDescription('Searchs new notes into Tomuss application')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userPath = $input->getArgument('user-path');
        if (false === file_exists($userPath)) {
            throw new \InvalidArgumentException(sprintf('Unable to find the user file (%s)', $userPath));
        }

        $user = require($userPath);
        if (!$user instanceof User) {
            throw new \InvalidArgumentException('The configuration file needs to return an User instance');
        }

        if (OutputInterface::VERBOSITY_VERBOSE === $output->getVerbosity()) {
            $user->addNotifier(new ConsoleNotifier($output));
        }

        $tomuss = new Tomuss($user);
        $tomuss->update();
    }
}
