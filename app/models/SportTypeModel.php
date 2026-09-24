<?php
class SportTypeModel extends Model
{
    public function all(): array
    {
        return $this->select('SELECT id, code, name FROM sport_types ORDER BY id');
    }
}
