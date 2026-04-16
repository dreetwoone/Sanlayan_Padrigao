<?php
class SessionModel extends Model {

    public function countByUser(int $id): int {
        $s = $this->db->prepare("SELECT COUNT(*) FROM sessions WHERE user_id=?");
        $s->execute([$id]); return (int)$s->fetchColumn();
    }
    public function totalCaloriesByUser(int $id): int {
        $s = $this->db->prepare("SELECT COALESCE(SUM(calories),0) FROM sessions WHERE user_id=?");
        $s->execute([$id]); return (int)$s->fetchColumn();
    }
    public function activeDaysByUser(int $id): int {
        $s = $this->db->prepare("SELECT COUNT(DISTINCT session_date) FROM sessions WHERE user_id=?");
        $s->execute([$id]); return (int)$s->fetchColumn();
    }
    public function avgDurationByUser(int $id): int {
        $s = $this->db->prepare("SELECT COALESCE(ROUND(AVG(duration)),0) FROM sessions WHERE user_id=?");
        $s->execute([$id]); return (int)$s->fetchColumn();
    }
    public function weeklyByUser(int $id): array {
        $s = $this->db->prepare("
            SELECT WEEK(session_date) AS week_num, COUNT(*) AS total
            FROM sessions WHERE user_id=? AND session_date>=DATE_SUB(CURDATE(),INTERVAL 7 WEEK)
            GROUP BY WEEK(session_date) ORDER BY week_num ASC LIMIT 7
        ");
        $s->execute([$id]); return $s->fetchAll();
    }
    public function recordsByUser(int $id): array {
        $s = $this->db->prepare("
            SELECT w.name, w.icon, MAX(s.duration) AS best_duration, COUNT(*) AS times_done
            FROM sessions s JOIN workouts w ON s.workout_id=w.id
            WHERE s.user_id=? GROUP BY w.id,w.name,w.icon ORDER BY times_done DESC
        ");
        $s->execute([$id]); return $s->fetchAll();
    }
    public function recentByUser(int $id, int $limit=10): array {
        $s = $this->db->prepare("
            SELECT s.*, w.name AS workout_name, w.icon AS workout_icon, b.name AS buddy_name
            FROM sessions s JOIN workouts w ON s.workout_id=w.id LEFT JOIN buddies b ON s.buddy_id=b.id
            WHERE s.user_id=? ORDER BY s.session_date DESC, s.id DESC LIMIT ?
        ");
        $s->execute([$id, $limit]); return $s->fetchAll();
    }
    public function getAll(int $limit=20): array {
        return $this->db->query("
            SELECT s.*, u.name AS user_name, w.name AS workout_name, b.name AS buddy_name
            FROM sessions s JOIN users u ON s.user_id=u.id JOIN workouts w ON s.workout_id=w.id
            LEFT JOIN buddies b ON s.buddy_id=b.id ORDER BY s.session_date DESC LIMIT {$limit}
        ")->fetchAll();
    }
    public function avgDurationAll(): int {
        return (int)$this->db->query("SELECT ROUND(AVG(duration)) FROM sessions")->fetchColumn();
    }
    public function countActiveToday(): int {
        return (int)$this->db->query("SELECT COUNT(DISTINCT user_id) FROM sessions WHERE session_date=CURDATE()")->fetchColumn();
    }
}