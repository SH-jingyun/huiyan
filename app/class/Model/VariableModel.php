<?php

namespace Model;

use Core\Head;


class VariableModel extends Head {

    public function getVar ($name) {
        $sql = 'SELECT variable_value FROM t_variable WHERE variable_name = ?';
        $value = $this->locator->db->getOne($sql, $name);
        if (!$value) {
            throw new \Exception(sprintf('Empty vaiable %s', $name));
        }
        return $value;
    }
}
