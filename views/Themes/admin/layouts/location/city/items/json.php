<?php

require 'table-lists.php';

echo json_encode( array(
	'settings' => $this->results,
	'body' => $table,
));