<?php
require_once '../swift/lib/swift_required.php';

$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
  ->setUsername('pixgalleryweb@gmail.com')
  ->setPassword('ForTesting');
?>