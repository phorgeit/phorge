<?php

$conn = id(new PhorgeRepository())->establishConnection('w');
if (queryfx_one($conn, "SHOW COLUMNS FROM `repository` LIKE 'description'")) {
  queryfx($conn, 'ALTER TABLE `repository` DROP `description`');
}
