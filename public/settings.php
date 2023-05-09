<?php

# APPLICATION DIRECTORY 
define('DOCUMENT_ROOT', __DIR__ . DIRECTORY_SEPARATOR);

# UPLOADS CONFIGURATION
define('UPLOAD_DIRECTORY', 'uploads' . DIRECTORY_SEPARATOR);
define('UPLOAD_MAX_SIZE', 20971520); # 20Mo en octets 
define('IMAGE_MIME_TYPE', ['image/apng', 'image/bmp', 'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/svg', 'image/tiff', 'image/webp',]);

define('PROFILE_IMAGE_DIRECTORY', 'profile-image' . DIRECTORY_SEPARATOR);

# MAILER CONFIGURATION
define('DEFAULT_EMAIL_SENDER', 'noreply@agathefrederick.fr');
define('EMAIL_TEMPLATE_PATH', 'emails' . DIRECTORY_SEPARATOR);
