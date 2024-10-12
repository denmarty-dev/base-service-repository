<?php

namespace Denmarty\BaseServiceRepository\Console\Commands;

use Illuminate\Console\Command;

class MakeServiceCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'make:service {serviceName} {modelPath}';

    /**
     * @var string
     */
    protected $description = 'Create a new service and repository class';

    /**
     * @return int
     */
    public function handle(): int
    {
        $modelName   = $this->argument('modelPath');
        $serviceName = $this->argument('serviceName');

        $modelClass = "App\\Models\\$modelName";

        if (!class_exists($modelClass)) {
            $this->error("Model $modelName does not exist");
            return 1;
        }

        $modelNameExplode = explode('\\', $modelName);

        $modelPath = '';
        if (!empty($modelNameExplode[1])) {
            $modelTitle = end($modelNameExplode);
            $modelPath  = $modelNameExplode[0];
        } else {
            $modelTitle = $modelNameExplode[0];
        }

        $this->createRepositoryInterface($modelPath, $serviceName);
        $this->createRepository($modelPath, $serviceName, $modelTitle);
        $this->createService($modelPath, $serviceName);

        return 0;
    }

    /**
     * @param string $modelPath
     * @param string $serviceName
     * @return void
     */
    private function createRepositoryInterface(string $modelPath, string $serviceName): void
    {
        $template = $this->createRepositoryInterfaceTemplate($serviceName);

        $path = app_path(
            "Services" . DIRECTORY_SEPARATOR . $serviceName . DIRECTORY_SEPARATOR . 'Repository' . DIRECTORY_SEPARATOR . str_replace(
                '\\',
                DIRECTORY_SEPARATOR,
                "{$serviceName}RepositoryInterface.php"
            )
        );

        $pathDir = app_path(
            "Services" . DIRECTORY_SEPARATOR . $serviceName . DIRECTORY_SEPARATOR . 'Repository' . DIRECTORY_SEPARATOR
        );

        if (!is_dir($pathDir)) {
            mkdir($pathDir, 0755, true);
        }

        file_put_contents($path, $template);

        $this->info(str_replace(DIRECTORY_SEPARATOR, '\\', $path));
    }

    /**
     * @param string $serviceName
     * @return string
     */
    private function createRepositoryInterfaceTemplate(string $serviceName): string
    {
        $servicePath = '\\' . $serviceName;
        return
            "<?php

namespace App\Services{$servicePath}\\Repository;

use use Denmarty\BaseServiceRepository\BaseService\BaseRepositoryInterface;

interface {$serviceName}RepositoryInterface extends BaseRepositoryInterface
{

}";
    }

    /**
     * @param string $modelPath
     * @param string $serviceName
     * @param $modelTitle
     * @return void
     */
    private function createRepository(string $modelPath, string $serviceName, $modelTitle): void
    {
        $template = $this->createRepositoryTemplate($serviceName, $modelPath, $modelTitle);

        $path = app_path(
            "Services" . DIRECTORY_SEPARATOR . $serviceName . DIRECTORY_SEPARATOR . 'Repository' . DIRECTORY_SEPARATOR . str_replace(
                '\\',
                DIRECTORY_SEPARATOR,
                "{$serviceName}Repository.php"
            )
        );

        $pathDir = app_path(
            "Services" . DIRECTORY_SEPARATOR . $serviceName . DIRECTORY_SEPARATOR . 'Repository' . DIRECTORY_SEPARATOR
        );

        if (!is_dir($pathDir)) {
            mkdir($pathDir, 0755, true);
        }

        file_put_contents($path, $template);

        $this->info(str_replace(DIRECTORY_SEPARATOR, '\\', $path));
    }

    /**
     * @param string $serviceName
     * @param string $modelPath
     * @param string $modelTitle
     * @return string
     */
    private function createRepositoryTemplate(string $serviceName, string $modelPath, string $modelTitle): string
    {
        $modelPath   = $modelPath ? '\\' . $modelPath . '\\' . $modelTitle : $modelPath . '\\' . $modelTitle;
        $servicePath = '\\' . $serviceName;

        return

            "<?php

namespace App\Services{$servicePath}\\Repository;

use App\Models{$modelPath};
use Denmarty\BaseServiceRepository\BaseService\BaseRepository;

class {$serviceName}Repository extends BaseRepository implements {$serviceName}RepositoryInterface
{

    /**
     * @param {$modelTitle} \$model
     */
    public function __construct($modelTitle \$model)
    {
        parent::__construct(\$model);

        \$this->model = \$model;
    }
}

";
    }

    /**
     * @param string $modelPath
     * @param string $serviceName
     * @return void
     */
    private function createService(string $modelPath, string $serviceName): void
    {
        $template = $this->createServiceTemplate($serviceName);

        $path = app_path(
            "Services" . DIRECTORY_SEPARATOR . $serviceName . DIRECTORY_SEPARATOR . str_replace(
                '\\',
                DIRECTORY_SEPARATOR,
                "{$serviceName}Service.php"
            )
        );

        $pathDir = app_path(
            "Services" . DIRECTORY_SEPARATOR . $serviceName . DIRECTORY_SEPARATOR
        );

        if (!is_dir($pathDir)) {
            mkdir($pathDir, 0755, true);
        }

        file_put_contents($path, $template);

        $this->info(str_replace(DIRECTORY_SEPARATOR, '\\', $path));
    }

    /**
     * @param string $serviceName
     * @return string
     */
    private function createServiceTemplate(string $serviceName): string
    {
        $servicePath = '\\' . $serviceName;
        return
            "<?php

namespace App\Services{$servicePath};

use Denmarty\BaseServiceRepository\BaseService\BaseService;
use App\Services{$servicePath}\Repository\\{$serviceName}RepositoryInterface;

class {$serviceName}Service extends BaseService
{

    public function __construct({$serviceName}RepositoryInterface \$baseRepository)
    {
        \$this->baseRepository = \$baseRepository;
    }
}";
    }
}
