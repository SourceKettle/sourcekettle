<?php
if (array_key_exists('error', $data)) {
    echo json_encode($data);
} if (empty($data)) {
        echo $this->element('Task/Board/empty', array('e' => 1, 'c' => 'tasks'));
} else {
    foreach ($data as $task) {
        echo $this->element('Task/lozenge', array('task' => $task));
    }
}
