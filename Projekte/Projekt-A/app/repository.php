<?php
require_once 'mysql.php';
require_once 'functions.php';
require_once '../config/config.php';

abstract class Repository
{
    protected $pdo;
    protected $tableName;

    public function __construct (PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->tableName = $this->getTableName();
    }

    abstract protected function getTableName(): string;

    abstract public function create(array $post): array;
    abstract public function update($id, array $post): bool;

    public function delete($id): bool
    {
        $sql = "DELETE FROM {$this->tableName} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    abstract public function findOne($id): array;
    abstract public function findSome(array $get, int $rows = 100, int $offset = 0): array;
    abstract public function findAll(): array;
}

class RepositoryUsers extends Repository
{
    protected function getTableName(): string
    {
        return 'users';
    }

    public function create(array $post): array
    {
        $firstname = cleanInput($post['first_name'] ?? '');
        $lastname = cleanInput($post['last_name'] ?? '');
        $email = cleanInput($post['email'] ?? '');
        $username = cleanInput($post['username'] ?? '');
        $password = cleanInput($post['password'] ?? '');
        $active = cleanInput($post['active'] ?? 0);
        $is_admin = cleanInput($post['is_admin'] ?? 0);

        if (empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password)) {
            return ['status' => false, 'message' => 'Bitte füllen Sie alle erforderlichen Felder aus!'];
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO {$this->tableName} (first_name, last_name, email, username, password, active, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $bindValues = [$firstname, $lastname, $email, $username, $hashed_password, $active, $is_admin];

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($bindValues);
            return ['status' => true, 'message' => 'Benutzer erfolgreich erstellt.'];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'Fehler beim Erstellen des Benutzers: ' . $e->getMessage()];
        }
    }

    public function update($id, array $post): bool
    {
        $firstname = $post['first_name'] ?? '';
        $lastname = $post['last_name'] ?? '';
        $username = $post['username'] ?? '';
        $last_change_date = date('Y-m-d H:i:s', time());
        $last_editor_id = $_SESSION['user_id'] ?? null;
        $active = $post['active'] ?? 0;
        $is_admin = $post['is_admin'] ?? 0;

        $sql = "UPDATE {$this->tableName} SET first_name=?, last_name=?, username=?, last_change_date=?, last_editor_id=?, active=?, is_admin=? WHERE id=?";
        $bindValues = [$firstname, $lastname, $username, $last_change_date, $last_editor_id, $active, $is_admin, $id];

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($bindValues);
    }

    public function findOne($id): array
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findSome(array $get, int $rows = 100, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE ";

        $conditions = [];
        $params = [];

        foreach ($get as $field => $value) {
            $conditions[] = "$field = :$field";
            $params[$field] = $value;
        }

        $sql .= implode(' AND ', $conditions);
        $sql .= " LIMIT $offset, $rows";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->tableName}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
