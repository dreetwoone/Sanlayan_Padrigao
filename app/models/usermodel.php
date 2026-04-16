<?php
class UserModel extends Model {

    public function findByUsernameOrEmail(string $u): array|false {
        $s = $this->db->prepare("SELECT * FROM users WHERE name=? OR email=? LIMIT 1");
        $s->execute([$u, $u]);
        return $s->fetch();
    }

    public function create(string $name, string $email, string $hash): int {
        $this->db->prepare("INSERT INTO users (name,email,password,role,status) VALUES (?,?,?,'member','active')")
                 ->execute([$name, $email, $hash]);
        return (int)$this->db->lastInsertId();
    }

    public function nameExists(string $name): bool {
        $s = $this->db->prepare("SELECT id FROM users WHERE name=?");
        $s->execute([$name]);
        return (bool)$s->fetch();
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    }

    public function findById(int $id): array|false {
        $s = $this->db->prepare("SELECT * FROM users WHERE id=?");
        $s->execute([$id]);
        return $s->fetch();
    }

    public function updateStatus(int $id, string $status): void {
        $this->db->prepare("UPDATE users SET status=? WHERE id=?")->execute([$status, $id]);
    }

    public function toggleStatus(int $id): string {
        $u = $this->findById($id);
        $new = $u['status'] === 'active' ? 'banned' : 'active';
        $this->updateStatus($id, $new);
        return $new;
    }

    public function updateRole(int $id, string $role): void {
        $this->db->prepare("UPDATE users SET role=? WHERE id=?")->execute([$role, $id]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}