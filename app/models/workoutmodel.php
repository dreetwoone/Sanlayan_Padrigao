<?php
class WorkoutModel extends Model {

    public function getAll(): array {
        return $this->db->query("SELECT * FROM workouts")->fetchAll();
    }

    public function getPopularity(): array {
        return $this->db->query("
            SELECT w.name, COUNT(b.id) AS booking_count
            FROM workouts w LEFT JOIN bookings b ON b.workout_id=w.id
            GROUP BY w.id,w.name ORDER BY booking_count DESC
        ")->fetchAll();
    }
}