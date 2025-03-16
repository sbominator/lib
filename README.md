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

## Contributing

## Committing

Our commit messages use a simplified form of [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/). This is how our automated release system knows what a given commit means.

```md
<type>: <description>

[body]
```

### Commit type prefixes

The `type` can be any of `feat`, `fix` or `chore`.

The prefix is used to calculate the semver release level, and the section of the release notes to place the commit message in.

| **type**   | When to Use                          | Release Level | Release Note Section  |
| ---------- | ----------------------------------- | ------------- | --------------------   |
| feat       | A feature has been added            | `minor`       | **Added**           |
| fix        | A bug has been patched              | `patch`       | **Fixed**          |
| deps        | Changes to the dependencies          | `patch`       | **Changed**          |
| perf       | Performance improvements            | none          | **Performance Improvements**   |
| chore      | Any changes that aren't user-facing | none          | none                   |
| docs       | Documentation updates               | none          | none                   |
| style      | Code style and formatting changes   | none          | none                   |
| refactor   | Code refactoring                    | none          | none                   |                |
| test       | Adding tests or test-related changes| none          | none                   |
| build      | Build system or tooling changes     | none          | none                   |
| ci         | Continuous Integration/Deployment    | none          | none                   |
| revert     | Reverting a previous commit          | none          | none                   |
| wip        | Work in progress (temporary)        | none          | none                   |

