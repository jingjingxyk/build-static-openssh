<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $coturn_prefix = COTURN_PREFIX;
    $openssl_prefix = OPENSSL_PREFIX;
    $libevent_prefix = LIBEVENT_PREFIX;
    $pgsql_prefix = PGSQL_PREFIX;
    $sqlite3_prefix = SQLITE3_PREFIX;
    $hiredis_prefix = HIREDIS_PREFIX;
    $p->addLibrary(
        (new Library('coturn'))
            ->withHomePage('https://github.com/coturn/coturn/')
            ->withManual('https://github.com/coturn/coturn/tree/master/docs')
            ->withLicense('https://github.com/coturn/coturn/blob/master/LICENSE', Library::LICENSE_SPEC)
            //->withUrl('https://github.com/coturn/coturn/archive/refs/tags/docker/4.6.2-r1.tar.gz')
            //->withFile('coturn-v4.6.2.tar.gz')
            ->withFile('coturn-latest.tar.gz')
            ->withDownloadScript(
                'coturn',
                <<<EOF
                # git clone -b 4.6.2 --depth=1 https://github.com/coturn/coturn.git
                # git clone -b master --depth=1 https://github.com/coturn/coturn.git
                # git clone -b test --depth=1 https://github.com/jingjingxyk/coturn.git
                git clone -b fix_openssl_no_threads --depth=1 https://github.com/jingjingxyk/coturn.git
EOF
            )
            ->withAutoUpdateFile()
            ->withPrefix($coturn_prefix)
            ->withCleanBuildDirectory()
            ->withCleanPreInstallDirectory($coturn_prefix)
            ->withBuildLibraryCached(false)

            ->withConfigure(
                <<<EOF

           mkdir -p build
           cd build



           export TURN_NO_PROMETHEUS=ON
           export TURN_NO_SYSTEMD=ON
           export TURN_NO_MYSQL=ON
           export TURN_NO_MONGO=ON
           export TURN_NO_SQLITE=OFF
           export TURN_NO_PQ=OFF
           export TURN_NO_HIREDIS=ON


           cmake .. \
           -DCMAKE_INSTALL_PREFIX={$coturn_prefix} \
           -DCMAKE_C_STANDARD=C11 \
           -DCMAKE_POLICY_DEFAULT_CMP0074=NEW \
           -DCMAKE_POLICY_DEFAULT_CMP0077=NEW \
           -DCMAKE_BUILD_TYPE=Release \
           -DBUILD_SHARED_LIBS=OFF \
           -DBUILD_STATIC_LIBS=ON \
           -DCMAKE_DISABLE_FIND_PACKAGE_mongo=ON \
           -DCMAKE_DISABLE_FIND_PACKAGE_libsystemd=ON \
           -DCMAKE_DISABLE_FIND_PACKAGE_Prometheus=ON \
           -DCMAKE_DISABLE_FIND_PACKAGE_MySQL=ON \
           -DOPENSSL_USE_STATIC_LIBS=ON \
           -DBUILD_TEST=OFF \
           -DFUZZER=OFF \
           -DCMAKE_PREFIX_PATH="{$openssl_prefix};{$libevent_prefix};{$sqlite3_prefix};{$pgsql_prefix}"

           # -DOpenSSL_ROOT={$openssl_prefix} \
           # -DLibevent_ROOT={$libevent_prefix} \
           # -DSQLite_DIR={$sqlite3_prefix} \
           # -DPostgreSQL_DIR={$pgsql_prefix} \
           # -Dhiredis_ROOT={$hiredis_prefix} \
           # -DCMAKE_C_FLAGS="-Werror -pedantic" \

           # -DCMAKE_MODULE_LINKER_FLAGS="-lpgcommon -lpgport" \
           # -DCMAKE_STATIC_LINKER_FLAGS="" \
           # -DCMAKE_EXE_LINKER_FLAGS="-static " \

           #  hiredis
           # TURN_NO_SCTP
           # TURN_NO_THREAD_BARRIERS
           # TURN_NO_GCM

           # make -j {$p->getMaxJob()}
           # make install


EOF
            )
            ->withConfigure(
                <<<EOF

            export TURN_NO_PROMETHEUS=ON
            export TURN_NO_SYSTEMD=ON
            export TURN_NO_MYSQL=ON
            export TURN_NO_MONGO=ON
            export TURN_NO_SQLITE=OFF
            export TURN_NO_PQ=OFF
            export TURN_NO_HIREDIS=OFF


            PACKAGES='sqlite3'
            PACKAGES="\$PACKAGES libevent  libevent_core libevent_extra  libevent_openssl  libevent_pthreads"
            PACKAGES="\$PACKAGES libpq"
            PACKAGES="\$PACKAGES hiredis"
            SSL_CFLAGS="$(pkg-config  --cflags-only-I  --static openssl libcrypto libssl) " \
            SSL_LIBS="$(pkg-config    --libs           --static openssl libcrypto libssl) " \
            CPPFLAGS="$(pkg-config  --cflags-only-I  --static \$PACKAGES)" \
            LDFLAGS="$(pkg-config   --libs-only-L    --static \$PACKAGES) -static" \
            LIBS="$(pkg-config      --libs-only-l    --static \$PACKAGES) -lstdc++ -lm -lpgcommon -lpgport " \
            CFLAGS="-O3  -g  -std=gnu11 " \
            ./configure  \
            --prefix=$coturn_prefix

EOF
            )
            ->withBinPath($coturn_prefix . '/bin/')
            ->withDependentLibraries(
                'libevent',
                'openssl',
                'sqlite3',
                //'pgsql',
                'hiredis',
                //'libmongoc',
                // 'prometheus_client_c'
            )
    );
};
