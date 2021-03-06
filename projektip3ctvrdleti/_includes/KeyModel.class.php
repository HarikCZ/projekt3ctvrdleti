<?php

class KeyModel
{
    public ?int $key_id, $room_id, $employee_id;
    public array $validationErrors = [];

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function __construct()
    {
        // $this->keys = $keys;
    }

    public function insert($employee_id, $room_id) : bool
    {
        try {
            $sql = "INSERT INTO `key` (`employee`, `room`) VALUES (:employee_id, :room_id)";
            $stmt = DB::getConnection()->prepare($sql);
            $stmt->bindParam(':employee_id', $employee_id);
            $stmt->bindParam(':room_id', $room_id);
            return $stmt->execute();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update() : bool
    {
        // $sql = "UPDATE room SET name=:name, no=:no, phone=:phone WHERE room_id=:room_id";
        //
        // $stmt = DB::getConnection()->prepare($sql);
        // $stmt->bindParam(':room_id', $this->room_id);
        // $stmt->bindParam(':name', $this->name);
        // $stmt->bindParam(':no', $this->no);
        // $stmt->bindParam(':phone', $this->phone);
        //
        // return $stmt->execute();
        return false;
    }

    public static function getById($roomId)
    {
        // $stmt = DB::getConnection()->prepare("SELECT * FROM `room` WHERE `room_id`=:room_id");
        // $stmt->bindParam(':room_id', $roomId);
        // $stmt->execute();
        //
        // $record = $stmt->fetch();
        //
        // if (!$record) {
        //     return null;
        // }
        //
        // return array(
        // room_id => $record->room_id,
        // name => $record->name,
        // no => $record->no,
        // phone => $record->phone
        // );
    }

    public function getAll($orderBy = "name", $orderDir = "ASC") : PDOStatement
    {

        $stmt = DB::getConnection()->prepare("SELECT * FROM `key` ORDER BY `{$orderBy}` {$orderDir}");
        $stmt->execute();
        return $stmt;

    }

    public function deleteById(int $key_id) : bool
    {
        $sql = "DELETE FROM key WHERE key_id=:key_id";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bindParam(':key_id', $key_id);
        return $stmt->execute();
    }

    public function delete() : bool
    {
        return self::deleteById($this->key_id);
    }


    public function getFromPost() : self
    {
        $room = new self();

        $room->key_id = filter_input(INPUT_POST, "key_id", FILTER_VALIDATE_INT);
        $room->room_id = filter_input(INPUT_POST, "room_id");
        $room->employee_id = filter_input(INPUT_POST, "employee_id");

        return $room;
    }

    public function validate() : bool
    {
        return true;

        $isOk = true;
        $errors = [];

        // if (!$this->name) {
        //     $isOk = false;
        //     $errors["name"] = "Nesm?? b??t pr??zdn??";
        // }
        //
        // if (!$this->no) {
        //     $isOk = false;
        //     $errors["no"] = "Nesm?? b??t pr??zdn??";
        // } elseif (!filter_var($this->no, FILTER_VALIDATE_INT)) {
        //     $errors["no"] = "Nen?? ????slo!";
        //     $this->no = null;
        //     $isOk = false;
        // }
        //
        // if ($this->phone === "") {
        //     $this->phone = null;
        // }  elseif (!filter_var($this->phone, FILTER_VALIDATE_INT)) {
        //     $errors["phone"] = "Nen?? ????slo!";
        //     $this->phone = null;
        //     $isOk = false;
        // }

        $this->validationErrors = $errors;
        return $isOk;
    }
}
