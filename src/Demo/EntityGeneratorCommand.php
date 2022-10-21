<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Demo;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command responsible to execute the entity generation to start a demo.
 */
class EntityGeneratorCommand extends Command
{
    /**
     * Manages the entity generation.
     *
     * @var EntityGenerator
     */
    private EntityGenerator $_generator;

    /**
     * EntityGeneratorCommand constructor.
     *
     * @param EntityGenerator $generator
     */
    public function __construct(EntityGenerator $generator)
    {
        $this->_generator = $generator;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setName('generate:demo')
            ->setDescription('Generating Google Review Manager.')
            ->setHelp('This command allows you to create a Google Review Manager demo.')
            ->setHidden(true);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<bg=blue;fg=white>                              </>');
        $output->writeln('<bg=blue;fg=white>  Project entities generator  </>');
        $output->writeln('<bg=blue;fg=white>                              </>');

        $progressBar = new ProgressBar($output, 100);
        $progressBar->start();

        // Generating entities.
        $this->_generator->generateProjectFixtures();
        $progressBar->finish();

        $output->writeln('');
        $output->writeln('<fg=green>Complete!</>');

        return 0;
    }
}