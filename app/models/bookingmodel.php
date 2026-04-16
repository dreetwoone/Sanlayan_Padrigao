<?php
class BookingModel extends Model {

    public function create(int $userId, int $buddyId, int $workoutId, string $date, string $time, string $notes=''): int {
        $this->db->prepare("
            INSERT INTO bookings (user_id,buddy_id,workout_id,date,time,notes,status)
            VALUES (?,?,?,?,?,?,'pending')
        ")->execute([$userId, $buddyId, $workoutId, $date, $time, $notes]);
        return (int)$this->db->lastInsertId();
    }

    public function getAll(): array {
        return $this->db->query("
            SELECT b.*, u.name AS user_name, bd.name AS buddy_name, w.name AS workout_name
            FROM bookings b
            JOIN users u ON b.user_id=u.id
            JOIN buddies bd ON b.buddy_id=bd.id
            JOIN workouts w ON b.workout_id=w.id
            ORDER BY b.created_at DESC
        ")->fetchAll();
    }

    public function updateStatus(int $id, string $status): void {
        $this->db->prepare("UPDATE bookings SET status=? WHERE id=?")->execute([$status, $id]);
    }

    public function countToday(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM bookings WHERE DATE(created_at)=CURDATE()")->fetchColumn();
    }
}