<?
class hi_score extends base {
    protected static $table = 'hi_scores';

    public function getByUser ($user_id) {
        return App::$db->getOne(
            'SELECT id, score FROM ' . self::$table . ' WHERE user_id = :user_id ORDER BY score DESC LIMIT 1',
            [':user_id' => $user_id]
        );
    }

    public function createNew ($score, $user_id) {
        $data = [
            'user_id' => $user_id,
            'score' => $score,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return self::save($data, self::$table);
    }

    public function updateRecord ($score, $user_id) {
        $data = [
            'score' => $score,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        return self::update($data, self::$table, ['user_id' => $user_id]);
    }

    public function getRating () {
        return App::$db->getAll(
            'SELECT u.fullname, u.country_code, h.score FROM hi_scores h JOIN users u ON u.id = h.user_id ORDER BY h.score DESC'
        );
    }
}