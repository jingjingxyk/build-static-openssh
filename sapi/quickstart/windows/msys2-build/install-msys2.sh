#!/usr/bin/env bash
set -x

__DIR__=$(
  cd "$(dirname "$0")"
  pwd
)
__PROJECT__=$(
  cd ${__DIR__}/../../../../
  pwd
)
cd ${__PROJECT__}
pwd

MIRROR=''
IS_UPGRADE=0
while [ $# -gt 0 ]; do
  case "$1" in
  --mirror)
    MIRROR="$2"
    ;;
  --upgrade)
    IS_UPGRADE=1
    ;;
  --*)
    echo "Illegal option $1"
    ;;
  esac
  shift $(($# > 0 ? 1 : 0))
done

case "$MIRROR" in
china)
  sed -i "s#https\?://mirror.msys2.org/#https://mirrors.tuna.tsinghua.edu.cn/msys2/#g" /etc/pacman.d/mirrorlist*
  ;;
esac

if [ $IS_UPGRADE == 1 ]; then
  # 升级
  pacman -Su --noconfirm
fi

# 搜索包
# pacman -Ss  curl

# 将本地的包数据库与远程的仓库进行了同步
# pacman -Sy

# 无须确认安装包
pacman -S --needed --noconfirm git curl wget openssl

pacman -S --needed --noconfirm zip unzip xz gcc cmake make

pacman -S --needed --noconfirm re2c

pacman -S --needed --noconfirm openssl-devel libreadline

pacman -S --needed --noconfirm lzip
pacman -S --needed --noconfirm zip unzip
pacman -S --needed --noconfirm bison automake autoconf libtool coreutils
pacman -S --needed --noconfirm libcurl-devel libxml2-devel libxslt-devel
pacman -S --needed --noconfirm zlib-devel
pacman -S --needed --noconfirm libbz2-devel liblz4-devel liblzma-devel libcares-devel
pacman -S --needed --noconfirm libyaml-devel libzstd-devel libreadline-devel
pacman -S --needed --noconfirm libssh2-devel libidn2-devel gettext-devel
pacman -S --needed --noconfirm libzstd-devel
pacman -S --needed --noconfirm icu-devel
pacman -S --needed --noconfirm libsqlite-devel libsqlite
pacman -S --needed --noconfirm gmp-devel
pacman -S --needed --noconfirm libintl
pacman -S --needed --noconfirm pcre2
pacman -S --needed --noconfirm brotli-devel
pacman -S --needed --noconfirm pcre-devel
pacman -S --needed --noconfirm libedit-devel

: <<'COMMENT'

# 不存在的包
pacman -S --needed --noconfirm libpcre2-devel libssl-devel libgmp-devel
pacman -S --needed --noconfirm ImageMagick libpng-devel libjpeg-devel libfreetype-devel libwebp-devel libsqlite3-devel
pacman -S --needed --noconfirm libzip-devel libicu-devel libonig-devel libsodium-devel
pacman -S --needed --noconfirm libMagick-devel  libbrotli-devel libintl-devel libpq-devel
pacman -S --needed --noconfirm libpq5 libpq-devel
pacman -S --needed --noconfirm gcc-g++

# msys 环境下 可以安装 re2c
# 不需要执行 bash ./sapi/scripts/cygwin/install-re2c.sh


COMMENT

# 清理缓存
pacman -Scc --noconfirm
