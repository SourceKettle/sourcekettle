<?php
if (array_key_exists('error', $data)) {
    echo json_encode($data);
} if (empty($data)) {
        echo $this->element('Task/board/empty', array('e' => 1, 'c' => 'tasks'));
} else {
    foreach ($data as $task) {
        echo $this->element('Task/element_1', array('task' => $task));
    }
}
