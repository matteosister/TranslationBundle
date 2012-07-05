<?php
/**
 * User: matteo
 * Date: 05/07/12
 * Time: 23.35
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sensio\Bundle\GeneratorBundle\Command\Validators;

/**
 * Command to generate translation entities
 */
class GenerateEntityCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cypress:translation:generate')
            ->setDescription('Generate the translation entity for a given entity')
            ->addArgument('entity', InputArgument::REQUIRED, 'The entity to be translated');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Validators::validateEntityName($input->getArgument('entity'));
        var_dump($this->parseShortcutNotation($input->getArgument('entity')));

        $output->writeln('command <info>finished</info>');
    }

    protected function parseShortcutNotation($shortcut)
    {
        $entity = str_replace('/', '\\', $shortcut);

        if (false === $pos = strpos($entity, ':')) {
            throw new \InvalidArgumentException(sprintf('The entity name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Blog/Post)', $entity));
        }

        return array(substr($entity, 0, $pos), substr($entity, $pos + 1));
    }
}
