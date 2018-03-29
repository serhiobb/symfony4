<?php

/**
 * This file is part of the Symfony package.
 *
 */
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

class ExportUsersCommand extends Command
{
    protected static $defaultName = 'app:users:export';

    protected function configure()
    {
        $this
            ->setDescription('???')
            ->setHelp(<<<'EOT'
The <info>%command.name%</info> command displays information about the current Symfony project.

The <info>PHP</info> section displays important configuration that could affect your application. The values might
be different between web and CLI.

The <info>Environment</info> section displays the current environment variables managed by Symfony Dotenv. It will not
be shown if no variables were found. The values might be different between web and CLI.
EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        /** @var $kernel KernelInterface */
        $kernel = $this->getApplication()->getKernel();

        $rows = array(
            array('<info>Symfony</>'),
            new TableSeparator(),
            array('Version', Kernel::VERSION),
            array('End of maintenance', Kernel::END_OF_MAINTENANCE.(self::isExpired(Kernel::END_OF_MAINTENANCE) ? ' <error>Expired</>' : '')),
            array('End of life', Kernel::END_OF_LIFE.(self::isExpired(Kernel::END_OF_LIFE) ? ' <error>Expired</>' : '')),
            new TableSeparator(),
            array('<info>Kernel</>'),
            new TableSeparator(),
            array('Type', get_class($kernel)),
            array('Name', $kernel->getName()),
            array('Environment', $kernel->getEnvironment()),
            array('Debug', $kernel->isDebug() ? 'true' : 'false'),
            array('Charset', $kernel->getCharset()),
            array('Root directory', self::formatPath($kernel->getRootDir(), $kernel->getProjectDir())),
            array('Cache directory', self::formatPath($kernel->getCacheDir(), $kernel->getProjectDir()).' (<comment>'.self::formatFileSize($kernel->getCacheDir()).'</>)'),
            array('Log directory', self::formatPath($kernel->getLogDir(), $kernel->getProjectDir()).' (<comment>'.self::formatFileSize($kernel->getLogDir()).'</>)'),
            new TableSeparator(),
            array('<info>PHP</>'),
            new TableSeparator(),
            array('Version', PHP_VERSION),
            array('Architecture', (PHP_INT_SIZE * 8).' bits'),
            array('Intl locale', class_exists('Locale', false) && \Locale::getDefault() ? \Locale::getDefault() : 'n/a'),
            array('Timezone', date_default_timezone_get().' (<comment>'.(new \DateTime())->format(\DateTime::W3C).'</>)'),
            array('OPcache', extension_loaded('Zend OPcache') && ini_get('opcache.enable') ? 'true' : 'false'),
            array('APCu', extension_loaded('apcu') && ini_get('apc.enabled') ? 'true' : 'false'),
            array('Xdebug', extension_loaded('xdebug') ? 'true' : 'false'),
        );

        if ($dotenv = self::getDotenvVars()) {
            $rows = array_merge($rows, array(
                new TableSeparator(),
                array('<info>Environment (.env)</>'),
                new TableSeparator(),
            ), array_map(function ($value, $name) {
                return array($name, $value);
            }, $dotenv, array_keys($dotenv)));
        }

        $io->table(array(), $rows);
    }

    private static function formatPath(string $path, string $baseDir = null): string
    {
        return null !== $baseDir ? preg_replace('~^'.preg_quote($baseDir, '~').'~', '.', $path) : $path;
    }

    private static function formatFileSize(string $path): string
    {
        if (is_file($path)) {
            $size = filesize($path) ?: 0;
        } else {
            $size = 0;
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS | \RecursiveDirectoryIterator::FOLLOW_SYMLINKS)) as $file) {
                $size += $file->getSize();
            }
        }

        return Helper::formatMemory($size);
    }

    private static function isExpired(string $date): bool
    {
        $date = \DateTime::createFromFormat('m/Y', $date);

        return false !== $date && new \DateTime() > $date->modify('last day of this month 23:59:59');
    }

    private static function getDotenvVars(): array
    {
        $vars = array();
        foreach (explode(',', getenv('SYMFONY_DOTENV_VARS')) as $name) {
            if ('' !== $name && false !== $value = getenv($name)) {
                $vars[$name] = $value;
            }
        }

        return $vars;
    }
}
