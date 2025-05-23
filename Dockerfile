# 构建SchoolAI项目

# 使用官方的 PHP 8.2 镜像，并包含 Apache
FROM php:8.2-apache

# 安装所需的 PHP 扩展
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libcurl4-openssl-dev \
    libgmp-dev \
    libicu-dev \
    libbz2-dev \
    unzip \
    zlib1g-dev \
    && docker-php-ext-install zip curl pdo_mysql pdo_sqlite openssl intl gmp gettext bz2 fileinfo mysqli \
    

# 安装 PHP 的 Redis 扩展
RUN pecl install redis && docker-php-ext-enable redis

# 启用 Apache 的 rewrite 模块
RUN a2enmod rewrite

# 设置工作目录
RUN mkdir -p /var/www/
WORKDIR /var/www/

# 克隆项目到 /var/www/
RUN git clone https://github.com/SmartSchoolAI/SchoolDataCenter.git /var/www/

# 解压webroot.zip文件
RUN unzip /var/www/htdocs/output/webroot.zip -d /var/www/html

# 安装 git Node.js 和 npm, 主要用于安装和编译前端项目
RUN apt-get update && apt-get install -y git nodejs npm

# 修改 Config.ts 文件中的 BackendApi 值
# 源代码中是后端的演示地址, 需要在前端中修改为DOCKER中本地镜像中的地址.
# 因为前端项目编译为静态的HTML和CSS文件以后,和后端的项目是在同一个Webroot下面,所以路径只需要写为 /aipptx/ , 如果你的后端是一个独立的URL地址, 则需要写完整的地址.

WORKDIR /var/www/SmartSchoolAI/htdocs
RUN npm install
# RUN npm run build

# 暴露端口 80（Apache 默认端口）和 6379（Redis 默认端口）
EXPOSE 8888

# 启动 Apache 和 Redis
CMD ["sh", "-c", "redis-server --daemonize yes && apache2-foreground"]
