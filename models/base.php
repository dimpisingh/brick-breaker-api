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
                (' . utils::implodeArrayKeys(', ', $data) . ')
                VALUES (' . utils::implodeArrayKeys(', ', $params) . ')',
            $params
        );
    }

    public static function update ($data, $table, $update_by = [])
    {
        if (!is_array($update_by) || !count($update_by)) {
            return false;
        }
        $params = [];
        $columns = [];

        foreach ($data as $k => $value) {
            $params[':' . $k] = $value;
            $columns[] = $k . ' = :' . $k;
        }

        foreach ($update_by as $col => $value) {
            $params[':' . $col] = $value;
            $conditions[] = $col . ' = :' . $col;
        }
        $condtion = implode(' AND ', $conditions);

        return App::$db->exec('UPDATE ' . $table . ' SET ' . (implode(', ', $columns)) . ' WHERE ' . $condtion, $params);
    }
}