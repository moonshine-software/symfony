# MoonShine for Symfony (Experimental)

This is an **experimental base implementation** of the [MoonShine](https://github.com/moonshine-software/moonshine) admin panel core adapted for the **Symfony framework**.

The purpose of this package is to provide a starting point for integrating MoonShine into Symfony-based projects. It’s not a production-ready solution yet and comes with **multiple limitations and missing features**. Use with caution.

## Requirements

- PHP **8.2+**
- Symfony **7.1+**
- Composer 2.5+

## Installation

```shell
mkdir packages && cd packages && git clone https://github.com/moonshine-software/symfony.git moonshine-symfony
```

Since the package is not yet published to Packagist, you’ll need to install it via the local `path` repository method:

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/moonshine-symfony",
        "options": {
            "versions": {
                "moonshine/symfony": "4.x-dev"
            },
            "symlink": true
        }
    }
]
```

Then require the package via Composer:

```shell
composer require moonshine/symfony:4.x-dev
```

Make sure the local folder packages/moonshine-symfony contains the package source.

## Contributing

We welcome contributions from the community! If you’re interested in improving this integration, feel free to open issues, submit PRs, or suggest improvements.

Let’s build something amazing together 💫