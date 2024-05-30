# postgres-partitioner

Postgres Partitioner is a PHP-based tool that helps to generate partition tables in PostgreSQL using an existing table.

It currently supports range partitioning based on a specified column.

## Explanation

![Image](https://marekhudyma.com/assets/2018-08-01-postgreSQL-10-partitioning/horizontal.png)

## Features

- Automatically create range partitions for a specified table and column.
- Easy configuration via a configuration file.
- Simple command-line interface for executing partitioning tasks.

## Questions

- [Documentation: 16: 5.11. Table Partitioning](https://www.postgresql.org/docs/current/ddl-partitioning.html)
- [How to use table partitioning to scale PostgreSQL - EDB](https://www.enterprisedb.com/postgres-tutorials/how-use-table-partitioning-scale-postgresql)
- [What is partitioning?](https://www.postgresql.fastware.com/postgresql-insider-prt-ove)

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
