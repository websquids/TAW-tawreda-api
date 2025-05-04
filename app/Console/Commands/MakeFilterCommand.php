<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFilterCommand extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'make:filter {name : The name of the filter} {--m|model= : The model associated with the filter}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create a new filter class';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle() {
    $name = $this->argument('name');
    $model = $this->option('model');

    if (!$model) {
      $this->error('The --model option is required!');
      return;
    }

    $stub = $this->getStub();
    $stub = str_replace('{{FilterName}}', $name, $stub);
    $stub = str_replace('{{Model}}', $model, $stub);

    $path = app_path("Filters/{$name}.php");

    if (File::exists($path)) {
      $this->error("Filter {$name} already exists!");
      return;
    }

    File::ensureDirectoryExists(app_path('Filters'));
    File::put($path, $stub);

    $this->info("Filter {$name} created successfully.");
  }

  /**
   * Get the stub file for the generator.
   *
   * @return string
   */
  protected function getStub() {
    return <<<'EOD'
<?php

namespace App\Filters;

use App\Models\{{Model}};

class {{FilterName}} extends BaseFilter {
    function __construct() {
        parent::__construct({{Model}}::class);
    }
}
EOD;
  }
}
