<?php

class ArletDb
{
    private $pathDatabase;

    public function __construct($pathDatabase, $nameDatabase)
    {
        $this->pathDatabase = realpath($pathDatabase . DIRECTORY_SEPARATOR . $nameDatabase);
        if (!file_exists($this->pathDatabase)) {
            mkdir($this->pathDatabase, 0777, true);
        }
    }

    public function createTableIfNotExists($tableName, $columns = [], $autoIncrementColumn = null)
    {
        $tablePath = $this->pathDatabase . DIRECTORY_SEPARATOR . $tableName . '.json';

        foreach ($columns as $key => $type) {
            if ($type !== 'string' && $type !== 'number') {
                throw new Exception('Tipo de dato no permitido');
            }
        }

        if (count($columns) === 0) {
            throw new Exception('No se puede crear una tabla sin columnas');
        }

        $columns['id_arlet'] = 'string';
        $tableData = [
            'columns' => $columns,
            'data' => [],
            'autoIncrement' => []
        ];

        if ($autoIncrementColumn) {
            if (!isset($columns[$autoIncrementColumn]) || $columns[$autoIncrementColumn] !== 'number') {
                throw new Exception('La columna de auto-incremento debe ser de tipo número y debe existir en la tabla');
            }
            $tableData['autoIncrement'][$autoIncrementColumn] = 1;
        }

        if (file_exists($tablePath)) {
            return $tableData;
        }

        file_put_contents($tablePath, json_encode($tableData, JSON_PRETTY_PRINT));
        echo 'Tabla creada' . PHP_EOL;
        return $tableData;
    }

    public function insert($tableName, $data)
    {
        $tablePath = $this->pathDatabase . DIRECTORY_SEPARATOR . $tableName . '.json';

        if (!file_exists($tablePath)) {
            throw new Exception('La tabla no existe');
        }

        $tableData = json_decode(file_get_contents($tablePath), true);
        $columns = $tableData['columns'];
        $autoIncrement = $tableData['autoIncrement'];

        foreach ($autoIncrement as $key => $value) {
            $data[$key] = $autoIncrement[$key]++;
        }

        $data['id_arlet'] = $this->generateUuid();
        $dataKeys = array_keys($data);

        if (count($columns) !== count($dataKeys)) {
            throw new Exception('La cantidad de columnas no coincide');
        }

        foreach ($dataKeys as $key) {
            if (!isset($columns[$key]) || gettype($data[$key]) !== $columns[$key]) {
                throw new Exception('Tipo de dato incorrecto o columna inexistente');
            }
        }

        $tableData['data'][] = $data;
        file_put_contents($tablePath, json_encode($tableData, JSON_PRETTY_PRINT));

        return true;
    }

    public function insertIfNotExists($tableName, $data)
    {
        $tablePath = $this->pathDatabase . DIRECTORY_SEPARATOR . $tableName . '.json';

        if (!file_exists($tablePath)) {
            throw new Exception('La tabla no existe');
        }

        $tableData = json_decode(file_get_contents($tablePath), true);
        $columns = $tableData['columns'];
        $autoIncrement = $tableData['autoIncrement'];

        $exists = array_filter($tableData['data'], function ($item) use ($data) {
            return array_intersect_assoc($item, $data) == $data;
        });

        if ($exists) {
            echo 'El dato ya existe, no se ha insertado' . PHP_EOL;
            return false;
        }

        foreach ($autoIncrement as $key => $value) {
            $data[$key] = $autoIncrement[$key]++;
        }

        $data['id_arlet'] = $this->generateUuid();
        $dataKeys = array_keys($data);

        if (count($columns) !== count($dataKeys)) {
            throw new Exception('La cantidad de columnas no coincide');
        }

        foreach ($dataKeys as $key) {
            if (!isset($columns[$key]) || gettype($data[$key]) !== $columns[$key]) {
                throw new Exception('Tipo de dato incorrecto o columna inexistente');
            }
        }

        $tableData['data'][] = $data;
        file_put_contents($tablePath, json_encode($tableData, JSON_PRETTY_PRINT));
        echo 'Dato insertado' . PHP_EOL;

        return true;
    }

    public function getAll($tableName)
    {
        $tablePath = $this->pathDatabase . DIRECTORY_SEPARATOR . $tableName . '.json';

        if (!file_exists($tablePath)) {
            throw new Exception('La tabla no existe');
        }

        $tableData = json_decode(file_get_contents($tablePath), true);
        return $tableData['data'];
    }

