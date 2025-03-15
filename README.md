# SBOMinator

This is the SBOMinator project started during the "Securing the Supply Chain of OSS" project at Cloudfest Hackathon 2025.

## Development
Note: There is a lot of stuff here to help aid in development of the library.
This will be removed and/or extraced out into their own packages (e.g. `sbominator-cli`) before it will be merged into the `main` branch.

### Without ddev
Make sure you have PHP 8.4 and Composer installed. You can install Composer by following the instructions at https://getcomposer.org/download/.
After checking out the project, you can run `composer install` to install the dependencies.

### With ddev
For easier development across machines, you can use ddev to run the project locally. You can install ddev by following the instructions at https://ddev.readthedocs.io/en/stable/#installation.
After checking out the project, you can run `ddev start` to start the project. Use `ddev ssh` to get a shell in the running container. You can then run `composer install` in the shell to install the dependencies.