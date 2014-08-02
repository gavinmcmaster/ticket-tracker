<?php

// user permission types
define('USER_PERMISSION_VIEW', 1);
define('USER_PERMISSION_UPDATE', 2);
define('USER_PERMISSION_CRUD', 3);
define('USER_PERMISSION_ADMIN', 4);
// user types
define('USER_TYPE_GRAPHICS', 1);
define('USER_TYPE_PROGRAMMER', 2);
define('USER_TYPE_PROJECT_MANAGER', 3);
define('USER_TYPE_SUPPORT', 4);
define('USER_TYPE_QA', 5);

// ticket types
define('TICKET_TYPE_TASK', 1);
define('TICKET_TYPE_ENHANCEMENT', 2);
define('TICKET_TYPE_DEFECT', 3);

// ticket priority types
define('TICKET_PRIORITY_TYPE_MINOR', 1);
define('TICKET_PRIORITY_TYPE_MAJOR', 2);
define('TICKET_PRIORITY_TYPE_CRITICAL', 3);

// ticket status types
define('TICKET_STATUS_TYPE_NEW', 1);
define('TICKET_STATUS_TYPE_ASSIGNED', 2);
define('TICKET_STATUS_TYPE_CLOSED', 3);

// ticket resolution types
define('TICKET_RESOLUTION_TYPE_FIXED', 1);
define('TICKET_RESOLUTION_TYPE_INVALID', 2);
define('TICKET_RESOLUTION_TYPE_WONTFIX', 3);
define('TICKET_RESOLUTION_TYPE_DUPLICATE', 4);
define('TICKET_RESOLUTION_TYPE_WORKSFORME', 5);

// attachments locations
define('ATTACHMENTS_UPLOAD_DIRECTORY', 'attachments/');
