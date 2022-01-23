# WordPress Interop Bundle

## Introduction

This bundle integrates [williarin/wordpress-interop](https://github.com/williarin/wordpress-interop) with Symfony.

## Installation

```bash
composer require williarin/wordpress-interop-bundle
```

## Configuration

```yaml
# config/packages/doctrine.yaml
doctrine:
    dbal:
        connections:
            my_dbal_connection:
                driver:   pdo_mysql
                url: '%env(resolve:WORDPRESS_DATABASE_URL)%'
                charset:  UTF8
```

```yaml
# config/packages/williarin_wordpress_interop.yaml
williarin_wordpress_interop:
    # default_entity_manager:  # defaults to the first defined entity manager
    entity_managers:
        my_entity_manager:
            connection: my_dbal_connection
            # tables_prefix: custom_ # defaults to 'wp_'
            
        # another_manager:
        #     connection: my_other_dbal_connection
        #     # tables_prefix: custom_
```

## Autowiring

* `Williarin\WordpressInterop\ManagerRegistryInterface` to access all your managers and repositories.
* `Williarin\WordpressInterop\EntityManagerInterface` to get the default entity manager
* `wordpress_interop.entity_manager` as an alternative to `Williarin\WordpressInterop\EntityManagerInterface`

## License

[MIT](LICENSE)

Copyright (c) 2022, William Arin