    public function query($tableName, $criteria = [], $options = [])
    {
        $tablePath = $this->pathDatabase . DIRECTORY_SEPARATOR . $tableName . '.json';

        if (!file_exists($tablePath)) {
            throw new Exception('La tabla no existe');
        }

        $tableData = json_decode(file_get_contents($tablePath), true);
        $result = array_filter($tableData['data'], function ($item) use ($criteria) {
            foreach ($criteria as $key => $value) {
                if ($item[$key] !== $value) {
                    return false;
                }
            }
            return true;
        });

        if (isset($options['sort'])) {
            list($key, $order) = $options['sort'];
            usort($result, function ($a, $b) use ($key, $order) {
                return ($a[$key] <=> $b[$key]) * ($order === 'desc' ? -1 : 1);
            });
        }

        if (isset($options['limit'])) {
            $result = array_slice($result, 0, $options['limit']);
        }

        return $result;
    }

    public function getById($tableName, $arlet_id)
    {
        $tablePath = $this->pathDatabase . DIRECTORY_SEPARATOR . $tableName . '.json';

        if (!file_exists($tablePath)) {
            throw new Exception('La tabla no existe');
        }

        $tableData = json_decode(file_get_contents($tablePath), true);
        foreach ($tableData['data'] as $item) {
            if ($item['id_arlet'] === $arlet_id) {
                return $item;
            }
        }

        return null;
    }

    public function update($tableName, $criteria, $newData)
    {
        $tablePath = $this->pathDatabase . DIRECTORY_SEPARATOR . $tableName . '.json';

        if (!file_exists($tablePath)) {
            throw new Exception('La tabla no existe');
        }

        $tableData = json_decode(file_get_contents($tablePath), true);

        foreach ($tableData['data'] as &$item) {
            if (array_intersect_assoc($item, $criteria) == $criteria) {
                foreach ($newData as $key => $value) {
                    if (isset($item[$key])) {
                        $item[$key] = $value;
                    }
                }
            }
        }

        file_put_contents($tablePath, json_encode($tableData, JSON_PRETTY_PRINT));

        return true;
    }

    public function delete($tableName, $criteria)
    {
        $tablePath = $this->pathDatabase . DIRECTORY_SEPARATOR . $tableName . '.json';

        if (!file_exists($tablePath)) {
            throw new Exception('La tabla no existe');
        }

        $tableData = json_decode(file_get_contents($tablePath), true);
        $tableData['data'] = array_filter($tableData['data'], function ($item) use ($criteria) {
            return array_intersect_assoc($item, $criteria) != $criteria;
        });

        file_put_contents($tablePath, json_encode($tableData, JSON_PRETTY_PRINT));

        return true;
    }

    public function select($tableName, $columns, $criteria = [], $options = [])
    {
        $tablePath = $this->pathDatabase . DIRECTORY_SEPARATOR . $tableName . '.json';

        if (!file_exists($tablePath)) {
            throw new Exception('La tabla no existe');
        }

        $tableData = json_decode(file_get_contents($tablePath), true);

        $result = array_filter($tableData['data'], function ($item) use ($criteria) {
            foreach ($criteria as $key => $condition) {
                if (is_array($condition)) {
                    foreach ($condition as $op => $val) {
                        if (!$this->evaluateCondition($item[$key], $op, $val)) {
                            return false;
                        }
                    }
                } else {
                    if (!$this->evaluateCondition($item[$key], '=', $condition)) {
                        return false;
                    }
                }
            }
            return true;
        });

        if (isset($options['sort'])) {
            list($key, $order) = $options['sort'];
            usort($result, function ($a, $b) use ($key, $order) {
                return ($a[$key] <=> $b[$key]) * ($order === 'desc' ? -1 : 1);
            });
        }

        if (isset($options['limit'])) {
            $result = array_slice($result, 0, $options['limit']);
        }

        return array_map(function ($item) use ($columns) {
            return array_intersect_key($item, array_flip($columns));
        }, $result);
    }

    private function evaluateCondition($value, $operator, $condition)
    {
        switch ($operator) {
            case '>':
                return $value > $condition;
            case '>=':
                return $value >= $condition;
            case '<':
                return $value < $condition;
            case '<=':
                return $value <= $condition;
            case '!=':
                return $value != $condition;
            case '=':
            default:
                return $value == $condition;
        }
    }

    private function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}