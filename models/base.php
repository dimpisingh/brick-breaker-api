<?

class base {
    protected static $table;

    protected static function save($data, $table)
    {
        $params = [];
        foreach ($data as $k => $item) {
            $params[':' . $k] = $item;
        }
        return App::$db->create(
            'INSERT INTO ' . $table . ' 
                (' . utils::implode_array_keys(', ', $data) . ')
                VALUES (' . utils::implode_array_keys(', ', $params) . ')',
            $params
        );
    }

    public static function update ($data, $id)
    {
        $params = [
            ':id' => $id
        ];
        foreach ($data as $k => $value) {
            $params[':' . $k] = $value;
            $columns[] = $k . ' = :' . $k;
        }
        return App::$db->exec('UPDATE ' . self::$table . ' SET ' . (implode(', ', $columns)) . ' WHERE id = :id', $params);
    }
}