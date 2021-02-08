<?php


namespace BrandonlinU\CvsUpdaterBundler\Tests\App;


use BrandonlinU\CvsUpdaterBundler\BrandonlinUCvsUpdaterBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', false);
    }

    public function getCacheDir(): string
    {
        return sprintf('%s/var/cache', $this->getTempDir());
    }

    public function getLogDir(): string
    {
        return sprintf('%s/var/log', $this->getTempDir());
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new BrandonlinUCvsUpdaterBundle(),
        ];
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(sprintf('%s/config/routes.yml', $this->getProjectDir()));
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', ['test' => true]);
    }

    private function getTempDir(): string
    {
        return sprintf('%s/brandonlinu-cvs-updater-bundle', sys_get_temp_dir());
    }
}