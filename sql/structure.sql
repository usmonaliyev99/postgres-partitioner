SELECT
    column_name,
    data_type,
    COALESCE(character_maximum_length, numeric_precision, datetime_precision) AS max_length,
    is_nullable,
    column_default,
    ordinal_position
FROM
    information_schema.columns
WHERE
    table_name = '#TABLE'
ORDER BY
    ordinal_position;
