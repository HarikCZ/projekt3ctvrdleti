<?php

class EmployeeModel
{
    public ?int $employee_id;
    public ?string $name;
    public ?string $surname;
    public ?string $job;
    public ?string $wage;
    public ?string $room;
    public ?string $login;
    public ?string $pswd;
    public ?int $admin;


    public array $validationErrors = [];

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }


    public function __construct($params = array())
    {
        $this->employee_id = $params[employee_id] ?: null;
        $this->name = $params[name] ?: null;
        $this->surname = $params[surname] ?: null;
        $this->job = $params[job] ?: null;
        $this->wage = $params[wage] ?: null;
        $this->room = $params[room] ?: null;
        $this->login = $params[login] ?: null;
        $this->pswd = $params[pswd] ?: null;
        $this->admin = ($params[admin] === "1") ? 1 : 0;
        $this->keys = $params[keys] ?: null;
        $this->params = $params;
    }


    public function insert() : bool
    {
        try {
            $sql = "INSERT INTO employee (name, surname, job, wage, room, login, pswd, admin) VALUES (:name, :surname, :job, :wage, :room, :login, :pswd, :admin)";
            $stmt = DB::getConnection()->prepare($sql);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':surname', $this->surname);
            $stmt->bindParam(':job', $this->job);
            $stmt->bindParam(':wage', $this->wage);
            $stmt->bindParam(':room', $this->room);
            $stmt->bindParam(':login', $this->login);

            $pass_hash = password_hash($this->pswd, PASSWORD_BCRYPT, $options);
            $stmt->bindParam(
                ':pswd',
                password_hash(
                    $this->pswd, PASSWORD_BCRYPT,
                    $options = [
                    'cost' => 10
                    ]
                )
            );

            $stmt->bindParam(':admin', $this->admin, PDO::PARAM_INT);
            $stmt->execute();

            $sql = "SELECT employee_id FROM employee WHERE login = :login";
            $stmt = DB::getConnection()->prepare($sql);
            $stmt->bindParam(':login', $this->login);
            $stmt->execute();

            $this->employee_id =  $stmt->fetch()->employee_id;

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update() : bool
    {
        $sql = "UPDATE room SET name=:name, surname=:surname, job=:job, wage=:wage, room=:room, login=:login, pswd=:pswd WHERE employee_id=:employee_id";

        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bindParam(':employee_id', $this->employee_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':job', $this->job);
        $stmt->bindParam(':wage', $this->wage);
        $stmt->bindParam(':room', $this->room);
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':pswd', $this->pswd);

        return $stmt->execute();
    }

    public function getById()
    {
        $stmt = DB::getConnection()->prepare("SELECT employee.name AS name, employee.surname AS surname, room.name AS room, room.phone AS phone, employee.job AS job, employee_id, wage FROM employee LEFT JOIN room ON employee.room = room.room_id WHERE employee_id=:employee_id");
        $stmt->bindParam(':employee_id', $this->employee_id);
        $stmt->execute();
        $record = $stmt->fetch();

        if (!$record) {
            return null;
        }

        return array(
        employee_id => $record->employee_id,
        name => $record->name,
        surname => $record->surname,
        job => $record->job,
        wage => $record->wage,
        room => $record->room,
        keys => $record->keys
        );
    }

    public function getAll($orderBy = "name", $orderDir = "ASC") : PDOStatement
    {
        $stmt = DB::getConnection()->prepare("SELECT CONCAT(employee.surname, \" \", employee.name) AS name, room.name AS room, room.phone AS phone, employee.job AS job, employee_id FROM employee LEFT JOIN room ON employee.room = room.room_id ORDER BY ${orderBy} " . (($orderDir) ? "DESC" : "") . ", name");
        $stmt->execute();
        return $stmt;
    }

    public function deleteById(int $employeeId) : bool
    {
        $sql = "DELETE FROM room WHERE employee_id=:employee_id";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bindParam(':employee_id', $employeeId);
        return $stmt->execute();
    }

    public function delete() : bool
    {
        return self::deleteById($this->employee_id);
    }


    public function getFromPost() : self
    {
        return new self(
            array(
            employee_id => filter_input(INPUT_POST, "employee_id", FILTER_VALIDATE_INT),
            name => filter_input(INPUT_POST, "name"),
            surname => filter_input(INPUT_POST, "surname"),
            job => filter_input(INPUT_POST, "job"),
            wage => filter_input(INPUT_POST, "wage"),
            room => filter_input(INPUT_POST, "room"),
            login => filter_input(INPUT_POST, "login"),
            pswd => filter_input(INPUT_POST, "pswd"),
            admin => filter_input(INPUT_POST, "admin"),
            keys => $_POST[roomsForKeys] ?: null
            )
        );
    }

    public function validate() : bool
    {
        return true;

        $isOk = true;
        $errors = [];

        if (!$this->name) {
            $isOk = false;
            $errors["room_id"] = "Room name cannot be empty";
        }

        $this->keys = array_filter($this->keys, 'is_int');

        $this->validationErrors = $errors;
        return $isOk;
    }
}
