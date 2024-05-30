# postgres-partitioner

Postgres Partitioner is a PHP-based tool that helps to generate partition tables in PostgreSQL using an existing table.

It currently supports range partitioning based on a specified column.

## Features

- Automatically create range partitions for a specified table and column.
- Easy configuration via a configuration file.
- Simple command-line interface for executing partitioning tasks.

## Requirements

- PHP 7.4 or higher
- Composer
- PostgreSQL
- PDO extension for PostgreSQL

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/usmonaliyev99/postgres-partitioner.git
    cd postgres-partitioner
    ```

2. Install dependencies using Composer:

    ```bash
    composer install
    ```

3. Create and configure the database credentials in `.env`:

    ```dotenv
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_NAME=
    DB_USER=
    DB_PASSWORD=
    ```

## Usage

The main script for partitioning is `bin/range.php`.

You can run it from the terminal as follows:

```bash
./bin/range.php <table_name> <column_name>
```

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.


## License

This project is licensed under the MIT License. See the [LICENSE]() file for more details.
