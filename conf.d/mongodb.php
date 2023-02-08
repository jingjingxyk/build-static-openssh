<?php

use SwooleCli\Preprocessor;
use SwooleCli\Extension;

return function (Preprocessor $p) {
    $p->addExtension((new Extension('mongodb'))
        ->withOptions('--enable-mongodb')
        ->withPeclVersion('1.14.2')
        ->withManual('https://www.php.net/mongodb')
        ->withUrl('https://github.com/mongodb/mongo-php-driver.git')
        ->withLicense('https://github.com/mongodb/mongo-php-driver/blob/master/LICENSE')
    );
};
