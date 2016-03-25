Robo Parameters Task
=====
Robo Parameters provides tasks for the Robo PHP task runner to read
and write parameters from/to files with various formats. You can
read a parameter file to configure you tasks from a central file
or write parameters to a configuration file for your own application
to support your deployment setup

Currently, we support `xml`, `ini`, `php`, `yaml` and `json` files.
The library comes with a dedicated task for Symfony parameter
files that supports the Symfony XML and YAML format
(currently no support for the Symfony PHP-configuration format).


## Installation
Simply add the library as dependency to your `composer.json` (you will mostly only require this in dev):
```
{
    "require-dev": {
        "codegyre/robo": "^0.5",
        "nordcode/robo-parameters": "^1.0"
    }
}
```
or via console:
```
composer require --dev "nordcode/robo-parameters" "^1.0"
```

If you need the YAML reader/writer you will have to add the `symfony/yaml` component, too!

## Usage
After installation you need to add the  to your RoboFile by importing the trait:
```
use Robo\Tasks;

class RoboFile extends Tasks
{
    use NordCode\RoboParameters\loadTasks;
    
    [...]
}
```

## Examples
Let's start with some examples. For detailed information on the different configuration options please see the
 reference at the end of this file.

### Basic usage
```
public function sometask()
{
    // 1. Load the variables host, port, ... from the environment
    //     variables DB_HOST, DB_PORT, ... and writes them to the file database.xml.
    // 2. If one of the variables could not be found in the environment
    //     the task will fail (this is no default)
    $this
        ->writeParameters('database.xml')
        ->loadFromEnvironment(['host', 'port', 'username', 'password', 'db_name'])
        ->envVariablePrefix('DB')
        ->failOnMissingEnvVariables()
        ->run();
}
```

### Write with reading from boilerplate
```
public function anothertask()
{
    // 1. Will load the config.yml.dist file as YAML - format needs
    //     to be set explicitly, because we cannot determine
    //     the format by the extension.
    // 2. The parameter mail_host will be read from the environment
    //     variable MAIL_HOST or fail back the mail_host value from the boilerplate file
    // 3. `env` will always be set to "production".
    // 4. The combined data will be written to config.yml
    $this
        ->writeParameters('config.yml')
        ->useBoilerplate('config.yml.dist', \NordCode\RoboParameters\Format::YAML)
        ->loadFromEnvironment(['mail_host'])
        ->set([
            'env' => 'production'
        ])
        ->run();
}
```

### Write Symfony parameters.yml
```
public function configureSymfony()
{
    // This is how you would configure your Symfony application automatically
    // 1. The app/config/parameters.yml.dist will be used as boilerplate (default)
    // 2. All the specified variables will be loaded from the environment with `SF` as prefix e.g. SF_DATABASE_USER
    //    note that this has NOTHING to do with Symfony's capabilities to load parameters from the env during runtime!
    //    The parameters specified here will be loaded and set while building the project (on deployment).
    //    So you set the environment variables in your CI tool like Jenkins, Travis, GitLab CI, ...
    // 3. Finally the secret will be set to a (stupid) random value and the combined data will be
    //    written to app/config/parameters.xml (note that the boilerplate and final output can have different formats)
    
    // please do not use this for actual secret generation!
    // actually, the secret should be stored in the environment as well
    $stupidSecret = md5(mt_rand());
    
    $this
        ->writeSymfonyParameters('app/config/parameters.xml', null, 'app/config/parameters.yml.dist')
        ->envVariablePrefix('SF')
        ->failOnMissingEnvVariables()
        ->loadFromEnvironment([
            'database_host',
            'database_port',
            'database_user',
            'database_password',
            'mailer_host',
            'mailer_user',
            'mailer_password'
        ])
        ->set('secret', $stupidSecret)
        ->run();
}
```


## `Parameters` Task Reference
A new instance can be created with `$this->writeParameters($path, $format = null)` when using the `loadTasks` trait.

### `->set(string $key, mixed $value)`, `->set(array $values)`
Set a parameter to a fixed value. Does also accept a list of key => value pairs

### `->loadFromEnvironment(array $parameter_names)`
Will attempt to load the parameter names from the environment variables.  
The parameter names will always be uppercased, e.g. loading the parameter `foo_bar` will look for `FOO_BAR` in the environment

### `->envVariablePrefix(string $prefix)`
Requires all parameters in the environment to be prefixed with the given values.  
The prefix also follows the uppercase scheme, so loading `foo` with `sf` as prefix will actually search for `SF_FOO`

### `->failOnMissingEnvVariables()`
Will make the task fail if one of the parameters set with `->loadFromEnvironment()` could not be found

### `->fileHeader(array $lines)`, `->fileHeader(string $header)`
Add a comment as header to the output file. The comment will always the correct syntax for the output format.  
This can either be a string or an array of lines

### `->useBoilerplate(string $path)`
Load all parameters from the given file

### `->overrideExisting()`
Explicitly override the parameters file if it is already existing.  
If this is not set the task will fail by default!


## `SymfonyParameters` Task Reference
The dedicated Symfony task has the same methods as the `Parameters` task.  
When using the `loadTask` trait a new instance will be created with `$this->writeSymfonyParameters()` by default
this uses the YAML format and will try to load the `app/config/parameters.yml.dist` and write the final values to
`app/config/parameters.yml`.


## The `FileConfigurable` Task
If you would like to make your own task more dynamically by loading some settings from a central file, you can use
the `FileConfigurable` trait:

```
<?php
// RoboFile.php

use Robo\Tasks;

class RoboFile extends Tasks
{
    use \NordCode\RoboParameters\FileConfigurable;

    public function __construct()
    {
        // load the configuration for the task (optionally)
        // if loading was successful you can use $this->get() in all the following tasks to receive a value
        $this->loadConfiguration('config.yml');
    }
    
    public function foo() {
        // basic usage
        $buildDir = $this->get('build_dir', 'some/default/path');
        
        // you can use the dot.notation to access array fields:
        $this->get('environment.dev.build_dir', 'build');
        // .. would be the same as ..
        $this->get('environment')['dev']['build_dir'] ?: 'build';
    }
} 
```
