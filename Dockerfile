# 构建SchoolAI项目
# 当前镜像仅为提供在LINUX或是MAC下面开发使用, 不要应用于生产环境

# 使用官方的 PHP 8.2 镜像，并包含 Apache
FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive

# 安装 MySQL Server 和依赖
RUN apt-get update
RUN apt-get install -y redis-server gnupg lsb-release wget default-mysql-server procps

# 修改 MySQL 端口为 3386
RUN sed -i 's/3306/3386/' /etc/mysql/mysql.conf.d/mysqld.cnf || true


# 安装所需的 PHP 扩展
RUN apt-get install -y --no-install-recommends build-essential libzip-dev libcurl4-openssl-dev libgmp-dev libicu-dev libbz2-dev zlib1g-dev libssl-dev libxml2-dev unzip openssh-server

RUN docker-php-ext-configure zip

RUN docker-php-ext-install zip curl pdo_mysql

RUN docker-php-ext-install intl gettext fileinfo gmp mysqli

RUN pecl install redis && docker-php-ext-enable redis

RUN a2enmod rewrite

# openssh-server
RUN mkdir /var/run/sshd
RUN sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN echo "root:MyRootPass123!" | chpasswd


# 启用 Apache 的 rewrite 模块
RUN a2enmod rewrite

# 设置工作目录
RUN mkdir -p /var/www/
WORKDIR /var/www/

# 安装 git Node.js 和 npm, 主要用于安装和编译前端项目
RUN apt-get install -y git nodejs npm vim

# 克隆项目到 /var/www/
RUN git clone https://github.com/SmartSchoolAI/SchoolDataCenter.git

# 替换 Apache 配置文件
RUN cp /var/www/SchoolDataCenter/docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN cp /var/www/SchoolDataCenter/docker/php.ini /usr/local/etc/php/php.ini

# 解压webroot.zip文件
# RUN unzip /var/www/SchoolDataCenter/htdocs/output/webroot.zip -d /var/www/html

WORKDIR /var/www/SchoolDataCenter/htdocs
RUN npm install
RUN npm run build

# 暴露端口 80（Apache 默认端口）和 6379（Redis 默认端口）
EXPOSE 8888 22 3386 3000

# 启动脚本：先启动 MySQL，初始化 root 密码，再启动 Apache
CMD ["bash", "-c", "\
    /usr/sbin/sshd; \
    echo 'Starting MySQL...'; \
    mysqld_safe & \
    echo 'Starting Redis...'; \
    redis-server --daemonize yes; \
    echo 'Starting Apache...'; \
    apache2-foreground"]


# docker build -t schoolai . 构建镜像
# docker run -d -p 8888:80 -p 1922:22 schoolai 启动容器
# apachectl graceful 重新启动Apache


