<?php
namespace App\Console\Commands\Generator;

use App\Console\Command;
use App\Console\Traits\Generatable;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerGeneratorCommand extends Command
{
    use Generatable;

    protected $command = 'make:controller';

    protected $description = 'Generate a controller.';

    public function handle(InputInterface $input, OutputInterface $output)
    {
        $controllerBase = __DIR__ . '/../../../Http/Controllers';
        $path = $controllerBase . '/';
        $namespace = 'App\\Http\\Controllers';

        $fileParts = explode('\\', trim($this->argument('name')));

        $fileName = array_pop($fileParts);

        $cleanPath = implode('/', $fileParts);

        if (count($fileParts) >= 1) {
            $path = $path . $cleanPath;

            $namespace = $namespace . '\\' . str_replace('/', '\\', $cleanPath);

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }

        $target = $path . '/' . $fileName . '.php';

        if (file_exists($target)) {
            return $this->error('Controller already exists!');
        }

        $stub = $this->generateStub('controller', [
            'DummyClass' => $fileName,
            'DummyNamespace' => $namespace,
        ]);

        file_put_contents($target, $stub);

        return $this->info('Controller generated!');
    }

    protected function arguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller to generate.']
        ];
    }

    protected function options()
    {
        return [

        ];
    }
}
